<!-- resources/views/auth/login.blade.php -->

@extends('guestLayout')

@section('title', 'Login')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h3 class="text-center mb-4">Login</h3>
            <form method="POST" action="{{ route('login.perform') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
@endsection
