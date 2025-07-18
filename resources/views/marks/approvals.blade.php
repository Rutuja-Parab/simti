@extends('layout')

@section('content')
<div class="container mt-4">
    <h2>Pending Marks Approval</h2>
    @foreach($groupedInfo as $group)
        <div class="card mb-4">
            <div class="card-header">
                <strong>Course:</strong> {{ $group['course'] ? $group['course']->name : 'Unknown' }} |
                <strong>Batch:</strong> {{ $group['batch'] ?? 'Unknown' }}
            </div>
            <div class="card-body">
                <h6>Pending Marks</h6>
                <form method="POST" action="{{ route('marks.approvals.group.approve', ['course_id' => $group['course']->id ?? 0, 'batch' => $group['batch']]) }}" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-success btn-sm">Approve All in Group</button>
                </form>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Subject</th>
                            <th>Marks</th>
                            <th>Edited By (Faculty)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($group['pending_marks'] as $mark)
                            <tr>
                                <td>{{ $mark->candidate->name ?? '-' }}</td>
                                <td>{{ $mark->subject->name ?? '-' }}</td>
                                <td>{{ $mark->marks_obtained }}</td>
                                <td>{{ $mark->lastEditor ? $mark->lastEditor->name : '-' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('marks.approvals.mark.approve', $mark->id) }}" style="display:inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form method="POST" action="{{ route('marks.approvals.mark.reject', $mark->id) }}" style="display:inline-block">
                                        @csrf
                                        <input type="text" name="rejection_reason" placeholder="Reason" required class="form-control form-control-sm d-inline-block w-auto" style="width:120px;display:inline-block;">
                                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($group['rejected_marks']->count())
                    <h6 class="mt-4">Rejected Marks</h6>
                    <table class="table table-bordered table-warning">
                        <thead>
                            <tr>
                                <th>Candidate</th>
                                <th>Subject</th>
                                <th>Marks</th>
                                <th>Edited By (Faculty)</th>
                                <th>Rejection Reason</th>
                                <th>Rejected At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group['rejected_marks'] as $mark)
                                <tr>
                                    <td>{{ $mark->candidate->name ?? '-' }}</td>
                                    <td>{{ $mark->subject->name ?? '-' }}</td>
                                    <td>{{ $mark->marks_obtained }}</td>
                                    <td>{{ $mark->lastEditor ? $mark->lastEditor->name : '-' }}</td>
                                    <td>{{ $mark->rejection_reason ?? '-' }}</td>
                                    <td>{{ $mark->updated_at ? $mark->updated_at->format('d-m-Y H:i') : '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection 