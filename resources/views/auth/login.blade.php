@extends('layouts.guest')
@section('content')
<div class="card shadow-sm">
    <div class="card-body p-4">
        <h3 class="mb-4">Log in to InnovateHub</h3>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="form-control @error('email') is-invalid @enderror">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input id="password" type="password" name="password" required
                       class="form-control @error('password') is-invalid @enderror">
                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label for="remember" class="form-check-label">Remember me</label>
            </div>

            <div class="d-flex align-items-center justify-content-between">
                @if (Route::has('password.request'))
                    <a class="text-decoration-none small" href="{{ route('password.request') }}">
                        Forgot your password?
                    </a>
                @endif
                <button type="submit" class="btn btn-primary">Log in</button>
            </div>

            <div class="text-center mt-3">
                <span class="text-muted small">Don't have an account?</span>
                <a href="{{ route('register') }}" class="small">Register</a>
            </div>
        </form>
    </div>
</div>
@endsection