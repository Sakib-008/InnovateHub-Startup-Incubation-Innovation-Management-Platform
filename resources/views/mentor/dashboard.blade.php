@extends('layouts.app')
@section('title', 'Mentor Dashboard')

@section('content')
<h2 class="h4 mb-4">Welcome, {{ auth()->user()->name }}</h2>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h6>Pending Requests</h6>
            <p class="display-6">
                {{ auth()->user()->mentorshipRequestsAsMentor()->where('status','pending')->count() }}
            </p>
            <a href="{{ route('mentor.requests.index') }}" class="btn btn-primary btn-sm">View Requests</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h6>Active Mentorships</h6>
            <p class="display-6">
                {{ auth()->user()->mentorshipRequestsAsMentor()->where('status','accepted')->count() }}
            </p>
            <a href="{{ route('mentor.startups.index') }}" class="btn btn-outline-primary btn-sm">View Startups</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h6>Messages</h6>
            <p class="display-6">{{ auth()->user()->unreadMessagesCount() }}</p>
            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary btn-sm">Inbox</a>
        </div>
    </div>
</div>
@endsection