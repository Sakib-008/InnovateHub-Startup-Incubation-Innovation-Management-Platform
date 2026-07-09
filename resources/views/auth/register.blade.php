@extends('layouts.guest')
@section('content')

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
               class="form-control @error('name') is-invalid @enderror"
               placeholder="Your full name">
        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required
               class="form-control @error('email') is-invalid @enderror"
               placeholder="you@example.com">
        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">I am joining as a...</label>
        <div class="row g-2">
            @foreach (['founder' => ['🚀', 'Founder', 'Submit ideas & build teams'], 'mentor' => ['🎓', 'Mentor', 'Guide startups to success'], 'investor' => ['💼', 'Investor', 'Discover investment opportunities']] as $value => [$icon, $label, $desc])
                <div class="col-md-4">
                    <input type="radio" class="btn-check" name="role" id="role_{{ $value }}"
                           value="{{ $value }}" {{ old('role') === $value ? 'checked' : '' }} required>
                    <label class="btn btn-outline-primary w-100 text-start p-2" for="role_{{ $value }}">
                        <div class="fs-5">{{ $icon }}</div>
                        <div class="fw-medium small">{{ $label }}</div>
                        <div class="text-muted" style="font-size:0.7rem">{{ $desc }}</div>
                    </label>
                </div>
            @endforeach
        </div>
        @error('role') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input id="password" type="password" name="password" required
               class="form-control @error('password') is-invalid @enderror">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required
               class="form-control">
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">Create Account</button>

    <p class="text-center text-muted mt-3 mb-0 small">
        Already have an account?
        <a href="{{ route('login') }}" class="text-decoration-none">Sign in</a>
    </p>
</form>
@endsection