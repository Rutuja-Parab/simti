@extends('layout')

@section('title', 'Courses Master')

@section('content')
<div class="container">
    <h4 class="mb-4"><i class="mdi mdi-book-multiple-outline me-2"></i>Courses</h4>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

    {{-- Add Course Form --}}
    <form method="POST" action="{{ route('courses.store') }}" class="row g-3 mb-4">
        @csrf
        <div class="col-md-6">
            <input type="text" name="name" class="form-control" placeholder="New course name" required>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Add</button>
        </div>
    </form>

    {{-- Course Table --}}
    <table class="table table-bordered table-striped" id="coursesTable">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Course Name</th>
                <th style="width: 180px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courses as $index => $course)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <form method="POST" action="{{ route('courses.update', $course->id) }}" class="d-flex align-items-center">
                            @csrf
                            <input type="text" name="name" class="form-control form-control-sm me-2"
                                   value="{{ $course->name }}" required>
                            <button class="btn btn-sm btn-success me-1" title="Save"><i class="bi bi-check-lg"></i></button>
                        </form>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('courses.destroy', $course->id) }}"
                              onsubmit="return confirm('Delete this course?');">
                            @csrf
                            <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash3-fill"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#coursesTable').DataTable();
    });
</script>
@endsection
