<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseDetail;
use App\Models\Marks;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class CourseMarksController extends Controller
{
    // Step 1: Show course selection form
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'faculty') {
            $subjectIds = $user->subjects()->pluck('subjects.id');
            $courses = Course::whereHas('subjects', function ($q) use ($subjectIds) {
                $q->whereIn('subjects.id', $subjectIds);
            })->distinct()->orderBy('name')->get();
        } else {
            $courses = Course::orderBy('name')->get();
        }
        return view('marks.course.index', compact('courses'));
    }

    // Step 2: Show form to enter marks for a selected course
    public function edit($courseId)
    {
        $user = Auth::user();
        $course = Course::with(['subjects'])->findOrFail($courseId);
        $courseDetails = CourseDetail::with(['candidates.marks', 'course'])
            ->where('course_id', $courseId)
            ->orderBy('batch_no')
            ->get();

        if ($user->role === 'faculty') {
            $allowedSubjects = $user->subjects()->pluck('subjects.id')->toArray();
            $course->subjects = $course->subjects->whereIn('id', $allowedSubjects);
        }

        return view('marks.course.edit', compact('course', 'courseDetails'));
    }

    // Step 3: Store marks
    public function store(Request $request, $courseId)
    {
        $user = Auth::user();
        $allowedSubjectIds = $user->role === 'faculty'
        ? $user->subjects()->pluck('subjects.id')->toArray()
        : null;
        
        $course = Course::with(['subjects'])->findOrFail($courseId);
        $isGme = stripos($course->name, 'gme') !== false;
        foreach ($request->input('marks', []) as $candidateId => $subjectMarks) {
            foreach ($subjectMarks as $subjectId => $obtained) {
                if ($allowedSubjectIds && !in_array($subjectId, $allowedSubjectIds)) {
                    continue;
                }
                if ($isGme && is_array($obtained)) {
                    // GME: Save both terms
                    foreach ([1,2] as $term) {
                        $termMark = $obtained[$term] ?? null;
                        if ($termMark === null || $termMark === '') continue;
                        Marks::updateOrCreate(
                            [
                                'candidate_id' => $candidateId,
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
                    if ($obtained === null || $obtained === '') continue;
                    Marks::updateOrCreate(
                        [
                            'candidate_id' => $candidateId,
                            'subject_id' => $subjectId,
                            'term' => null
                        ],
                        [
                            'marks_obtained' => $obtained,
                            'status' => 'pending',
                            'approved_by' => null,
                            'approved_at' => null,
                            'rejection_reason' => null,
                            'last_edited_by' => $user->id
                        ]
                    );
                }

                // Notify examcell users if edited by faculty
                if ($user->role === 'faculty' || $user->role === 'teacher') {
                    $candidate = \App\Models\Candidates::find($candidateId);
                    $examcellUsers = \App\Models\User::where('role', 'examcell')->get();
                    foreach ($examcellUsers as $examcell) {
                        $examcell->notify(new \App\Notifications\MarksEditedNotification([
                            'faculty' => $user->name,
                            'course' => $candidate && $candidate->courseDetail && $candidate->courseDetail->course ? $candidate->courseDetail->course->name : '-',
                            'batch' => $candidate && $candidate->courseDetail ? $candidate->courseDetail->batch_no : '-',
                            'candidate' => $candidate ? $candidate->name : '-',
                            'subject' => \App\Models\Subject::find($subjectId)->name ?? '-',
                            'message' => 'Marks edited by ' . $user->name
                        ]));
                    }
                }
            }
        }
        return redirect()->route('course.marks.index')->with('success', 'Marks saved successfully!');
    }

    // Marksheet Wizard Page
    public function marksheetWizard()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'examcell'])) {
            abort(403);
        }
        $courses = \App\Models\Course::orderBy('name')->get();
        return view('marksheet-wizard', compact('courses'));
    }

    // AJAX: Get batches for a course
    public function getBatchesForCourse($courseId)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'examcell'])) {
            abort(403);
        }
        $batches = \App\Models\CourseDetail::where('course_id', $courseId)->orderBy('batch_no')->get();
        return response()->json($batches);
    }

    // AJAX: Get candidates for a batch
    public function getCandidatesForBatch($batchId)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'examcell'])) {
            abort(403);
        }
        $candidates = \App\Models\Candidates::where('course_detail_id', $batchId)->orderBy('name')->get();
        return response()->json($candidates);
    }

    // Generate and download/save marksheet PDF for a candidate
    public function generateEtoMarksheet($candidateId)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'examcell'])) {
            abort(403);
        }
        $candidate = \App\Models\Candidates::with(['courseDetail.course'])->findOrFail($candidateId);
        $course = $candidate->courseDetail->course;
        $subjects = \App\Models\Subject::where('course_id', $course->id)->get();
        // Only approved marks
        $marks = [];
        $subjectData = [];
        $total = 0;
        $obtained = 0;
        $courseName = strtolower($course->name);
        $isGme = strpos($courseName, 'gme') !== false;
        foreach ($subjects as $i => $subject) {
            if ($isGme) {
                $markTerm1 = \App\Models\Marks::where('candidate_id', $candidate->id)
                    ->where('subject_id', $subject->id)
                    ->where('term', 1)
                    ->where('status', 'approved')
                    ->first();
                $markTerm2 = \App\Models\Marks::where('candidate_id', $candidate->id)
                    ->where('subject_id', $subject->id)
                    ->where('term', 2)
                    ->where('status', 'approved')
                    ->first();
                $marksObtained = ($markTerm1->marks_obtained ?? 0) + ($markTerm2->marks_obtained ?? 0);
                $marks[$i] = (object)[
                    'marks_obtained' => $marksObtained,
                    'term1' => $markTerm1->marks_obtained ?? 0,
                    'term2' => $markTerm2->marks_obtained ?? 0
                ];
            } else {
                $mark = \App\Models\Marks::where('candidate_id', $candidate->id)
                    ->where('subject_id', $subject->id)
                    ->where(function($q) {
                        $q->whereNull('term')->orWhere('term', 0);
                    })
                    ->where('status', 'approved')
                    ->first();
                $marks[$i] = $mark;
            }
            $subjectData[] = [
                $subject->subject_code, // Add subject code as first element
                $subject->name,
                $subject->max_marks,
                $subject->passing_marks
            ];
            $total += $subject->max_marks;
            $obtained += $marks[$i]->marks_obtained ?? 0;
        }
        $percentage = $total ? round(($obtained / $total) * 100, 2) : 0;
        $result = ($percentage >= 60) ? 'PASS' : 'FAIL';
        // Choose template based on course
        if (strpos($courseName, 'gme') !== false) {
            $viewName = 'pdf.marksheets.gme';
        } else {
            $viewName = 'pdf.marksheets.electro_technical_officer_eto';
        }
        // Render Blade view to HTML
        $pdfHtml = view($viewName, [
            'candidate' => $candidate,
            'subjects' => $subjectData,
            'marks' => $marks,
            'total' => $total,
            'obtained' => $obtained,
            'percentage' => $percentage,
            'result' => $result
        ])->render();
        // Generate PDF with background
        $pdf = Pdf::loadHTML($pdfHtml);
        $pdf->setPaper('A4', 'portrait');
        // Set background using DomPDF CSS in the Blade view
        // Save to disk
        $filename = 'marksheet_' . $candidate->roll_no . '.pdf';
        $savePath = public_path('marksheets/' . $filename);
        if (!file_exists(public_path('marksheets'))) {
            mkdir(public_path('marksheets'), 0777, true);
        }
        file_put_contents($savePath, $pdf->output());
        // Save marksheet path to candidate
        $candidate->marksheet_path = 'marksheets/' . $filename;
        $candidate->save();
        // Download response
        return $pdf->download($filename);
    }

    public function generateMultipleMarksheets(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'examcell'])) {
            abort(403);
        }
        $candidateIds = $request->input('candidate_ids', []);
        if (empty($candidateIds)) {
            return back()->with('error', 'No candidates selected.');
        }
        $pdfPaths = [];
        $publicDir = public_path('marksheets');
        if (!file_exists($publicDir)) {
            mkdir($publicDir, 0777, true);
        }
        $incompleteCandidates = [];
        $skippedCandidates = [];
        foreach ($candidateIds as $candidateId) {
            $candidate = \App\Models\Candidates::with(['courseDetail.course'])->find($candidateId);
            if (!$candidate) continue;
            $course = $candidate->courseDetail->course;
            $subjects = \App\Models\Subject::where('course_id', $course->id)->get();
            $marks = [];
            $allApproved = true;
            $subjectData = [];
            $total = 0;
            $obtained = 0;
            $courseName = strtolower($course->name);
            $isGme = strpos($courseName, 'gme') !== false;
            foreach ($subjects as $i => $subject) {
                if ($isGme) {
                    $markTerm1 = \App\Models\Marks::where('candidate_id', $candidate->id)
                        ->where('subject_id', $subject->id)
                        ->where('term', 1)
                        ->where('status', 'approved')
                        ->first();
                    $markTerm2 = \App\Models\Marks::where('candidate_id', $candidate->id)
                        ->where('subject_id', $subject->id)
                        ->where('term', 2)
                        ->where('status', 'approved')
                        ->first();
                    $marksObtained = ($markTerm1->marks_obtained ?? 0) + ($markTerm2->marks_obtained ?? 0);
                    $marks[$i] = (object)[
                        'marks_obtained' => $marksObtained,
                        'term1' => $markTerm1->marks_obtained ?? 0,
                        'term2' => $markTerm2->marks_obtained ?? 0
                    ];
                } else {
                    $mark = \App\Models\Marks::where('candidate_id', $candidate->id)
                        ->where('subject_id', $subject->id)
                        ->where(function($q) {
                            $q->whereNull('term')->orWhere('term', 0);
                        })
                        ->where('status', 'approved')
                        ->first();
                    $marks[$i] = $mark;
                }
                $subjectData[] = [
                    $subject->subject_code, // Add subject code as first element
                    $subject->name,
                    $subject->max_marks,
                    $subject->passing_marks
                ];
                $total += $subject->max_marks;
                $obtained += $marks[$i]->marks_obtained ?? 0;
            }
            if (!$allApproved) {
                $incompleteCandidates[] = $candidate->name . ' (' . $candidate->roll_no . ')';
                continue;
            }
            $filename = 'marksheet_' . $candidate->roll_no . '.pdf';
            $pdfPath = $publicDir . '/' . $filename;
            if (file_exists($pdfPath)) {
                $skippedCandidates[] = $candidate->name . ' (' . $candidate->roll_no . ')';
                $pdfPaths[] = $pdfPath; // Still add to ZIP
                continue;
            }
            $percentage = $total ? round(($obtained / $total) * 100, 2) : 0;
            $result = ($percentage >= 60) ? 'PASS' : 'FAIL';
            // Choose template based on course
            if (strpos($courseName, 'gme') !== false) {
                $viewName = 'pdf.marksheets.gme';
            } else {
                $viewName = 'pdf.marksheets.electro_technical_officer_eto';
            }
            $pdfHtml = view($viewName, [
                'candidate' => $candidate,
                'subjects' => $subjectData,
                'marks' => $marks,
                'total' => $total,
                'obtained' => $obtained,
                'percentage' => $percentage,
                'result' => $result
            ])->render();
            $pdf = Pdf::loadHTML($pdfHtml);
            $pdf->setPaper('A4', 'portrait');
            file_put_contents($pdfPath, $pdf->output());
            // Save marksheet path to candidate
            $candidate->marksheet_path = 'marksheets/' . $filename;
            $candidate->save();
            $pdfPaths[] = $pdfPath;
        }
        if (!empty($incompleteCandidates)) {
            return back()->with('error', 'Marksheet cannot be generated for the following candidates as not all subject marks are approved: ' . implode(', ', $incompleteCandidates));
        }
        // Create temporary ZIP for download
        $tempDir = storage_path('app/marksheet_zip_' . uniqid());
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        $zipPath = $tempDir . '/marksheets_' . date('Ymd_His') . '.zip';
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
            foreach ($pdfPaths as $pdfFile) {
                $zip->addFile($pdfFile, basename($pdfFile));
            }
            $zip->close();
        }
        // Prepare skipped message
        $headers = [];
        if (!empty($skippedCandidates)) {
            $headers['X-Skipped-Candidates'] = 'Marksheet already exists for: ' . implode(', ', $skippedCandidates);
        }
        // Download ZIP and delete after send
        $response = response()->download($zipPath)->deleteFileAfterSend(true);
        if (!empty($skippedCandidates)) {
            $response->headers->set('X-Skipped-Candidates', 'Marksheet already exists for: ' . implode(', ', $skippedCandidates));
        }
        return $response;
    }
}