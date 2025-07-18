@extends('layout')
@section('title', 'Course Details')
@section('content')
<div class="container">
    <h4 class="mb-4">Course Details</h4>
    <a href="{{ route('course-details.create') }}" class="btn btn-primary mb-3">Add Course Detail</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Course</th>
                <th>Batch No</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($courseDetails as $detail)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->course->name }}</td>
                    <td>{{ $detail->batch_no }}</td>
                    <td>{{ $detail->start_date }}</td>
                    <td>{{ $detail->end_date }}</td>
                    <td>
                        <a href="{{ route('course-details.edit', $detail->id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('course-details.destroy', $detail->id) }}" method="POST" style="display:inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this course detail?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection 