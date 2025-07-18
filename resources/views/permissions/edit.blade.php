@extends('layout')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Edit Permission</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('permissions.update', $permission) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Permission Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $permission->name }}" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Update</button>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection 