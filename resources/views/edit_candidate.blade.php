@extends('layout')
@section('title', 'Edit Candidate')
@section('content')
<div class="container">
    <h4 class="mb-4">Edit Candidate</h4>
    <form method="POST" action="{{ route('candidate.update', $candidate->id) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="roll_no" class="form-label">Roll Number</label>
            <input type="text" class="form-control" id="roll_no" value="{{ $candidate->roll_no }}" disabled>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $candidate->name }}" required>
        </div>
        <div class="mb-3">
            <label for="dob" class="form-label">Date of Birth</label>
            <input type="date" name="dob" id="dob" class="form-control" value="{{ $candidate->dob->format('Y-m-d') }}" required>
        </div>
        <div class="mb-3">
            <label for="indos_no" class="form-label">INDOS No</label>
            <input type="text" name="indos_no" id="indos_no" class="form-control" value="{{ $candidate->indos_no }}" required>
        </div>
        <div class="mb-3">
            <label for="passport_no" class="form-label">Passport No</label>
            <input type="text" name="passport_no" id="passport_no" class="form-control" value="{{ $candidate->passport_no }}" required>
        </div>
        <div class="mb-3">
            <label for="cdc_no" class="form-label">CDC No</label>
            <input type="text" name="cdc_no" id="cdc_no" class="form-control" value="{{ $candidate->cdc_no }}" >
        </div>
        <div class="mb-3">
            <label for="dgs_certificate_no" class="form-label">DGS Certificate No (Optional)</label>
            <input type="text" name="dgs_certificate_no" id="dgs_certificate_no" class="form-control" value="{{ $candidate->dgs_certificate_no }}">
        </div>
        <div class="mb-3">
            <label for="course_detail_id" class="form-label">Course & Batch</label>
            <select name="course_detail_id" id="course_detail_id" class="form-select" required>
                <option value="">Select Course & Batch</option>
                @foreach($courseDetails as $cd)
                    <option value="{{ $cd->id }}" {{ $candidate->course_detail_id == $cd->id ? 'selected' : '' }}>{{ $cd->course->name }} - Batch {{ $cd->batch_no }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('candidate') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection 