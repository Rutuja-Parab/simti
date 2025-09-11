<?php

namespace App\Http\Controllers;

use App\Models\Candidates;
use App\Models\Course;
use App\Models\Marks;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\MarksUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class MarksController extends Controller
{
    //
    public function create(Candidates $candidate)
    {
        $user = auth()->user();

        $courseDetail = $candidate->courseDetail;
        $course = $courseDetail ? $courseDetail->course : null;
        $subjects = collect();
        if ($course) {
            if ($user->role === 'faculty') {
                $subjects = Subject::where('course_id', $course->id)
                    ->whereIn('id', $user->subjects()->pluck('subjects.id'))
                    ->get();
            } else {
                $subjects = Subject::where('course_id', $course->id)->get();
            }
        }

        $courseDetails = \App\Models\CourseDetail::with('course')->orderBy('id', 'desc')->get();
        return view('marks.create', compact('candidate', 'subjects', 'courseDetails'));
    }

    public function store(Request $request, Candidates $candidate)
    {
        $user = auth()->user();

        $course = $candidate->courseDetail ? $candidate->courseDetail->course : null;
        $isGme = $course && stripos($course->name, 'gme') !== false;

        if ($isGme) {
            $rules = [
                'marks' => 'nullable|array',
                'marks.*' => 'array',
                'marks.*.*' => 'nullable|numeric|min:0',
            ];
        } else {
            $rules = [
                'marks' => 'nullable|array',
                'marks.*' => 'nullable|numeric|min:0',
            ];
        }

        $request->validate($rules);

        if ($user->role !== 'faculty') {
            $updates = [];
            if ($request->name !== $candidate->name)
                $updates['name'] = $request->name;
            if ($request->dob !== $candidate->dob->format('Y-m-d'))
                $updates['dob'] = $request->dob;
            if ($request->course_detail_id != $candidate->course_detail_id) {
                $updates['course_detail_id'] = $request->course_detail_id;
                $courseDetail = \App\Models\CourseDetail::with('course')->findOrFail($request->course_detail_id);
                // Extract roll part from old roll_no
                $oldParts = explode('-', $candidate->roll_no);
                $rollPart = isset($oldParts[2]) ? $oldParts[2] : '01';
                $batchNo = str_pad($courseDetail->batch_no, 2, '0', STR_PAD_LEFT);
                $courseName = $courseDetail->course->name;
                if (preg_match('/\((.*?)\)/', $courseName, $matches)) {
                    $courseCode = $matches[1];
                } else {
                    $courseCode = strtok($courseName, ' ');
                }
                $updates['roll_no'] = $courseCode . '-' . $batchNo . '-' . $rollPart;
            }
            if ($request->indos_no !== $candidate->indos_no)
                $updates['indos_no'] = $request->indos_no;
            if ($request->passport_no !== $candidate->passport_no)
                $updates['passport_no'] = $request->passport_no;
            if ($request->cdc_no !== $candidate->cdc_no)
                $updates['cdc_no'] = $request->cdc_no;
            if ($request->dgs_certificate_no !== $candidate->dgs_certificate_no)
                $updates['dgs_certificate_no'] = $request->dgs_certificate_no;
            if (!empty($updates)) {
                $candidate->update($updates);
            }
        }

        $allowedSubjectIds = $user->role === 'faculty'
            ? $user->subjects()->pluck('subjects.id')->toArray()
            : null;

        foreach ($request->input('marks', []) as $subjectId => $mark) {
            if ($user->role === 'faculty' && $allowedSubjectIds && !in_array($subjectId, $allowedSubjectIds)) {
                continue;
            }
            if ($isGme && is_array($mark)) {
                // GME: Save both terms
                foreach ([1, 2] as $term) {
                    $termMark = $mark[$term] ?? null;
                    if (!isset($termMark) || $termMark === '' || !is_numeric($termMark))
                        continue;
                    $marks = Marks::updateOrCreate(
                        [
                            'candidate_id' => $candidate->id,
                            'subject_id' => $subjectId,
                            'term' => $term
                        ],
                        [
                            'marks_obtained' => $termMark,
                            'status' => 'pending',
                            'approved_by' => null,
                            'approved_at' => null,
                            'rejection_reason' => null,
                            'last_edited_by' => $user->id
                        ]
                    );
                }
            } else {
                // Non-GME: Save single mark
                if (!isset($mark) || $mark === '' || !is_numeric($mark))
                    continue;
                $marks = Marks::updateOrCreate(
                    [
                        'candidate_id' => $candidate->id,
                        'subject_id' => $subjectId,
                        'term' => null
                    ],
                    [
                        'marks_obtained' => $mark,
                        'status' => 'pending',
                        'approved_by' => null,
                        'approved_at' => null,
                        'rejection_reason' => null,
                        'last_edited_by' => $user->id
                    ]
                );
            }
            $examcells = User::where('role', 'examcell')->get();
            Notification::send($examcells, new MarksUpdatedNotification($marks));
            // // faculty of that subject
            // $faculty = User::find($marks->faculty_id);
            // $faculty->notify(new MarksUpdatedNotification($marks));

            // Notify examcell users if edited by faculty
            // if ($user->role === 'faculty' || $user->role === 'teacher') {
            //     $examcellUsers = \App\Models\User::where('role', 'examcell')->get();
            //     foreach ($examcellUsers as $examcell) {
            //         $examcell->notify(new \App\Notifications\MarksEditedNotification([
            //             'faculty' => $user->name,
            //             'course' => $candidate->courseDetail && $candidate->courseDetail->course ? $candidate->courseDetail->course->name : '-',
            //             'batch' => $candidate->courseDetail ? $candidate->courseDetail->batch_no : '-',
            //             'candidate' => $candidate->name,
            //             'subject' => \App\Models\Subject::find($subjectId)->name ?? '-',
            //             'message' => 'Marks edited by ' . $user->name
            //         ]));
            //     }
            // }
        }

        return redirect()->route('candidate.view')->with('success', 'Candidate and marks saved successfully.');
    }
}

