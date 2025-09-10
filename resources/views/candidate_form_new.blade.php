@extends('layout') {{-- You can change this to a different layout or remove it --}}

@section('title', 'Candidate Form')

@section('content')
    <div class="container">
        <h1>Candidate Form</h1>

        <form action="{{ route('candidate.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="course_detail_id" value="{{ $courseDetail->id }}">
            <input type="hidden" name="course_name" value="{{ $courseDetail->course->name }}">
            <input type="hidden" name="batch" value="{{ $courseDetail->batch_no }}">

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="indos_no" class="form-label">INDOS Number</label>
                        <input type="text" class="form-control" id="indos_no" name="indos_no" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="passport_no" class="form-label">Passport Number</label>
                        <input type="text" class="form-control" id="passport_no" name="passport_no" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="cdc_no" class="form-label">CDC Number</label>
                        <input type="text" class="form-control" id="cdc_no" name="cdc_no">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="dgs_certificate_no" class="form-label">DGS Certificate Number</label>
                        <input type="text" class="form-control" id="dgs_certificate_no" name="dgs_certificate_no" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="photo" class="form-label">Passport Photo</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="signature" class="form-label">Signature</label>
                        <input type="file" class="form-control" id="signature" name="signature" accept="image/*" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="passport" class="form-label">Passport Document</label>
                        <input type="file" class="form-control" id="passport" name="passport" accept=".pdf, image/*"
                            required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
