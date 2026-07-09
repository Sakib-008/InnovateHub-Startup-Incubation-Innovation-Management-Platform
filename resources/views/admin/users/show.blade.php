@extends('layouts.app')
@section('title', $user->name)

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h4>User Profile</h4>
    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card text-center p-4">
            <img src="{{ $user->avatar_url }}" class="rounded-circle mx-auto mb-3"
                 width="80" height="80">
            <h5 class="mb-1">{{ $user->name }}</h5>
            <p class="text-muted small mb-2">{{ $user->email }}</p>

            <span class="badge bg-secondary mb-2">{{ ucfirst($user->role) }}</span>
            @if ($user->is_active)
                <span class="badge bg-success d-block mx-auto" style="width:fit-content">Active</span>
            @else
                <span class="badge bg-danger d-block mx-auto" style="width:fit-content">Inactive</span>
            @endif

            @if ($user->phone)
                <p class="text-muted small mt-2">📞 {{ $user->phone }}</p>
            @endif
            @if ($user->bio)
                <p class="text-muted small">{{ $user->bio }}</p>
            @endif
        </div>

        {{-- Actions --}}
        @if ($user->id !== auth()->id())
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="mb-3">Admin Actions</h6>

                    {{-- Toggle status --}}
                    <form method="POST"
                          action="{{ route('admin.users.toggle-status', $user) }}" class="mb-2">
                        @csrf @method('PATCH')
                        <button class="btn w-100 {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}">
                            {{ $user->is_active ? '🔒 Deactivate Account' : '✅ Activate Account' }}
                        </button>
                    </form>

                    {{-- Change role --}}
                    <form method="POST" action="{{ route('admin.users.update-role', $user) }}">
                        @csrf @method('PATCH')
                        <label class="form-label small">Change Role</label>
                        <div class="d-flex gap-2">
                            <select name="role" class="form-select form-select-sm">
                                @foreach (['founder','mentor','investor','admin'] as $r)
                                    <option value="{{ $r }}" {{ $user->role === $r ? 'selected' : '' }}>
                                        {{ ucfirst($r) }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <div class="col-md-8">
        {{-- Activity stats --}}
        <div class="row g-3 mb-4">
            @if ($user->isFounder())
                <div class="col-6">
                    <div class="card stat-card p-3 text-center">
                        <div class="stat-label">Ideas Submitted</div>
                        <div class="stat-value">{{ $ideaCount }}</div>
                    </div>
                </div>
            @endif
            @if ($user->isMentor())
                <div class="col-6">
                    <div class="card stat-card stat-success p-3 text-center">
                        <div class="stat-label">Active Mentorships</div>
                        <div class="stat-value text-success">{{ $mentorshipCount }}</div>
                    </div>
                </div>
            @endif
            @if ($user->isInvestor())
                <div class="col-6">
                    <div class="card stat-card stat-info p-3 text-center">
                        <div class="stat-label">Interests Expressed</div>
                        <div class="stat-value text-info">{{ $interestCount }}</div>
                    </div>
                </div>
            @endif
            <div class="col-6">
                <div class="card p-3 text-center">
                    <div class="text-muted small">Events Registered</div>
                    <div class="fs-3 fw-bold">{{ $eventCount }}</div>
                </div>
            </div>
        </div>

        {{-- Extra info --}}
        <div class="card">
            <div class="card-header">Account Details</div>
            <div class="card-body">
                <table class="table table-sm mb-0">
                    <tr>
                        <td class="text-muted" style="width:40%">Joined</td>
                        <td>{{ $user->created_at->format('M d, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Last Updated</td>
                        <td>{{ $user->updated_at->diffForHumans() }}</td>
                    </tr>
                    @if ($user->linkedin)
                        <tr>
                            <td class="text-muted">LinkedIn</td>
                            <td><a href="{{ $user->linkedin }}" target="_blank">View Profile</a></td>
                        </tr>
                    @endif
                    @if ($user->expertise)
                        <tr>
                            <td class="text-muted">Expertise</td>
                            <td>{{ $user->expertise }}</td>
                        </tr>
                    @endif
                    @if ($user->investorProfile)
                        <tr>
                            <td class="text-muted">Company</td>
                            <td>{{ $user->investorProfile->company_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Focus</td>
                            <td>{{ $user->investorProfile->investment_focus }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Investment Range</td>
                            <td>{{ $user->investorProfile->investment_range }}</td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>

        {{-- Message button --}}
        @if ($user->id !== auth()->id())
            <div class="mt-3">
                <a href="{{ route('messages.start', $user) }}"
                   class="btn btn-outline-primary">
                    💬 Send Message
                </a>
            </div>
        @endif
    </div>
</div>
@endsection