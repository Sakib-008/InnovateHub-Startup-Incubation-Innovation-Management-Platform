@extends('layouts.app')
@section('title', 'Request Mentorship')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Request Mentorship</h4>
        <small class="text-muted">For: <strong>{{ $idea->title }}</strong></small>
    </div>
    <a href="{{ route('founder.ideas.show', $idea) }}" class="btn btn-outline-secondary btn-sm">Back to Idea</a>
</div>

@if (! $idea->isApproved())
    <div class="alert alert-warning">
        Your idea must be <strong>approved</strong> before you can request mentorship.
    </div>
@else
    <div class="row g-3">
        @forelse ($mentors as $mentor)
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ $mentor->avatar_url }}" class="rounded-circle" width="48" height="48">
                            <div>
                                <h6 class="mb-0">{{ $mentor->name }}</h6>
                                <small class="text-muted">{{ $mentor->expertise ?? 'General Mentor' }}</small>
                            </div>
                        </div>
                        <p class="small text-muted mb-3">{{ Str::limit($mentor->bio, 100) ?? 'No bio available.' }}</p>

                        @if (isset($existingRequests[$mentor->id]))
                            <span class="badge {{ $existingRequests[$mentor->id] === 'accepted' ? 'bg-success' : ($existingRequests[$mentor->id] === 'rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                {{ ucfirst($existingRequests[$mentor->id]) }}
                            </span>
                        @else
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse"
                                    data-bs-target="#request-form-{{ $mentor->id }}"
                                    onclick="this.closest('.card').querySelector('.collapse').classList.toggle('show')">
                                Send Request
                            </button>
                            <div class="collapse mt-3" id="request-form-{{ $mentor->id }}">
                                <form method="POST" action="{{ route('founder.mentorship.store', $idea) }}">
                                    @csrf
                                    <input type="hidden" name="mentor_id" value="{{ $mentor->id }}">
                                    <div class="mb-2">
                                        <textarea name="message" class="form-control form-control-sm" rows="2"
                                                  placeholder="Optional message to the mentor..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">Send</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">No mentors are registered yet.</div>
            </div>
        @endforelse
    </div>
@endif
@endsection