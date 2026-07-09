@extends('layouts.app')
@section('title', $event->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">{{ $event->title }}</h4>
        <small class="text-muted">
            {{ $event->event_date->format('M d, Y · h:i A') }} · {{ $event->location }}
        </small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6>Registered</h6>
            <p class="display-6">{{ $event->registrations_count }}</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center p-3">
            <h6>Attended</h6>
            <p class="display-6 text-success">
                {{ $registrations->where('attended', true)->count() }}
            </p>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><strong>Registrations & Attendance</strong></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Registered At</th>
                    <th>Attended</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($registrations as $reg)
                    <tr>
                        <td>
                            <img src="{{ $reg->user->avatar_url }}" class="rounded-circle me-2"
                                 width="24" height="24">
                            {{ $reg->user->name }}
                        </td>
                        <td><span class="badge bg-secondary">{{ ucfirst($reg->user->role) }}</span></td>
                        <td>{{ $reg->created_at->format('M d, Y') }}</td>
                        <td>
                            @if ($reg->attended)
                                <span class="badge bg-success">Present</span>
                            @else
                                <span class="badge bg-secondary">Absent</span>
                            @endif
                        </td>
                        <td>
                            <form method="POST"
                                  action="{{ route('admin.events.attendance.toggle', [$event, $reg]) }}">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $reg->attended ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                    {{ $reg->attended ? 'Mark Absent' : 'Mark Present' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No registrations yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $registrations->links() }}</div>
@endsection