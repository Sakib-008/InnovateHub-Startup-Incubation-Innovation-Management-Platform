@extends('layouts.app')
@section('title', 'Manage Team')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>{{ $team->name }}</strong>
                <a href="{{ route('founder.ideas.show', $idea) }}" class="btn btn-sm btn-outline-secondary">
                    Back to Idea
                </a>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($team->members as $member)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>
                            <img src="{{ $member->user->avatar_url }}" class="rounded-circle me-2" width="32" height="32">
                            {{ $member->user->name }}
                            <small class="text-muted">({{ $member->user->email }})</small>
                        </span>
                        <span class="d-flex align-items-center gap-2">
                            <span class="badge bg-secondary">{{ $member->role_in_team ?? 'Member' }}</span>
                            @if ($member->user_id !== auth()->id())
                                <form method="POST"
                                      action="{{ route('founder.teams.members.remove', [$idea, $member]) }}"
                                      onsubmit="return confirm('Remove this member?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Add member form --}}
        <div class="card">
            <div class="card-body">
                <h5 class="mb-3">Add Team Member</h5>
                <form method="POST" action="{{ route('founder.teams.members.add', $idea) }}">
                    @csrf
                    <div class="row g-2 align-items-start">
                        <div class="col-md-5">
                            <label class="form-label">User Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control @error('email') is-invalid @enderror"
                                   placeholder="user@example.com">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Role in Team</label>
                            <input type="text" name="role_in_team" value="{{ old('role_in_team') }}"
                                   class="form-control" placeholder="e.g. Backend Dev">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary d-block w-100">Add</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection