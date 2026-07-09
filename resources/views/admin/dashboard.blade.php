@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<div class="page-header">
    <h4>Admin Dashboard</h4>
    <span class="text-muted">Platform overview</span>
</div>

{{-- Stats row 1 --}}
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card stat-card p-3">
            <div class="stat-label">Total Users</div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary mt-2">Manage</a>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card stat-card stat-warning p-3">
            <div class="stat-label">Pending Ideas</div>
            <div class="stat-value text-warning">{{ $stats['pending_ideas'] }}</div>
            <a href="{{ route('admin.ideas.index') }}" class="btn btn-sm btn-outline-warning mt-2">Review</a>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card stat-card stat-success p-3">
            <div class="stat-label">Approved Ideas</div>
            <div class="stat-value text-success">{{ $stats['approved_ideas'] }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card stat-card stat-info p-3">
            <div class="stat-label">Upcoming Events</div>
            <div class="stat-value text-info">{{ $stats['upcoming_events'] }}</div>
            <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-outline-info mt-2">Manage</a>
        </div>
    </div>
</div>

{{-- Stats row 2 --}}
<div class="row g-3 mb-4">
    <div class="col-md-3 col-6">
        <div class="card p-3 text-center">
            <div class="text-muted small">Founders</div>
            <div class="fs-3 fw-bold">{{ $stats['total_founders'] }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card p-3 text-center">
            <div class="text-muted small">Mentors</div>
            <div class="fs-3 fw-bold">{{ $stats['total_mentors'] }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card p-3 text-center">
            <div class="text-muted small">Investors</div>
            <div class="fs-3 fw-bold">{{ $stats['total_investors'] }}</div>
        </div>
    </div>
    <div class="col-md-3 col-6">
        <div class="card p-3 text-center">
            <div class="text-muted small">Active Mentorships</div>
            <div class="fs-3 fw-bold">{{ $stats['total_mentorships'] }}</div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Recent ideas --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Startup Ideas</span>
                <a href="{{ route('admin.ideas.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($recentIdeas as $idea)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <a href="{{ route('admin.ideas.show', $idea) }}"
                               class="text-decoration-none fw-medium">{{ $idea->title }}</a>
                            <div class="text-muted small">by {{ $idea->founder->name }}</div>
                        </div>
                        <x-status-badge :status="$idea->status" />
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- Recent users --}}
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Recent Registrations</span>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($recentUsers as $user)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $user->avatar_url }}" class="rounded-circle" width="32" height="32">
                            <div>
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="text-decoration-none fw-medium">{{ $user->name }}</a>
                                <div class="text-muted small">{{ $user->email }}</div>
                            </div>
                        </div>
                        <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection