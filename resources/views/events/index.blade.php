@extends('layouts.app')
@section('title', 'Events')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Events</h4>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control" placeholder="Search events...">
    </div>
    <div class="col-md-4">
        <select name="status" class="form-select">
            <option value="">All statuses</option>
            @foreach (['upcoming','ongoing','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                    {{ ucfirst($s) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>

<div class="row g-3">
    @forelse ($events as $event)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                @if ($event->banner_url)
                    <img src="{{ $event->banner_url }}" class="card-img-top"
                         style="height:160px; object-fit:cover" alt="banner">
                @else
                    <div class="bg-secondary d-flex align-items-center justify-content-center"
                         style="height:160px">
                        <span class="text-white opacity-50">No image</span>
                    </div>
                @endif

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h6 class="card-title mb-0">{{ $event->title }}</h6>
                        <span class="badge {{ match($event->status) {
                            'upcoming'  => 'bg-primary',
                            'ongoing'   => 'bg-success',
                            'completed' => 'bg-secondary',
                            default     => 'bg-danger'
                        } }}">{{ ucfirst($event->status) }}</span>
                    </div>
                    <small class="text-muted d-block mb-2">
                        📅 {{ $event->event_date->format('M d, Y · h:i A') }}<br>
                        📍 {{ $event->location }}
                    </small>
                    <p class="card-text small">{{ Str::limit($event->description, 80) }}</p>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center">
                    <small class="text-muted">{{ $event->registrations_count }} registered</small>
                    <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-outline-primary">
                        View Details
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">No events found.</div>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $events->links() }}</div>
@endsection