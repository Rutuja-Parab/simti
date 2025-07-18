@extends('layout')
@section('content')
    <h2>Select Candidate</h2>
    <form action="{{ route('eto.marksheet.generate', ['candidateId' => '']) }}" method="get" id="candidateForm">
        <label for="candidateId">Candidate:</label>
        <select name="candidateId" id="candidateId" required>
            @foreach($candidates as $candidate)
                <option value="{{ $candidate->id }}">{{ $candidate->name }} ({{ $candidate->roll_no }})</option>
            @endforeach
        </select>
        <button type="submit">Generate Marksheet</button>
    </form>
    <script>
        document.getElementById('candidateForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var id = document.getElementById('candidateId').value;
            window.location.href = "{{ url('eto-marksheet/generate') }}/" + id;
        });
    </script>
@endsection 