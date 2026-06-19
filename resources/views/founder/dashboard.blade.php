@extends('layouts.app')
@section('title', 'Founder Dashboard')
@section('content')
    <h1 class="h3 mb-4">Welcome, {{ auth()->user()->name }}</h1>
    <div class="card">
        <div class="card-body">Founder dashboard — startup idea management.</div>
    </div>
@endsection