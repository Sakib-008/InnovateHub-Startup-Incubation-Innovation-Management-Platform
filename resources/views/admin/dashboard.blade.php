@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <h1 class="h3 mb-4">Welcome, {{ auth()->user()->name }}</h1>

    <div class="card">
        <div class="card-body">
            Admin dashboard — platform management and user control panel coming soon.
        </div>
    </div>
@endsection