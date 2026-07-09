@extends('layouts.app')
@section('title', $event->title)

@section('content')
<div class="row">
    <div class="col-md-8">
        @if ($event->banner_url)
            <img src="{{ $event->banner_url }}" class="img-fluid rounded mb-4"
                 style="max-height:300px; width:100%; object-fit:cover">
        @endif

        <h2>{{ $event->title }}</h2>
        <p class="text-muted">
            📅 {{ $event->event_date->format('l, F j, Y · h:i A') }} &nbsp;|&nbsp;
            📍 {{ $event->location }}
        </p>

        <div class="mb-4">{{ $event->description }}</div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h6>Event Details</h6>
                <p class="mb-1 small">
                    <strong>Status:</strong>
                    <span class="badge {{ match($event->status) {
                        'upcoming'  => 'bg-primary',
                        'ongoing'   => 'bg-success',
                        'completed' => 'bg-secondary',
                        default     => 'bg-danger'
                    } }}">{{ ucfirst($event->status) }}</span>
                </p>
                <p class="mb-1 small">
                    <strong>Registered:</strong> {{ $event->registrations_count }}
                    @if ($event->max_attendees)
                        / {{ $event->max_attendees }}
                    @endif
                </p>

                <hr>

                @if ($event->status === 'upcoming' || $event->status === 'ongoing')
                    @if ($isRegistered)
                        <div class="alert alert-success py-2 small">You are registered!</div>
                        <form method="POST" action="{{ route('events.unregister', $event) }}">
                            @csrf @method('DELETE')
                            <button class="btn btn-outline-danger w-100 btn-sm">Cancel Registration</button>
                        </form>
                    @elseif ($event->isFull())
                        <div class="alert alert-warning py-2 small">This event is full.</div>
                    @else
                        <form method="POST" action="{{ route('events.register', $event) }}">
                            @csrf
                            <button class="btn btn-primary w-100">Register Now</button>
                        </form>
                    @endif
                @else
                    <p class="text-muted small">Registration is closed for this event.</p>
                @endif

                @error('event')
                    <div class="text-danger small mt-2">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>
</div>
@endsection