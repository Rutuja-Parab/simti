@extends('layout')
@section('content')
    <h2>Select ETO Course & Batch</h2>
    <form action="{{ route('eto.marksheet.selectCandidate', ['courseDetailId' => '']) }}" method="get" id="courseBatchForm">
        <label for="courseDetailId">Course & Batch:</label>
        <select name="courseDetailId" id="courseDetailId" required>
            @foreach($etoCourseDetails as $cd)
                <option value="{{ $cd->id }}">{{ $cd->course->name }} - Batch {{ $cd->batch_no }}</option>
            @endforeach
        </select>
        <button type="submit">Next</button>
    </form>
    <script>
        document.getElementById('courseBatchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var id = document.getElementById('courseDetailId').value;
            window.location.href = "{{ url('eto-marksheet/select-candidate') }}/" + id;
        });
    </script>
@endsection 