<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Candidates;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Marks;

class Controller extends \Illuminate\Routing\Controller
{
    public function adminDashboard()
    {
        $userCount = User::count();
        $candidateCount = Candidates::count();
        $courseCount = Course::count();
        $subjectCount = Subject::count();
        $marksCount = Marks::count();
        $recentCandidates = Candidates::orderBy('created_at', 'desc')->take(5)->get();
        $recentMarks = Marks::orderBy('created_at', 'desc')->take(5)->get();

        return view('adminDashboard', compact(
            'userCount',
            'candidateCount',
            'courseCount',
            'subjectCount',
            'marksCount',
            'recentCandidates',
            'recentMarks'
        ));
    }
}
