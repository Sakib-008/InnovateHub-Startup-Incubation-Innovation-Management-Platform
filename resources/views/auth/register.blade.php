@extends('layouts.guest')
@section('content')
<div class="card shadow-sm">
    <div class="card-body p-4">
        <h3 class="mb-4">Create your account</h3>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="form-control @error('name') is-invalid @enderror">
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                       class="form-control @error('email') is-invalid @enderror">
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">I am a...</label>
                <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                    <option value="">Select role</option>
                    <option value="founder" {{ old('role') === 'founder' ? 'selected' : '' }}>Startup Founder</option>
                    <option value="mentor" {{ old('role') === 'mentor' ? 'selected' : '' }}>Mentor</option>
                    <option value="investor" {{ old('role') === 'investor' ? 'selected' : '' }}>Investor</option>
                </select>
                @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
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

            <div class="d-flex align-items-center justify-content-between mt-4">
                <a class="text-decoration-none" href="{{ route('login') }}">Already registered?</a>
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
        </form>
    </div>
</div>
@endsection