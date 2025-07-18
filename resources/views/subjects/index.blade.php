@extends('layout')

@section('title', 'Subjects by Course')

@section('content')
    <div class="container">
        <h4 class="mb-4"><i class="mdi mdi-book-open-variant-outline me-2"></i>Subjects</h4>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- Add New Subject --}}
        <form method="POST" action="{{ route('subjects.store') }}" class="row g-2 mb-4 align-items-end">
            @csrf
            <div class="col-md-3">
                <label class="form-label">Course</label>
                <select name="course_id" class="form-select" required>
                    <option value="">-- Select Course --</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Subject Code</label>
                <input type="text" name="subject_code" class="form-control" required>
            </div>

            <div class="col-md-3">
                <label class="form-label">Subject Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="col-md-1">
                <label class="form-label">Max Marks</label>
                <input type="number" name="max_marks" class="form-control" min="1" value="100" required>
            </div>

            <div class="col-md-1">
                <label class="form-label text-nowrap small">Cut-off Marks</label>
                <input type="number" name="passing_marks" class="form-control" min="0" value="50" required>
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-50"><i class="bi bi-plus-circle me-1"></i> Add</button>
            </div>
        </form>


        {{-- Display Subjects Grouped by Course --}}
        @foreach ($courses as $course)
            @php
                $hasSubjectCode = $course->subjects->contains(function ($s) {
                    return !empty($s->subject_code);
                });
            @endphp
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <strong>{{ $course->name }}</strong>
                </div>
                <div class="card-body p-0">
                    @if ($course->subjects->isEmpty())
                        <div class="p-3">No subjects added for this course.</div>
                    @else
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    @if ($hasSubjectCode)
                                        <th>Subject Code</th>
                                    @endif
                                    <th>Name</th>
                                    <th>Max</th>
                                    <th>Pass</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($course->subjects as $index => $subject)
                                    <tr>
                                        <form method="POST" action="{{ route('subjects.update', $subject->id) }}"
                                            class="d-flex gap-2">
                                            @csrf
                                            <td>{{ $index + 1 }}</td>

                                            @if ($hasSubjectCode)
                                                <td>
                                                    <input type="text" name="subject_code"
                                                        class="form-control form-control-sm"
                                                        value="{{ $subject->subject_code }}">
                                                </td>
                                            @endif

                                            <td><input type="text" name="name" class="form-control form-control-sm"
                                                    value="{{ $subject->name }}" required></td>
                                            <td><input type="number" name="max_marks" class="form-control form-control-sm"
                                                    value="{{ $subject->max_marks }}" required></td>
                                            <td><input type="number" name="passing_marks"
                                                    class="form-control form-control-sm"
                                                    value="{{ $subject->passing_marks }}" required></td>

                                            <td class="d-flex gap-1">
                                                <button class="btn btn-sm btn-success"><i
                                                        class="bi bi-check-lg"></i></button>
                                        </form>
                                        <form method="POST" action="{{ route('subjects.destroy', $subject->id) }}"
                                            onsubmit="return confirm('Delete this subject?')">
                                            @csrf
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const maxMarksInput = document.querySelector('input[name="max_marks"]');
            const passingMarksInput = document.querySelector('input[name="passing_marks"]');

            maxMarksInput.addEventListener('input', function () {
                const max = parseFloat(maxMarksInput.value);
                if (!isNaN(max)) {
                    passingMarksInput.value = Math.floor(max / 2);
                }
            });
        });
    </script>
@endsection
