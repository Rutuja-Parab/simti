@extends('layout')

@section('content')
    <div class="container">
        <h2>Edit User</h2>

        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Role</label>
                <select name="role" id="roleSelect" class="form-select" required>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="faculty" {{ $user->role === 'faculty' ? 'selected' : '' }}>Faculty</option>
                    <option value="examcell" {{ $user->role === 'examcell' ? 'selected' : '' }}>Exam Cell</option>
                </select>
            </div>

            <!-- Show subjects only if faculty -->
            <div id="facultyFields" class="{{ $user->role === 'faculty' ? '' : 'd-none' }}">
                <div class="mb-3">
                    <label>Subjects</label>
                    <div class="form-check">
                        @foreach ($subjects as $subject)
                            <div>
                                <input class="form-check-input" type="checkbox" name="subject_ids[]"
                                    value="{{ $subject->id }}" id="subject-{{ $subject->id }}"
                                    {{ $user->subjects->contains($subject->id) ? 'checked' : '' }}>
                                <label class="form-check-label"
                                    for="subject-{{ $subject->id }}">{{ $subject->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Update User</button>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
@section('scripts')
    <script>
        const roleSelect = document.getElementById('roleSelect');
        const facultyFields = document.getElementById('facultyFields');

        roleSelect.addEventListener('change', function() {
            facultyFields.classList.toggle('d-none', this.value !== 'faculty');
        });
    </script>
@endsection
