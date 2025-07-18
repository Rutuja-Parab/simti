@extends('layout')

@section('title', 'Edit Marks for ' . $candidate->name)

@section('content')
    <div class="container py-4">
        <h4 class="mb-4">
            <i class="mdi mdi-account-edit-outline me-2"></i> Edit Marks - {{ $candidate->name }}
        </h4>

        <form method="POST" action="{{ route('marks.store', $candidate->id) }}">
            @csrf

            {{-- Tabs only for admin/manager --}}
            @if (auth()->user()->role !== 'faculty')
                <ul class="nav nav-tabs mb-3" id="editCandidateTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active bg-primary text-white" id="details-tab" data-bs-toggle="tab"
                            data-bs-target="#details" type="button" role="tab" aria-controls="details"
                            aria-selected="true">
                            Candidate Details
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="marks-tab" data-bs-toggle="tab" data-bs-target="#marks" type="button"
                            role="tab" aria-controls="marks" aria-selected="false">
                            Add Marks
                        </button>
                    </li>
                </ul>
            @endif

            <div class="tab-content border rounded p-4 bg-white shadow-sm" id="editTabsContent">

                {{-- Candidate Details Tab (Only for non-facultys) --}}
                @if (auth()->user()->role !== 'faculty')
                    <div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="details-tab">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="roll_no" class="form-label">Roll Number</label>
                                <input type="text" class="form-control" id="roll_no" value="{{ $candidate->roll_no }}" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Name</label><span class="text-danger">*</span>
                                <input type="text" name="name" class="form-control" value="{{ $candidate->name }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date of Birth</label><span class="text-danger">*</span>
                                <input type="date" name="dob" class="form-control" value="{{ $candidate->dob->format('Y-m-d') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="course_detail_id" class="form-label">Course & Batch</label>
                                <select name="course_detail_id" id="course_detail_id" class="form-select" required>
                                    <option value="">Select Course & Batch</option>
                                    @foreach($courseDetails as $cd)
                                        <option value="{{ $cd->id }}" {{ $candidate->course_detail_id == $cd->id ? 'selected' : '' }}>{{ $cd->course->name }} - Batch {{ $cd->batch_no }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">INDOS No</label><span class="text-danger">*</span>
                                <input type="text" name="indos_no" class="form-control" value="{{ $candidate->indos_no }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Passport No</label><span class="text-danger">*</span>
                                <input type="text" name="passport_no" class="form-control" value="{{ $candidate->passport_no }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CDC No</label><span class="text-danger">*</span>
                                <input type="text" name="cdc_no" class="form-control" value="{{ $candidate->cdc_no }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">DGS Certificate No (Optional)</label>
                                <input type="text" name="dgs_certificate_no" class="form-control" value="{{ $candidate->dgs_certificate_no }}">
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="marks" role="tabpanel" aria-labelledby="marks-tab">
                @else
                    {{-- facultys only see Marks panel without tabs --}}
                    <div class="show active">
                @endif

                        @php
                            $isGme = false;
                            if ($candidate->courseDetail && $candidate->courseDetail->course) {
                                $isGme = stripos($candidate->courseDetail->course->name, 'gme') !== false;
                            }
                        @endphp
                        <div class="table-responsive">
                            <table class="table table-bordered mt-3">
                                <thead class="table-light">
                                    <tr>
                                        <th>Subject</th>
                                        <th>Max Marks</th>
                                        <th>Cut-off</th>
                                        @if($isGme)
                                            <th>Term-1</th>
                                            <th>Term-2</th>
                                        @else
                                            <th>Marks Obtained</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subjects as $subject)
                                        <tr>
                                            <td>{{ $subject->name }}</td>
                                            <td>{{ $subject->max_marks }}</td>
                                            <td>{{ $subject->passing_marks }}</td>
                                            @if($isGme)
                                                @php
                                                    $existingTerm1 = optional($candidate->marks->firstWhere(fn($m) => $m->subject_id == $subject->id && $m->term == 1))->marks_obtained;
                                                    $existingTerm2 = optional($candidate->marks->firstWhere(fn($m) => $m->subject_id == $subject->id && $m->term == 2))->marks_obtained;
                                                @endphp
                                                <td>
                                                    <input type="number" name="marks[{{ $subject->id }}][1]" class="form-control"
                                                        max="{{ $subject->max_marks }}" min="0"
                                                        value="{{ $existingTerm1 }}" placeholder="Term-1">
                                                </td>
                                                <td>
                                                    <input type="number" name="marks[{{ $subject->id }}][2]" class="form-control"
                                                        max="{{ $subject->max_marks }}" min="0"
                                                        value="{{ $existingTerm2 }}" placeholder="Term-2">
                                                </td>
                                            @else
                                                <td>
                                                    <input type="number" name="marks[{{ $subject->id }}]" class="form-control"
                                                        max="{{ $subject->max_marks }}" min="0"
                                                        value="{{ optional($candidate->marks->firstWhere('subject_id', $subject->id))->marks_obtained }}">
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="mt-4">
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-check-circle me-1"></i> Save All
                </button>
                <a href="{{ route('candidate.view') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navTabs = document.querySelector('#editCandidateTabs');
            if (!navTabs) return;

            const navLinks = navTabs.querySelectorAll('.nav-link');

            navLinks.forEach(link => {
                link.addEventListener('shown.bs.tab', () => {
                    navLinks.forEach(l => l.classList.remove('bg-primary', 'text-white'));
                    link.classList.add('bg-primary', 'text-white');
                });
            });
        });
    </script>
@endsection
