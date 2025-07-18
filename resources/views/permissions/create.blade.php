@extends('layout')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Add Permission</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('permissions.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Permission Name</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Create</button>
                <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection 