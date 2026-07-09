@extends('layouts.app')
@section('title', 'Manage Events')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Events</h4>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">+ Create Event</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Registered</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->event_date->format('M d, Y') }}</td>
                        <td>{{ $event->location }}</td>
                        <td>
                            <span class="badge {{ match($event->status) {
                                'upcoming'  => 'bg-primary',
                                'ongoing'   => 'bg-success',
                                'completed' => 'bg-secondary',
                                default     => 'bg-danger'
                            } }}">{{ ucfirst($event->status) }}</span>
                        </td>
                        <td>{{ $event->registrations_count }}</td>
                        <td class="d-flex gap-1">
                            <a href="{{ route('admin.events.show', $event) }}"
                               class="btn btn-sm btn-outline-primary">View</a>
                            <a href="{{ route('admin.events.edit', $event) }}"
                               class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                                  onsubmit="return confirm('Delete this event?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No events yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $events->links() }}</div>
@endsection