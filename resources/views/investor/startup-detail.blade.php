@extends('layouts.app')
@section('title', $idea->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        <h2>{{ $idea->title }}</h2>

        @if ($idea->showcase->tagline)
            <p class="lead text-primary">{{ $idea->showcase->tagline }}</p>
        @endif

        <p class="text-muted">
            Category: <strong>{{ $idea->category }}</strong> ·
            By <strong>{{ $idea->founder->name }}</strong>
        </p>

        <p>{{ $idea->description }}</p>

        @if ($idea->showcase->achievements)
            <div class="card mb-3">
                <div class="card-body">
                    <h6>Achievements & Traction</h6>
                    <p class="mb-0">{{ $idea->showcase->achievements }}</p>
                </div>
            </div>
        @endif

        {{-- Gallery --}}
        @if (!empty($idea->showcase->gallery_images))
            <h6 class="mb-2">Gallery</h6>
            <div class="row g-2 mb-4">
                @foreach ($idea->showcase->gallery_images as $img)
                    <div class="col-4">
                        <img src="{{ asset('storage/' . $img) }}"
                             class="img-fluid rounded" style="height:150px; width:100%; object-fit:cover">
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Team --}}
        @if ($idea->team)
            <h6 class="mb-2">Team</h6>
            <div class="row g-2 mb-4">
                @foreach ($idea->team->members as $member)
                    <div class="col-md-4">
                        <div class="card text-center p-2">
                            <img src="{{ $member->user->avatar_url }}"
                                 class="rounded-circle mx-auto mb-2" width="48" height="48">
                            <small><strong>{{ $member->user->name }}</strong></small>
                            <small class="text-muted d-block">{{ $member->role_in_team ?? 'Member' }}</small>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6>Express Investment Interest</h6>

                @if ($hasInterest)
                    <div class="alert alert-success py-2 small">
                        You have already expressed interest in this startup.
                    </div>
                    <a href="{{ route('investor.interests.index') }}" class="btn btn-outline-secondary btn-sm w-100">
                        View My Interests
                    </a>
                @else
                    <form method="POST" action="{{ route('investor.interest.store', $idea) }}">
                        @csrf
                        <div class="mb-2">
                            <textarea name="message" class="form-control form-control-sm" rows="3"
                                      placeholder="Optional message to the founder..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Express Interest</button>
                    </form>
                    @error('interest')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                @endif

                <hr>
                <a href="{{ route('messages.start', $idea->founder) }}"
                   class="btn btn-outline-secondary w-100 btn-sm">
                    Message Founder
                </a>
            </div>
        </div>

        @if ($idea->showcase->website)
            <div class="card">
                <div class="card-body">
                    <h6>Website</h6>
                    <a href="{{ $idea->showcase->website }}" target="_blank" class="btn btn-outline-primary btn-sm w-100">
                        Visit Website ↗
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection