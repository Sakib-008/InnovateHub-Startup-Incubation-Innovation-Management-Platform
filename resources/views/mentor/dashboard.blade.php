@extends('layouts.app')

@section('title', 'Mentor Dashboard')

@section('content')
    <h1 class="h3 mb-4">Welcome, {{ auth()->user()->name }}</h1>

    <div class="card">
        <div class="card-body">
            Mentor dashboard — mentoring sessions and startup guidance features coming soon.
        </div>
    </div>
@endsection