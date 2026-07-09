@extends('layouts.app')
@section('title', 'My Investment Interests')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">My Investment Interests</h4>
    <a href="{{ route('investor.browse') }}" class="btn btn-outline-primary btn-sm">Browse Startups</a>
</div>

@forelse ($interests as $interest)
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-start">
            <div>
                <h6>{{ $interest->startupIdea->title }}</h6>
                <p class="text-muted small mb-1">
                    By {{ $interest->startupIdea->founder->name }}
                    · {{ $interest->startupIdea->category }}
                </p>
                @if ($interest->message)
                    <p class="small mb-1">Your message: {{ $interest->message }}</p>
                @endif
                <small class="text-muted">Sent {{ $interest->created_at->diffForHumans() }}</small>
            </div>
            <div class="d-flex flex-column align-items-end gap-2">
                <span class="badge {{ match($interest->status) {
                    'contacted' => 'bg-success',
                    'declined'  => 'bg-danger',
                    default     => 'bg-warning text-dark',
                } }}">{{ ucfirst($interest->status) }}</span>

                <form method="POST" action="{{ route('investor.interest.destroy', $interest) }}"
                      onsubmit="return confirm('Withdraw interest?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Withdraw</button>
                </form>
            </div>
        </div>
    </div>
@empty
    <div class="alert alert-info">
        No interests yet. <a href="{{ route('investor.browse') }}">Browse startups</a> to get started.
    </div>
@endforelse

<div class="mt-3">{{ $interests->links() }}</div>
@endsection