@extends('layouts.app')
@section('title', 'Founder Dashboard')

@section('content')
<h2 class="h4 mb-4">Welcome, {{ auth()->user()->name }}</h2>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h5>My Ideas</h5>
            <p class="display-6">{{ auth()->user()->startupIdeas()->count() }}</p>
            <a href="{{ route('founder.ideas.index') }}" class="btn btn-primary btn-sm">Manage Ideas</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h5>Approved</h5>
            <p class="display-6">{{ auth()->user()->startupIdeas()->approved()->count() }}</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h5>Pending</h5>
            <p class="display-6">{{ auth()->user()->startupIdeas()->pending()->count() }}</p>
        </div>
    </div>
</div>
@endsection