@extends('layout')

@section('title', 'Candidates List')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>

                <h4 class="mb-0"><span class="mdi mdi-account-tie-hat-outline"></span> Registered Candidates</h4>
            </div>
            <div>

                @if (auth()->user()->role !== 'faculty')
                    <button type="button" class="btn btn-sm btn-outline-secondary me-2" data-bs-toggle="modal"
                        data-bs-target="#importModal">
                        Upload CSV
                    </button>
                    <a href="{{ route('candidates.export') }}" class="btn btn-sm btn-outline-success">Export CSV</a>
                    <a href="{{ route('candidate.add') }}" class="btn btn-sm btn-primary">
                        <i class="bi bi-plus-circle me-1"></i> Add Candidate
                    </a>
                @endif
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($candidate->isEmpty())
            <div class="alert alert-info">No candidates found.</div>
        @else
            <div class="table-responsive">
                <table id="candidatesTable" class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Roll No</th>
                            <th>Name</th>
                            <th>DOB</th>
                            <th>Course</th>
                            <th>Batch No</th>
                            @if (auth()->user()->role !== 'faculty')
                                <th>INDOS</th>
                                <th>Passport</th>
                                <th>CDC</th>
                                <th>Documents</th>
                            @endif
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($candidate as $index => $c)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $c->roll_no }}</td>
                                <td>{{ $c->name }}</td>
                                <td>{{ $c->dob->format('d-m-Y') }}</td>
                                <td>{{ $c->courseDetail ? $c->courseDetail->course->name : '-' }}</td>
                                <td>{{ $c->courseDetail ? $c->courseDetail->batch_no : '-' }}</td>
                                @if (auth()->user()->role !== 'faculty')
                                    <td>{{ $c->indos_no }}</td>
                                    <td>{{ $c->passport_no }}</td>
                                    <td>{{ $c->cdc_no }}</td>
                                    <td>
                                        <a href="{{ asset('photos/' . basename($c->photo_path)) }}" target="_blank"
                                            class="btn btn-sm btn-outline-primary mb-1">Photo</a>
                                        <a href="{{ asset('photos/' . basename($c->signature_path)) }}" target="_blank"
                                            class="btn btn-sm btn-outline-success mb-1">Signature</a>
                                        <a href="{{ asset('photos/' . basename($c->passport_path)) }}" target="_blank"
                                            class="btn btn-sm btn-outline-warning mb-1">Passport</a>
                                        @if($c->marksheet_path)
                                            <a href="{{ asset($c->marksheet_path) }}" target="_blank" class="btn btn-sm btn-outline-info mb-1">Marksheet</a>
                                        @endif
                                        @if($c->certificate_path)
                                            <a href="{{ asset($c->certificate_path) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-1">Certificate</a>
                                        @endif
                                    </td>
                                @endif
                                <td class="d-flex">
                                    <form method="GET" action="{{ route('marks.create', $c->id) }}"
                                        class="d-flex align-items-center">
                                        @csrf
                                        <button class="btn btn-sm btn-success me-1" title="Edit"><i
                                                class="bi bi-pen"></i></button>
                                    </form>
                                    @if (auth()->user()->role !== 'faculty')
                                        <form method="POST" action="{{ route('candidates.destroy', $c->id) }}"
                                            onsubmit="return confirm('Delete this candidate?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger" title="Delete">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        @endif
    </div>
    <!-- Import CSV Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('candidates.import') }}" method="POST" enctype="multipart/form-data"
                class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Candidates from CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>
                        <strong>CSV Template:</strong>
                        <a href="{{ asset('sample/candidate_template.csv') }}" class="btn btn-sm btn-link"
                            download>Download Template</a>
                    </p>

                    <div class="mb-3">
                        <label for="csv_file" class="form-label">Upload CSV File</label>
                        <input class="form-control" type="file" name="csv_file" id="csv_file" accept=".csv" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Import</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function () {
        $('#candidatesTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: -1 }, // Disable sorting on actions
                @if (auth()->user()->role !== 'faculty')
                    { orderable: false, targets: [8] } // Disable sorting on documents column
                @endif
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search candidates..."
            }
        });
    });
    </script>
@endsection
