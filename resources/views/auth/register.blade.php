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
        <label class="form-label">I am joining as</label>
        <div class="row g-2">
            @foreach ([
                'founder'  => ['🚀', 'Founder',  'Submit ideas & build teams'],
                'mentor'   => ['🎓', 'Mentor',   'Guide startups to success'],
                'investor' => ['💼', 'Investor', 'Discover opportunities'],
            ] as $value => [$icon, $label, $desc])
                <div class="col-4">
                    <input type="radio"
                           class="role-card-input visually-hidden"
                           name="role"
                           id="role_{{ $value }}"
                           value="{{ $value }}"
                           {{ old('role') === $value ? 'checked' : '' }}
                           required>
                    <label class="role-card-label" for="role_{{ $value }}">
                        <span class="role-icon">{{ $icon }}</span>
                        <span class="role-name">{{ $label }}</span>
                        <span class="role-desc">{{ $desc }}</span>
                    </label>
                </div>
            @endforeach
        </div>
        @error('role') <div class="text-danger small mt-1" style="color:#fca5a5!important">{{ $message }}</div> @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input id="password" type="password" name="password" required
               class="form-control @error('password') is-invalid @enderror">
        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    <div class="mb-4">
        <label class="form-label">Confirm Password</label>
        <input id="password_confirmation" type="password" name="password_confirmation" required
               class="form-control">
    </div>

    <button type="submit" class="btn btn-primary w-100 py-2">Create Account</button>

    <p class="text-center mt-3 mb-0" style="font-size:0.875rem;color:rgba(255,255,255,0.45)">
        Already have an account?
        <a href="{{ route('login') }}">Sign in</a>
    </p>
</form>
@endsection