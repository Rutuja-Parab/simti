@extends('layout')
@section('title', 'Edit Course Detail')
@section('content')
<div class="container">
    <h4 class="mb-4">Edit Course Detail</h4>
    <form method="POST" action="{{ route('course-details.update', $courseDetail->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="course_id" class="form-label">Course</label>
            <select name="course_id" id="course_id" class="form-select" required>
                <option value="">Select Course</option>
                @foreach($courses as $course)
                    <option value="{{ $course->id }}" {{ $courseDetail->course_id == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="batch_no" class="form-label">Batch No</label>
            <input type="text" name="batch_no" id="batch_no" class="form-control" value="{{ $courseDetail->batch_no }}" required>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ $courseDetail->start_date }}" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ $courseDetail->end_date }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('course-details.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 