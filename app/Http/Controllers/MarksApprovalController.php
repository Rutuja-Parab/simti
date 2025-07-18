<?php

namespace App\Http\Controllers;

use App\Models\Marks;
use App\Models\CourseDetail;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class MarksApprovalController extends Controller
{
    // Show dashboard of pending marks grouped by course and batch
    public function index()
    {
        // Get all pending and rejected marks with related candidate, subject, courseDetail, course, and last_edited_by (faculty)
        $pendingMarks = Marks::with(['candidate.courseDetail.course', 'subject', 'candidate', 'lastEditor'])
            ->where('status', 'pending')
            ->get();
        $rejectedMarks = Marks::with(['candidate.courseDetail.course', 'subject', 'candidate', 'lastEditor'])
            ->where('status', 'rejected')
            ->get();

        // Group by course and batch
        $groupedPending = $pendingMarks->groupBy(function ($mark) {
            $course = $mark->candidate->courseDetail->course ?? null;
            $batch = $mark->candidate->courseDetail->batch_no ?? null;
            return $course ? $course->id . '-' . $batch : 'unknown';
        });
        $groupedRejected = $rejectedMarks->groupBy(function ($mark) {
            $course = $mark->candidate->courseDetail->course ?? null;
            $batch = $mark->candidate->courseDetail->batch_no ?? null;
            return $course ? $course->id . '-' . $batch : 'unknown';
        });

        // For displaying course and batch info
        $groupedInfo = [];
        foreach ($groupedPending as $key => $marks) {
            $first = $marks->first();
            $course = $first->candidate->courseDetail->course ?? null;
            $batch = $first->candidate->courseDetail->batch_no ?? null;
            $groupedInfo[$key] = [
                'course' => $course,
                'batch' => $batch,
                'pending_marks' => $marks,
                'rejected_marks' => $groupedRejected[$key] ?? collect([])
            ];
        }
        // Add any rejected groups not in pending
        foreach ($groupedRejected as $key => $marks) {
            if (!isset($groupedInfo[$key])) {
                $first = $marks->first();
                $course = $first->candidate->courseDetail->course ?? null;
                $batch = $first->candidate->courseDetail->batch_no ?? null;
                $groupedInfo[$key] = [
                    'course' => $course,
                    'batch' => $batch,
                    'pending_marks' => collect([]),
                    'rejected_marks' => $marks
                ];
            }
        }

        // Mark all notifications as read when visiting approvals page
        if (auth()->check()) {
            auth()->user()->unreadNotifications->markAsRead();
        }

        return view('marks.approvals', compact('groupedInfo'));
    }

    public function approveGroup($course_id, $batch)
    {
        $user = auth()->user();
        $marks = \App\Models\Marks::whereHas('candidate.courseDetail', function ($q) use ($course_id, $batch) {
            $q->where('course_id', $course_id)->where('batch_no', $batch);
        })->where('status', 'pending')->get();

        foreach ($marks as $mark) {
            $mark->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'rejection_reason' => null
            ]);
        }
        return redirect()->back()->with('success', 'All marks for this group approved.');
    }

    public function approveMark($markId)
    {
        $user = auth()->user();
        $mark = \App\Models\Marks::findOrFail($markId);
        $mark->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
            'rejection_reason' => null
        ]);
        return redirect()->back()->with('success', 'Mark approved.');
    }

    public function rejectMark(Request $request, $markId)
    {
        $request->validate(['rejection_reason' => 'required|string']);
        $mark = \App\Models\Marks::findOrFail($markId);
        $mark->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => null,
            'approved_at' => null
        ]);
        return redirect()->back()->with('success', 'Mark rejected.');
    }
} 