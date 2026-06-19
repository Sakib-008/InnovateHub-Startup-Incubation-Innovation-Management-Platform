@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <h4 class="mb-4">Edit Profile</h4>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="mb-3 text-center">
                        <img src="{{ $user->avatar_url }}" class="rounded-circle mb-2" width="90" height="90" alt="avatar">
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror">
                        @error('avatar') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="form-control @error('email') is-invalid @enderror">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">LinkedIn URL</label>
                            <input type="text" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}" class="form-control">
                        </div>
                    </div>

                    @if (auth()->user()->isMentor())
                        <div class="mb-3">
                            <label class="form-label">Expertise</label>
                            <input type="text" name="expertise" value="{{ old('expertise', $user->expertise) }}"
                                   class="form-control" placeholder="e.g. Fintech, Product Strategy">
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" rows="3" class="form-control">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection