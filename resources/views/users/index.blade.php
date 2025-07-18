@extends('layout')

@section('content')
<div class="container">
    <h2>Users</h2>
    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">Add New User</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th><th>Email</th><th>Role</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                            Edit
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
