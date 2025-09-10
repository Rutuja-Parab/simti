@extends('layout')

@section('content')
    <div class="container">
        <h2>Add User</h2>

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="mb-3">
                <label>Name</label><span class="text-danger">*</span>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label><span class="text-danger">*</span>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Role</label><span class="text-danger">*</span>
                <select name="role" id="roleSelect" class="form-select" required>
                    <option value="" disabled>Select Role</option>
                    
                    <option value="admin">Admin</option>
                    <option value="faculty">Faculty</option>
                    <option value="examcell">Exam Cell</option>
                </select>
            </div>

            <!-- Show only when role = faculty -->
            <div id="facultyFields" class="d-none">
                {{-- <div class="mb-3">
                    <label>Course</label><span class="text-danger">*</span>
                    <select name="course_id" id="courseSelect" class="form-select">
                        <option value="">Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </select>
                </div> --}}

                <div class="mb-3">
                    <label>Subjects</label><span class="text-danger">*</span>

                    <div class="form-check">
                        @foreach ($subjects as $subject)
                            <div>
                                <input class="form-check-input" type="checkbox" name="subject_ids[]" value="{{ $subject->id }}">
                                <label class="form-check-label">{{ $subject->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="mb-3">
                <label>Password</label><span class="text-danger">*</span>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Confirm Password</label><span class="text-danger">*</span>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Create User</button>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    document.getElementById('roleSelect').addEventListener('change', function () {
        const facultyFields = document.getElementById('facultyFields');
        facultyFields.classList.toggle('d-none', this.value !== 'faculty');
    });
</script>
@endsection
