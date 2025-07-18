@extends('layout')

@section('title', 'Select Course')

@section('content')
<div class="container py-4">
    <h4>Select Course to Add/Edit Marks</h4>
    <form method="GET" action="{{ url('course-marks') }}/" id="courseForm">
        <div class="row">
            <div class="col-md-6">
                <select name="course_id" class="form-select" required onchange="redirectToEdit(this)">
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>

</div>
<script>
    function redirectToEdit(select) {
        const courseId = select.value;
        if (courseId) {
            window.location.href = `/course-marks/${courseId}/edit`;
        }
    }
</script>
@endsection
