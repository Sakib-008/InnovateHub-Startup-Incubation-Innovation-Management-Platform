@extends('layouts.app')

@section('title', 'Investor Dashboard')

@section('content')
    <h1 class="h3 mb-4">Welcome, {{ auth()->user()->name }}</h1>

    <div class="card">
        <div class="card-body">
            Investor dashboard — explore startup pitches and investment opportunities coming soon.
        </div>
    </div>
@endsection