@extends('layouts.app')
@section('title', 'Review Idea')

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
                    <a href="{{ $idea->pitch_file_url }}" class="btn btn-sm btn-outline-secondary" target="_blank">
                        View Pitch Deck
                    </a>
                @endif
            </div>
        </div>

        @if ($idea->isPending())
            <div class="row g-3">
                {{-- Approve --}}
                <div class="col-md-5">
                    <div class="card border-success">
                        <div class="card-body">
                            <h6 class="text-success">Approve this idea</h6>
                            <form method="POST" action="{{ route('admin.ideas.approve', $idea) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-success w-100"
                                        onclick="return confirm('Approve this idea?')">
                                    Approve
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Reject --}}
                <div class="col-md-7">
                    <div class="card border-danger">
                        <div class="card-body">
                            <h6 class="text-danger">Reject this idea</h6>
                            <form method="POST" action="{{ route('admin.ideas.reject', $idea) }}">
                                @csrf @method('PATCH')
                                <div class="mb-2">
                                    <textarea name="rejection_reason" rows="2"
                                              class="form-control @error('rejection_reason') is-invalid @enderror"
                                              placeholder="Provide a reason for rejection...">{{ old('rejection_reason') }}</textarea>
                                    @error('rejection_reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button class="btn btn-danger w-100">Reject</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-body">
                <h6>Founder</h6>
                <div class="d-flex align-items-center gap-2">
                    <img src="{{ $idea->founder->avatar_url }}" class="rounded-circle" width="36" height="36">
                    <div>
                        <div>{{ $idea->founder->name }}</div>
                        <small class="text-muted">{{ $idea->founder->email }}</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h6>Submitted</h6>
                <p class="mb-0 small text-muted">{{ $idea->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        <div class="mt-3">
            <a href="{{ route('admin.ideas.index') }}" class="btn btn-outline-secondary w-100">Back to list</a>
        </div>
    </div>
</div>
@endsection