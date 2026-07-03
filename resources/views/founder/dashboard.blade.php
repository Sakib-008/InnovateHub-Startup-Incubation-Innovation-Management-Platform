@extends('layouts.app')
@section('title', 'Founder Dashboard')

@section('content')
<h2 class="h4 mb-4">Welcome, {{ auth()->user()->name }}</h2>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6>My Ideas</h6>
            <p class="display-6">{{ auth()->user()->startupIdeas()->count() }}</p>
            <a href="{{ route('founder.ideas.index') }}" class="btn btn-primary btn-sm">Manage</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6>Approved</h6>
            <p class="display-6 text-success">{{ auth()->user()->startupIdeas()->approved()->count() }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6>Mentorships</h6>
            <p class="display-6">{{ auth()->user()->mentorshipRequestsAsFounder()->where('status','accepted')->count() }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6>Messages</h6>
            <p class="display-6">{{ auth()->user()->unreadMessagesCount() }}</p>
            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary btn-sm">Inbox</a>
        </div>
    </div>
</div>
@endsection