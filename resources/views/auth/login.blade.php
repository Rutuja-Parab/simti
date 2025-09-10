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
                        <div class="input-group">
                            <input type="password" id="password" name="password" class="form-control" required>
                            <span class="input-group-text" id="togglePassword" style="cursor: pointer;">
                                <i class="bi bi-eye"></i>
                            </span>
                        </div>
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
@section('scripts')
    <script>
        const toggle = document.querySelector('#togglePassword');
        const pwd = document.querySelector('#password');
        const icon = toggle.querySelector('i');

        toggle.addEventListener('click', () => {
            pwd.type = pwd.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        });
    </script>
@endsection