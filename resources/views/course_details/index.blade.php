@extends('layout')
@section('title', 'Course Details')
@section('content')
    <div class="container">
        <h4 class="mb-4">Course Details</h4>
        <a href="{{ route('course-details.create') }}" class="btn btn-primary mb-3">Add Course Detail</a>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div id="copyAlert" class="alert alert-success"
            style="display:none; position: fixed; top: 20px; right: 20px; z-index: 9999;">
            Link copied to clipboard!
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Course</th>
                    <th>Batch No</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($courseDetails as $detail)
                    @php
                        // build an encrypted payload with id (+optional expiry)
                        $payload = ['id' => $detail->id, 'ts' => now()->timestamp];
                        $token = Crypt::encryptString(json_encode($payload));
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $detail->course->name }}</td>
                        <td>{{ $detail->batch_no }}</td>
                        <td>{{ $detail->start_date }}</td>
                        <td>{{ $detail->end_date }}</td>
                        <td>
                            <a href="{{ route('course-details.edit', $detail->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('course-details.destroy', $detail->id) }}" method="POST"
                                style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Delete this course detail?')">Delete</button>
                            </form>
                            <a href="#"
                               onclick="copyToClipboard('{{ route('candidate.link', ['token' => $token]) }}'); return false;"
                               class="btn btn-sm btn-info">
                               Generate Link
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('scripts')
<script>
    function copyToClipboard(text) {
        const tempInput = document.createElement("input");
        tempInput.value = text;
        document.body.appendChild(tempInput);
        tempInput.select();
        document.execCommand("copy");
        document.body.removeChild(tempInput);

        const alertBox = document.getElementById("copyAlert");
        alertBox.style.display = "block";
        setTimeout(() => {
            alertBox.style.display = "none";
        }, 2000);
    }
</script>
@endsection
