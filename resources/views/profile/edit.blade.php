@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="page-header">
    <h4>My Profile</h4>
    <span class="text-muted">Manage your personal information</span>
</div>

<div class="row g-4">
    {{-- Avatar card --}}
    <div class="col-md-3">
        <div class="card text-center p-4">
            <img src="{{ $user->avatar_url }}" class="rounded-circle mx-auto mb-3"
                 width="90" height="90" id="avatarPreview">
            <h6 class="mb-0">{{ $user->name }}</h6>
            <p class="text-muted small mb-0">{{ ucfirst($user->role) }}</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="col-md-9">
        <div class="card">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('profile.update') }}"
                      enctype="multipart/form-data">
                    @csrf @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" name="avatar" class="form-control"
                               accept="image/*" id="avatarInput"
                               onchange="document.getElementById('avatarPreview').src = URL.createObjectURL(this.files[0])">
                        <div class="form-text">Max 2MB. JPG, PNG, GIF.</div>
                        @error('avatar') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                   class="form-control" placeholder="+880 ...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">LinkedIn URL</label>
                            <input type="text" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}"
                                   class="form-control" placeholder="https://linkedin.com/in/...">
                        </div>
                        @if ($user->isMentor())
                            <div class="col-12">
                                <label class="form-label">Areas of Expertise</label>
                                <input type="text" name="expertise"
                                       value="{{ old('expertise', $user->expertise) }}"
                                       class="form-control"
                                       placeholder="e.g. Fintech, Product Strategy, B2B SaaS">
                            </div>
                        @endif
                        <div class="col-12">
                            <label class="form-label">Bio</label>
                            <textarea name="bio" rows="3" class="form-control"
                                      placeholder="Tell others about yourself...">{{ old('bio', $user->bio) }}</textarea>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Danger zone --}}
        <div class="card mt-3 border-danger">
            <div class="card-body">
                <h6 class="text-danger">Danger Zone</h6>
                <p class="text-muted small mb-3">
                    Deleting your account is permanent and cannot be undone.
                </p>
                <button type="button" class="btn btn-outline-danger btn-sm"
                        onclick="document.getElementById('deleteAccountForm').classList.toggle('d-none')">
                    Delete My Account
                </button>

                <form id="deleteAccountForm" method="POST" action="{{ route('profile.destroy') }}"
                      class="mt-3 d-none">
                    @csrf @method('DELETE')
                    <div class="mb-2">
                        <label class="form-label small">Confirm your password</label>
                        <input type="password" name="password"
                               class="form-control form-control-sm @error('password', 'userDeletion') is-invalid @enderror"
                               placeholder="Enter your password">
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Are you absolutely sure? This cannot be undone.')">
                        Permanently Delete Account
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection