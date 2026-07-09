@extends('layouts.app')
@section('title', 'Investor Dashboard')

@section('content')
<h2 class="h4 mb-4">Welcome, {{ auth()->user()->name }}</h2>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h6>Startups Browsed</h6>
            <p class="display-6">{{ auth()->user()->investmentInterests()->count() }}</p>
            <a href="{{ route('investor.browse') }}" class="btn btn-primary btn-sm">Browse Startups</a>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center p-3">
            <h6>Interests Expressed</h6>
            <p class="display-6 text-success">{{ auth()->user()->investmentInterests()->count() }}</p>
            <a href="{{ route('investor.interests.index') }}" class="btn btn-outline-primary btn-sm">View</a>
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