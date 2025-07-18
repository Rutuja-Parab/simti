@extends('layout')
@section('title', 'Add Course Detail')
@section('content')
<div class="container">
    <h4 class="mb-4">Add Course Detail</h4>
    <form method="POST" action="{{ route('course-details.store') }}">
        @csrf
        <div class="mb-3">
            <label for="course_id" class="form-label">Course</label>
            <select name="course_id" id="course_id" class="form-select" required>
                <option value="">Select Course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}">{{ $course->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="batch_no" class="form-label">Batch No</label>
            <input type="text" name="batch_no" id="batch_no" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
        <a href="{{ route('course-details.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 