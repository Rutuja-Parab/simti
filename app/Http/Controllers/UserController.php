<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $courses = Course::all();
        $subjects = Subject::all();
        return view('users.create', compact('courses', 'subjects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => ['required', Rule::in(['admin', 'faculty', 'examcell'])],
            // 'course_id' => 'required_if:role,faculty|nullable|exists:courses,id',
            'subject_ids' => 'required_if:role,faculty|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        DB::beginTransaction();

        try {
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            // If faculty, assign course and subjects
            if ($request->role === 'faculty') {
                // Assuming your users table has a course_id field (optional)
                // $user->course_id = $request->course_id;
                $user->save();

                // Attach subjects via pivot (many-to-many)
                $user->subjects()->sync($request->subject_ids); // assuming user->subjects() relation
            }

            DB::commit();

            return redirect()->route('users.index')->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error creating user: ' . $e->getMessage()]);
        }
    }
    public function edit(User $user)
    {
        $courses = Course::all(); // if needed
        $subjects = Subject::all(); // if needed
        return view('users.edit', compact('user', 'courses', 'subjects'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
           'role' => ['required', Rule::in(['admin', 'faculty', 'examcell'])],
            'subject_ids' => 'required_if:role,faculty|array',
            'subject_ids.*' => 'exists:subjects,id',
        ]);

        $user->update($request->only(['name', 'email', 'role']));

        if ($request->role === 'faculty') {
            $user->subjects()->sync($request->subject_ids ?? []);
        } else {
            $user->subjects()->detach(); // remove any attached subjects for non-facultys
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
}
