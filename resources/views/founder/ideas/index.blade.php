@extends('layouts.app')
@section('title', 'My Startup Ideas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h4 mb-0">My Startup Ideas</h2>
    <a href="{{ route('founder.ideas.create') }}" class="btn btn-primary">+ Submit New Idea</a>
</div>

@if ($ideas->isEmpty())
    <div class="alert alert-info">
        You haven't submitted any ideas yet.
        <a href="{{ route('founder.ideas.create') }}">Submit your first idea</a>.
    </div>
@else
    <div class="row g-3">
        @foreach ($ideas as $idea)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title mb-0">{{ $idea->title }}</h5>
                            <x-status-badge :status="$idea->status" />
                        </div>
                        <p class="text-muted small mb-1">{{ $idea->category }}</p>
                        <p class="card-text">{{ Str::limit($idea->description, 100) }}</p>
                    </div>
                    <div class="card-footer d-flex gap-2">
                        <a href="{{ route('founder.ideas.show', $idea) }}" class="btn btn-sm btn-outline-primary">View</a>
                        @if (!$idea->isApproved())
                            <a href="{{ route('founder.ideas.edit', $idea) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('founder.ideas.destroy', $idea) }}"
                                  onsubmit="return confirm('Delete this idea?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $ideas->links() }}</div>
@endif
@endsection