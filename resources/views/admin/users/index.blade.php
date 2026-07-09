@extends('layouts.app')
@section('title', 'User Management')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h4>User Management</h4>
        <span class="text-muted">{{ $roleCounts['all'] }} total users</span>
    </div>
</div>

{{-- Role filter tabs --}}
<div class="mb-3">
    @foreach (['all' => 'All', 'founder' => 'Founders', 'mentor' => 'Mentors', 'investor' => 'Investors', 'admin' => 'Admins'] as $key => $label)
        <a href="{{ route('admin.users.index', array_merge(request()->query(), ['role' => $key === 'all' ? '' : $key])) }}"
           class="btn btn-sm me-1 {{ request('role', '') === ($key === 'all' ? '' : $key) ? 'btn-primary' : 'btn-outline-secondary' }}">
            {{ $label }}
            <span class="badge bg-white text-dark ms-1">{{ $roleCounts[$key] }}</span>
        </a>
    @endforeach
</div>

{{-- Search & status filter --}}
<form method="GET" class="row g-2 mb-3">
    @if (request('role'))
        <input type="hidden" name="role" value="{{ request('role') }}">
    @endif
    <div class="col-md-6">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control" placeholder="Search by name or email...">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All statuses</option>
            <option value="active"   {{ request('status') === 'active'   ? 'selected' : '' }}>Active</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100">Search</button>
    </div>
</form>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img src="{{ $user->avatar_url }}" class="rounded-circle"
                                     width="36" height="36">
                                <div>
                                    <div class="fw-medium">{{ $user->name }}</div>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td>
                            @if ($user->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="btn btn-sm btn-outline-primary">View</a>

                                @if ($user->id !== auth()->id())
                                    <form method="POST"
                                          action="{{ route('admin.users.toggle-status', $user) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">{{ $users->links() }}</div>
@endsection