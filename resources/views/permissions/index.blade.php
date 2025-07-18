@extends('layout')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Permissions</h4>
            <a href="{{ route('permissions.create') }}" class="btn btn-light btn-sm"><i class="bi bi-plus-circle"></i> Add Permission</a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permissions as $permission)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>
                                <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i> Edit</a>
                                <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 