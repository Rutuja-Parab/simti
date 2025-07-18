@extends('layout')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Role</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('roles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Role Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Assign Permissions</label>
                    <div class="row">
                        @foreach($permissions as $permission)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm_{{ $permission->id }}">
                                <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Create</button>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection 