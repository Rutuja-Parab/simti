<?php

namespace App\Http\Controllers;

use App\Models\CourseDetail;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseDetailsController extends Controller
{
    public function index()
    {
        $courseDetails = CourseDetail::with('course')->get();
        return view('course_details.index', compact('courseDetails'));
    }

    public function create()
    {
        $courses = Course::all();
        return view('course_details.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'batch_no' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        CourseDetail::create($request->all());
        return redirect()->route('course-details.index')->with('success', 'Course detail added successfully.');
    }

    public function edit($id)
    {
        $courseDetail = CourseDetail::findOrFail($id);
        $courses = Course::all();
        return view('course_details.edit', compact('courseDetail', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'batch_no' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        $courseDetail = CourseDetail::findOrFail($id);
        $courseDetail->update($request->all());
        return redirect()->route('course-details.index')->with('success', 'Course detail updated successfully.');
    }

    public function destroy(CourseDetail $courseDetail)
    {
        $courseDetail->delete();
        return redirect()->route('course-details.index')->with('success', 'Course detail deleted successfully.');
    }

    public function createCandidateForm($course_detail_id)
    {
        $candidateController = new CandidateController();
        return $candidateController->create($course_detail_id);
    }
} 