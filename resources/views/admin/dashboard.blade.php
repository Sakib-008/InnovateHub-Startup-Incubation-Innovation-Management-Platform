@extends('layouts.app')
@section('title', 'Admin Dashboard')

@section('content')
<h2 class="h4 mb-4">Admin Dashboard</h2>

<div class="row g-3">
    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6>Total Ideas</h6>
            <p class="display-6">{{ \App\Models\StartupIdea::count() }}</p>
            <a href="{{ route('admin.ideas.index') }}" class="btn btn-sm btn-outline-primary">Manage</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6>Pending Approval</h6>
            <p class="display-6 text-warning">{{ \App\Models\StartupIdea::pending()->count() }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6>Events</h6>
            <p class="display-6">{{ \App\Models\Event::count() }}</p>
            <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-outline-primary">Manage</a>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6>Total Users</h6>
            <p class="display-6">{{ \App\Models\User::count() }}</p>
        </div>
    </div>
</div>
@endsection