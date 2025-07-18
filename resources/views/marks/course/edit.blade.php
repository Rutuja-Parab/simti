@extends('layout')

@section('title', 'Enter Marks for ' . $course->name)

@section('content')
    <div class="container py-4">
        <h4><i class="bi bi-journal-text me-2"></i> Enter Marks – {{ $course->name }}</h4>

        @if(isset($courseDetails) && $courseDetails->count())
            <div class="mb-3">
                <label for="courseDetailSelect" class="form-label">Select Batch</label>
                <select id="courseDetailSelect" class="form-select">
                    <option value="">-- All Batches --</option>
                    @foreach($courseDetails as $cd)
                        <option value="batch-{{ $cd->id }}">{{ $cd->course->name }} - Batch {{ $cd->batch_no }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        @if ($course->subjects->isEmpty())
            <div class="alert alert-warning mt-3">No subjects available for you in this course.</div>
        @else
            <form id="marksForm" action="{{ route('course.marks.store', $course->id) }}" method="POST" style="display:none;">
                @csrf
                <div class="table-responsive mt-4">
                    @php $isGme = stripos($course->name, 'gme') !== false; @endphp
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            @if($isGme)
                                <tr>
                                    <th rowspan="2" style="vertical-align: middle;">Candidate</th>
                                    @foreach ($course->subjects as $subject)
                                        <th colspan="2" style="text-align: center;">{{ $subject->name }}<br><small>Max: {{ $subject->max_marks }}</small></th>
                                    @endforeach
                                </tr>
                                <tr>
                                    @foreach ($course->subjects as $subject)
                                        <th style="text-align: center;">Term-1</th>
                                        <th style="text-align: center;">Term-2</th>
                                    @endforeach
                                </tr>
                            @else
                                <tr>
                                    <th>Candidate</th>
                                    @foreach ($course->subjects as $subject)
                                        <th>{{ $subject->name }}<br><small>Max: {{ $subject->max_marks }}</small></th>
                                    @endforeach
                                </tr>
                            @endif
                        </thead>
                        <tbody>
                            @if(isset($courseDetails) && $courseDetails->count())
                                @foreach ($courseDetails as $courseDetail)
                                    @foreach ($courseDetail->candidates as $candidate)
                                        <tr class="batch-row batch-{{ $courseDetail->id }}" style="display:none;">
                                            <td>{{ $candidate->name }}</td>
                                            @foreach ($course->subjects as $subject)
                                                @php
                                                    $existing = optional($candidate->marks->firstWhere('subject_id', $subject->id))->marks_obtained;
                                                    $existingTerm1 = optional($candidate->marks->firstWhere(fn($m) => $m->subject_id == $subject->id && $m->term == 1))->marks_obtained;
                                                    $existingTerm2 = optional($candidate->marks->firstWhere(fn($m) => $m->subject_id == $subject->id && $m->term == 2))->marks_obtained;
                                                @endphp
                                                @if($isGme)
                                                    <td>
                                                        <input type="number" name="marks[{{ $candidate->id }}][{{ $subject->id }}][1]"
                                                            class="form-control" max="{{ $subject->max_marks }}" min="0"
                                                            value="{{ $existingTerm1 }}" placeholder="Term-1">
                                                    </td>
                                                    <td>
                                                        <input type="number" name="marks[{{ $candidate->id }}][{{ $subject->id }}][2]"
                                                            class="form-control" max="{{ $subject->max_marks }}" min="0"
                                                            value="{{ $existingTerm2 }}" placeholder="Term-2">
                                                    </td>
                                                @else
                                                    <td>
                                                        <input type="number" name="marks[{{ $candidate->id }}][{{ $subject->id }}]"
                                                            class="form-control" max="{{ $subject->max_marks }}" min="0"
                                                            value="{{ $existing }}">
                                                    </td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save2 me-1"></i> Save Marks
                    </button>
                    <a href="{{ route('course.marks.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        @endif
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('courseDetailSelect');
        const form = document.getElementById('marksForm');
        if (select) {
            select.addEventListener('change', function() {
                const val = this.value;
                let anyVisible = false;
                document.querySelectorAll('.batch-row').forEach(row => {
                    if (val && row.classList.contains(val)) {
                        row.style.display = '';
                        anyVisible = true;
                    } else {
                        row.style.display = 'none';
                    }
                });
                // Show form only if a batch is selected
                if (form) {
                    form.style.display = val ? '' : 'none';
                }
            });
        }
    });
</script>
@endsection
