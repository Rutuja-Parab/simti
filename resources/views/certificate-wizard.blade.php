@extends('layout')
@section('content')
<div class="container mt-4">
    <h2>Generate Training Certificates</h2>
    <form id="certificateForm" action="{{ route('certificates.generateMultiple') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="courseSelect" class="form-label">Select Course</label>
                <select id="courseSelect" class="form-select" required>
                    <option value="">-- Select Course --</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label for="batchSelect" class="form-label">Select Batch</label>
                <select id="batchSelect" class="form-select" required disabled>
                    <option value="">-- Select Batch --</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="candidateSelect" class="form-label">Select Candidate(s)</label>
                <select name="candidate_ids[]" id="candidateSelect" class="form-select" multiple required disabled style="height: 200px;">
                    <option value="">-- Select Candidate(s) --</option>
                </select>
                <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</small>
            </div>
        </div>
        <button id="generateBtn" class="btn btn-primary" disabled type="submit">Generate Certificate ZIP</button>
    </form>
</div>
<script>
    const batchSelect = document.getElementById('batchSelect');
    const candidateSelect = document.getElementById('candidateSelect');
    const courseSelect = document.getElementById('courseSelect');
    const generateBtn = document.getElementById('generateBtn');
    const certificateForm = document.getElementById('certificateForm');

    courseSelect.addEventListener('change', function() {
        const courseId = this.value;
        batchSelect.innerHTML = '<option value="">-- Select Batch --</option>';
        candidateSelect.innerHTML = '<option value="">-- Select Candidate(s) --</option>';
        batchSelect.disabled = true;
        candidateSelect.disabled = true;
        generateBtn.disabled = true;
        if (courseId) {
            fetch(`/marksheet/batches/${courseId}`)
                .then(res => res.json())
                .then(batches => {
                    batches.forEach(batch => {
                        const opt = document.createElement('option');
                        opt.value = batch.id;
                        opt.textContent = `Batch ${batch.batch_no}`;
                        batchSelect.appendChild(opt);
                    });
                    batchSelect.disabled = false;
                });
        }
    });

    batchSelect.addEventListener('change', function() {
        const batchId = this.value;
        candidateSelect.innerHTML = '<option value="">-- Select Candidate(s) --</option>';
        candidateSelect.disabled = true;
        generateBtn.disabled = true;
        if (batchId) {
            fetch(`/marksheet/candidates/${batchId}`)
                .then(res => res.json())
                .then(candidates => {
                    candidates.forEach(candidate => {
                        const opt = document.createElement('option');
                        opt.value = candidate.id;
                        opt.textContent = `${candidate.name} (${candidate.roll_no})`;
                        candidateSelect.appendChild(opt);
                    });
                    candidateSelect.disabled = false;
                });
        }
    });

    candidateSelect.addEventListener('change', function() {
        generateBtn.disabled = candidateSelect.selectedOptions.length === 0;
    });
</script>
@endsection 