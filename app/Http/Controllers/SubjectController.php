<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\Course;

class SubjectController extends Controller
{
    public function index()
    {
        $courses = Course::with('subjects')->get();
        return view('subjects.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'subject_code' => 'required|string|max:50|unique:subjects,subject_code',
            'name' => 'required|string|max:255',
            'max_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|lte:max_marks'
        ]);

        Subject::create($request->only('course_id', 'subject_code', 'name', 'max_marks', 'passing_marks'));
        return redirect()->route('subjects.index')->with('success', 'Subject added successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subject_code' => 'required|string|max:50|unique:subjects,subject_code,',
            'max_marks' => 'required|integer|min:1',
            'passing_marks' => 'required|integer|min:0|lte:max_marks'
        ]);


        $subject = Subject::findOrFail($id);
        $subject->update($request->only('subject_code', 'name', 'max_marks', 'passing_marks'));
        return redirect()->route('subjects.index')->with('success', 'Subject updated.');
    }

    public function destroy($id)
    {
        Subject::findOrFail($id)->delete();
        return redirect()->route('subjects.index')->with('success', 'Subject deleted.');
    }
}
