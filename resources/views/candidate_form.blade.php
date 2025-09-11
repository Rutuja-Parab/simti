<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/logo.png" type="image/x-icon">
    <title>@yield('title', 'SIMTI')</title>
    <title>Candidate Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }

        .card {
            border-radius: 12px;
        }

        .img-thumbnail {
            max-height: 200px;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <!-- Logo + Heading -->
        <div class="row mb-4">
            <div class="col-lg-10 mx-auto d-flex flex-column flex-md-row align-items-center gap-3">
                <img src="/logo.png" alt="Logo" height="80">
                <h1 class="fw-bold mb-0 text-center text-md-start" style="color: #3881c3;">
                    Welcome to Seven Islands Maritime Training Institute
                </h1>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                ✅ {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                ❌ {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Form -->
        @if(!session('success'))
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <form id="candidateForm" method="POST" enctype="multipart/form-data"
                        action="{{ route('candidate.store') }}">
                        @csrf
                        <div class="card shadow-sm mb-4">
                            <div class="card-header text-white" style="background-color: #3881c3;">
                                <h5 class="mb-0">Candidate Form</h5>
                            </div>
                            <div class="card-body">
                                {{-- Candidate Info --}}
                                <div class="row">
                                    <input type="hidden" name="course_detail_id" value="{{ $courseDetail->id }}">
                                    <div class="col-md-6 mb-3">
                                        <label for="roll_no" class="form-label">Roll Number</label>
                                        <input type="text" class="form-control" name="roll_no" id="roll_no">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">Full Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" id="name" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="dob" class="form-label">Date of Birth <span
                                                class="text-danger">*</span></label>
                                        <input type="date" class="form-control" name="dob" id="dob" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="indos_no" class="form-label">INDOS Number <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="indos_no" id="indos_no" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="passport_no" class="form-label">Passport Number <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="passport_no" id="passport_no"
                                            pattern="^[A-Za-z0-9]{9}$"
                                            title="Please enter a valid passport number (9 letters/numbers)" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="cdc_no" class="form-label">CDC Number</label>
                                        <input type="text" class="form-control" name="cdc_no" id="cdc_no">
                                    </div>
                                </div>

                                {{-- Course --}}
                                <div class="mb-3">
                                    <label class="form-label">Course & Batch</label>
                                    <input type="text" class="form-control"
                                        value="{{ $courseDetail->course->name }} - Batch {{ $courseDetail->batch_no }}"
                                        readonly>
                                </div>

                                {{-- Document Uploads --}}
                                @foreach (['photo' => 'Passport Photo', 'signature' => 'Signature', 'passport' => 'Passport Document'] as $type => $label)
                                    <div class="mb-4">
                                        <label class="form-label">{{ $label }} <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control file-input" name="{{ $type }}"
                                            data-type="{{ $type }}" required accept=".jpg,.jpeg,.png,.pdf">
                                        <div class="spinner-border text-primary mt-2 d-none loader" role="status"></div>
                                        <div class="mt-2 validation-result" data-type="{{ $type }}"></div>
                                        <div class="mt-3 preview-box" data-type="{{ $type }}"></div>
                                    </div>
                                @endforeach

                                {{-- Submit --}}
                                <div class="mt-4">
                                    <button type="submit" class="btn btn-success w-100" id="finalSubmitBtn" disabled>Submit
                                        All Documents</button>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const alertBox = document.querySelector('.alert');
            if (alertBox) {
                alertBox.scrollIntoView({ behavior: 'smooth' });
            }
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = document.querySelectorAll('.file-input');
            const submitBtn = document.getElementById('finalSubmitBtn');
            const validationStatus = {};

            inputs.forEach(input => {
                input.addEventListener('change', function () {
                    const type = input.dataset.type;
                    const file = input.files[0];
                    const loader = input.parentElement.querySelector('.loader');
                    const resultBox = input.parentElement.querySelector('.validation-result');
                    const previewBox = input.parentElement.querySelector('.preview-box');

                    if (!file) return;

                    loader.classList.remove('d-none');
                    resultBox.innerHTML = '';
                    previewBox.innerHTML = '';

                    // Preview
                    const fileType = file.type;

                    if (fileType.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            previewBox.innerHTML = `<img src="${e.target.result}" class="img-thumbnail">`;
                        };
                        reader.readAsDataURL(file);

                    } else if (fileType === 'application/pdf') {
                        // Create a blob URL for PDF
                        const pdfUrl = URL.createObjectURL(file);
                        previewBox.innerHTML = `
        <iframe src="${pdfUrl}" type="application/pdf" width="100%" height="500px" class="border rounded"></iframe>
        <div class="small text-muted mt-1"><i class="bi bi-file-earmark-pdf"></i> ${file.name}</div>
    `;
                    } else {
                        previewBox.innerHTML = `<div class="text-muted">Preview not available</div>`;
                    }

                    // Upload
                    const formData = new FormData();
                    formData.append('photo', file);
                    formData.append('type', type);
                    formData.append('_token', '{{ csrf_token() }}');

                    fetch('{{ route('candidate.upload') }}', {
                        method: 'POST',
                        body: formData,
                        credentials: 'same-origin'
                    })
                        .then(response => response.json())
                        .then(data => {
                            loader.classList.add('d-none');

                            // Display alert dynamically
                            if (data.status && data.status === 'Perfect') {
                                resultBox.innerHTML = `<div class="alert alert-success d-flex align-items-center" role="alert">
                        <strong class="me-2">✅ Status:</strong> Perfect
                    </div>`;
                                validationStatus[type] = true;
                            } else if (data.errors && data.errors.length > 0) {
                                resultBox.innerHTML = `<div class="alert alert-danger">❌ ${data.errors.join('<br>')}</div>`;
                                validationStatus[type] = false;
                            } else if (data.error) {
                                resultBox.innerHTML = `<div class="alert alert-warning">⚠️ ${data.error}</div>`;
                                validationStatus[type] = false;
                            } else {
                                resultBox.innerHTML = `<div class="alert alert-warning">⚠️ Unknown validation result.</div>`;
                                validationStatus[type] = false;
                            }

                            checkAllValidated();
                        })
                        .catch(() => {
                            loader.classList.add('d-none');
                            resultBox.innerHTML = '<div class="text-danger">Validation failed.</div>';
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>