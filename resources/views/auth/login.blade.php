@extends('layouts.guest')
@section('content')

@if (session('status'))
    <div class="alert alert-success mb-3">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
               class="form-control @error('email') is-invalid @enderror"
               placeholder="you@example.com">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label d-flex justify-content-between">
            Password
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-decoration-none small">
                    Forgot password?
                </a>
            @endif
        </label>
        <input id="password" type="password" name="password" required
               class="form-control @error('password') is-invalid @enderror">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3 form-check">
        <input type="checkbox" name="remember" id="remember" class="form-check-input">
        <label for="remember" class="form-check-label small">Remember me</label>
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">Sign In</button>

    <p class="text-center text-muted mt-3 mb-0 small">
        Don't have an account?
        <a href="{{ route('register') }}" class="text-decoration-none">Create one</a>
    </p>
</form>

{{-- Demo credentials helper --}}
<div class="mt-4 pt-3 border-top">
    <p class="text-muted small text-center mb-2">Demo accounts</p>
    <div class="row g-1">
        @foreach ([
            ['admin@innovatehub.test', 'Admin'],
        ] as [$email, $label])
            <div class="col-12">
                <button type="button"
                        class="btn btn-outline-secondary btn-sm w-100"
                        onclick="
                            document.getElementById('email').value='{{ $email }}';
                            document.getElementById('password').value='password';
                        ">
                    Fill {{ $label }} credentials
                </button>
            </div>
        @endforeach
    </div>
</div>
@endsection