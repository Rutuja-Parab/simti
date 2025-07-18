<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::all();
        return view('courses.index', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:courses,name|string|max:255',
        ]);

        Course::create(['name' => $request->name]);
        return redirect()->route('courses.index')->with('success', 'Course added.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:courses,name,' . $id,
        ]);

        $course = Course::findOrFail($id);
        $course->name = $request->name;
        $course->save();

        return redirect()->route('courses.index')->with('success', 'Course updated.');
    }

    public function destroy($id)
    {
        Course::findOrFail($id)->delete();
        return redirect()->route('courses.index')->with('success', 'Course deleted.');
    }
}

