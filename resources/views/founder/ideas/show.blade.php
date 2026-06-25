@extends('layouts.app')
@section('title', $idea->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h3>{{ $idea->title }}</h3>
                    <x-status-badge :status="$idea->status" />
                </div>
                <p class="text-muted">Category: <strong>{{ $idea->category }}</strong></p>
                <p>{{ $idea->description }}</p>

                @if ($idea->pitch_file_url)
                    <a href="{{ $idea->pitch_file_url }}" class="btn btn-sm btn-outline-primary" target="_blank">
                        View Pitch Deck
                    </a>
                @endif

                @if ($idea->isRejected())
                    <div class="alert alert-danger mt-3">
                        <strong>Rejection reason:</strong> {{ $idea->rejection_reason }}
                    </div>
                @endif
            </div>
            @if (!$idea->isApproved())
                <div class="card-footer d-flex gap-2">
                    <a href="{{ route('founder.ideas.edit', $idea) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                    <form method="POST" action="{{ route('founder.ideas.destroy', $idea) }}"
                          onsubmit="return confirm('Delete this idea?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </div>
            @endif
        </div>

        {{-- Team section --}}
        @if ($idea->team)
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <strong>Team: {{ $idea->team->name }}</strong>
                    <a href="{{ route('founder.teams.show', $idea) }}" class="btn btn-sm btn-outline-primary">Manage Team</a>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach ($idea->team->members as $member)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <span>
                                <img src="{{ $member->user->avatar_url }}" class="rounded-circle me-2" width="28" height="28">
                                {{ $member->user->name }}
                            </span>
                            <span class="badge bg-secondary">{{ $member->role_in_team ?? 'Member' }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <div class="alert alert-info">
                No team yet.
                <a href="{{ route('founder.teams.create', $idea) }}">Create a team</a> for this idea.
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title">Idea Details</h6>
                <p class="mb-1 small text-muted">Submitted: {{ $idea->created_at->format('M d, Y') }}</p>
                <p class="mb-1 small text-muted">Last updated: {{ $idea->updated_at->diffForHumans() }}</p>
                <p class="mb-0 small text-muted">Status: <x-status-badge :status="$idea->status" /></p>
            </div>
        </div>
    </div>
</div>
@endsection