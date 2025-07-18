@extends('layout')

@section('title', 'Candidate Document Upload')

@section('content')

    <body>
        <div class="container py-5">
            <a href="{{ route('candidate.view') }}" class="btn btn-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left-circle me-1"></i> Go Back
            </a>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <form id="fullValidationForm" method="POST" enctype="multipart/form-data"
                        action="{{ route('final.submit') }}">
                        @csrf

                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Candidate Document Upload Form</h5>
                            </div>
                            <div class="card-body">
                                {{-- Candidate Info --}}
                                <div class="mb-3">
                                    <label for="roll_no" class="form-label">Roll Number (For Example : 01)</label>
                                    <input type="text" class="form-control" id="roll_no">
                                </div>

                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label><span
                                        class="text-danger">*</span>
                                    <input type="text" class="form-control" name="name" id="name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="dob" class="form-label">Date of Birth</label><span
                                        class="text-danger">*</span>
                                    <input type="date" class="form-control" name="dob" id="dob" required>
                                </div>

                                <div class="mb-3">
                                    <label for="indos_no" class="form-label">INDOS Number</label><span
                                        class="text-danger">*</span>
                                    <input type="text" class="form-control" name="indos_no" id="indos_no" required>
                                </div>

                                <div class="mb-3">
                                    <label for="passport_no" class="form-label">Passport Number</label><span
                                        class="text-danger">*</span>
                                    <input type="text" class="form-control" name="passport_no" id="passport_no" required>
                                </div>

                                <div class="mb-3">
                                    <label for="cdc_no" class="form-label">CDC Number</label><span
                                        class="text-danger">*</span>
                                    <input type="text" class="form-control" name="cdc_no" id="cdc_no" required>
                                </div>

                                <div class="mb-3">
                                    <label for="dgs_certificate_no" class="form-label">DGS Certificate Number</label>
                                    <input type="text" class="form-control" name="dgs_certificate_no"
                                        id="dgs_certificate_no">
                                </div>

                                {{-- Course Dropdown --}}
                                <div class="mb-3">
                                    <label for="course_detail_id" class="form-label">Course & Batch</label>
                                    <select name="course_detail_id" id="course_detail_id" class="form-select" required>
                                        <option value="">Select Course & Batch</option>
                                        @foreach($courseDetails as $cd)
                                            <option value="{{ $cd->id }}">{{ $cd->course->name }} - Batch {{ $cd->batch_no }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Document Uploads --}}
                                @foreach (['photo' => 'Passport Photo', 'signature' => 'Signature', 'passport' => 'Passport Document'] as $type => $label)
                                    <div class="mb-4">
                                        <label class="form-label">{{ $label }}</label><span
                                            class="text-danger">*</span>
                                        <input type="file" class="form-control file-input" name="{{ $type }}"
                                            data-type="{{ $type }}" required accept=".jpg,.jpeg,.png,.pdf">

                                        {{-- Loader --}}
                                        <div class="spinner-border text-primary mt-2 d-none loader" role="status"></div>

                                        {{-- Validation result --}}
                                        <div class="mt-2 validation-result" data-type="{{ $type }}"></div>

                                        {{-- Preview box --}}
                                        <div class="mt-3 preview-box" data-type="{{ $type }}"></div>
                                    </div>
                                @endforeach

                                {{-- Final Submit --}}
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success w-100" id="finalSubmitBtn" disabled>Submit
                                        All Documents</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    {{-- Validation Result Display --}}
                    @if (isset($result['errors']) && count($result['errors']) > 0)
                        <div class="alert alert-danger">
                            <strong class="d-block mb-2">Errors:</strong>
                            <ul class="mb-0">
                                @foreach ($result['errors'] as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @elseif(isset($result['status']) && $result['status'] === 'Perfect')
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <strong class="me-2">✅ Status:</strong> {{ $result['status'] }}
                        </div>
                    @elseif(isset($result['error']))
                        <div class="alert alert-warning">
                            <strong>Error:</strong> {{ $result['error'] }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Script --}}
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const inputs = document.querySelectorAll('.file-input');
                const submitBtn = document.getElementById('finalSubmitBtn');
                const validationStatus = {};

                inputs.forEach(input => {
                    input.addEventListener('change', function() {
                        const type = input.dataset.type;
                        const file = input.files[0];
                        const loader = input.parentElement.querySelector('.loader');
                        const resultBox = input.parentElement.querySelector('.validation-result');
                        const previewBox = input.parentElement.querySelector('.preview-box');

                        if (!file) return;

                        // Clear previous
                        loader.classList.remove('d-none');
                        resultBox.innerHTML = '';
                        previewBox.innerHTML = '';

                        // Show preview
                        const fileType = file.type;
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            if (fileType.startsWith('image/')) {
                                previewBox.innerHTML =
                                    `<img src="${e.target.result}" class="img-thumbnail" style="max-height: 200px;">`;
                            } else if (fileType === 'application/pdf') {
                                previewBox.innerHTML = `
        <iframe src="${e.target.result}" type="application/pdf" width="100%" height="300px" class="border rounded"></iframe>
        <div class="small text-muted mt-1"><i class="bi bi-file-earmark-pdf"></i> ${file.name}</div>
    `;
                            } else {
                                previewBox.innerHTML =
                                    `<div class="text-muted">Preview not available</div>`;
                            }
                        };

                        reader.readAsDataURL(file);

                        // Send to backend for validation
                        const formData = new FormData();
                        formData.append('photo', file);
                        formData.append('type', type);
                        formData.append('_token', '{{ csrf_token() }}');

                        fetch('{{ route('photo.upload') }}', {
                                method: 'POST',
                                body: formData
                            })
                            .then(response => response.text())
                            .then(html => {
                                loader.classList.add('d-none');
                                resultBox.innerHTML = html;

                                const isValid = !html.includes('error') && !html.includes('Error');
                                validationStatus[type] = isValid;
                                checkAllValidated();
                            })
                            .catch(() => {
                                loader.classList.add('d-none');
                                resultBox.innerHTML =
                                    '<div class="text-danger">Validation failed.</div>';
                                validationStatus[type] = false;
                            });
                    });
                });

                function checkAllValidated() {
                    const allValid = ['photo', 'signature', 'passport'].every(type => validationStatus[type]);
                    submitBtn.disabled = !allValid;
                }
            });
        </script>

    </body>
@endsection
