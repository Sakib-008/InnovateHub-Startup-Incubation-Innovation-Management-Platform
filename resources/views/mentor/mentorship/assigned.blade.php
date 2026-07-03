@extends('layouts.app')
@section('title', 'Assigned Startups')

@section('content')
<h4 class="mb-4">My Assigned Startups</h4>

@forelse ($accepted as $req)
    <div class="card mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h5>{{ $req->startupIdea->title }}</h5>
                    <p class="text-muted small mb-1">
                        Founder: {{ $req->startupIdea->founder->name }}
                        · Category: {{ $req->startupIdea->category }}
                    </p>
                    <p class="mb-0">{{ Str::limit($req->startupIdea->description, 150) }}</p>
                </div>
                <a href="{{ route('messages.start', $req->founder) }}" class="btn btn-sm btn-outline-primary">
                    Message Founder
                </a>
            </div>

            @if ($req->startupIdea->milestones->count() > 0)
                <hr>
                <h6 class="text-muted">Milestones</h6>
                <div class="row g-2">
                    @foreach ($req->startupIdea->milestones as $milestone)
                        <div class="col-md-4">
                            <div class="p-2 border rounded">
                                <div class="d-flex justify-content-between">
                                    <small><strong>{{ $milestone->title }}</strong></small>
                                    <x-milestone-status-badge :status="$milestone->status" />
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@empty
    <div class="alert alert-info">No accepted mentorship requests yet.</div>
@endforelse
@endsection