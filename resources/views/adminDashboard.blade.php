@extends('layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">
    <h1 class="text-primary fw-bold mb-3">Welcome to Seven Islands Maritime Training Institute</h1>
    <p class="lead">Manage your candidates and academic records with ease. Use the sidebar to navigate between tasks.</p>
    <hr>
    <div class="row g-4 mb-4">
        <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-people-fill display-5 text-primary"></i>
                    <h5 class="card-title mt-2">Users</h5>
                    <h2 class="fw-bold">{{ $userCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-person-lines-fill display-5 text-primary"></i>
                    <h5 class="card-title mt-2">Candidates</h5>
                    <h2 class="fw-bold">{{ $candidateCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-journal-text display-5 text-primary"></i>
                    <h5 class="card-title mt-2">Courses</h5>
                    <h2 class="fw-bold">{{ $courseCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-book display-5 text-primary"></i>
                    <h5 class="card-title mt-2">Subjects</h5>
                    <h2 class="fw-bold">{{ $subjectCount }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-2 col-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body">
                    <i class="bi bi-clipboard-data display-5 text-primary"></i>
                    <h5 class="card-title mt-2">Marks</h5>
                    <h2 class="fw-bold">{{ $marksCount }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-person-lines-fill me-2"></i> Recent Candidates
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Roll No</th>
                                <th>DOB</th>
                                <th>Course Detail</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentCandidates as $candidate)
                                <tr>
                                    <td>{{ $candidate->name }}</td>
                                    <td>{{ $candidate->roll_no }}</td>
                                    <td>{{ $candidate->dob ? $candidate->dob->format('d-m-Y') : '' }}</td>
                                    <td>{{ $candidate->courseDetail->name ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">No recent candidates</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-clipboard-data me-2"></i> Recent Marks Entries
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Candidate</th>
                                <th>Subject</th>
                                <th>Marks</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentMarks as $mark)
                                <tr>
                                    <td>{{ $mark->candidate->name ?? '-' }}</td>
                                    <td>{{ $mark->subject->name ?? '-' }}</td>
                                    <td>{{ $mark->marks_obtained }}</td>
                                    <td>
                                        @if($mark->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($mark->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">No recent marks entries</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="alert alert-info mt-4" role="alert">
        Tip: Keep candidate information and marks updated regularly for accurate reporting!
    </div>
</div>
@endsection
