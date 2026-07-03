@extends('layouts.app')
@section('title', 'Mentorship Requests')

@section('content')
<h4 class="mb-4">Mentorship Requests</h4>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Founder</th>
                    <th>Startup Idea</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Received</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requests as $req)
                    <tr>
                        <td>
                            <img src="{{ $req->founder->avatar_url }}" class="rounded-circle me-1" width="24" height="24">
                            {{ $req->founder->name }}
                        </td>
                        <td>{{ $req->startupIdea->title }}</td>
                        <td><small>{{ Str::limit($req->message, 60) ?? '—' }}</small></td>
                        <td><x-status-badge :status="$req->status" /></td>
                        <td><small>{{ $req->created_at->diffForHumans() }}</small></td>
                        <td>
                            @if ($req->isPending())
                                <div class="d-flex gap-1">
                                    <form method="POST" action="{{ route('mentor.requests.accept', $req) }}">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm btn-success">Accept</button>
                                    </form>
                                    <button class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#reject-{{ $req->id }}"
                                            onclick="this.closest('tr').nextElementSibling.classList.toggle('d-none')">
                                        Reject
                                    </button>
                                </div>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                    </tr>
                    @if ($req->isPending())
                        <tr class="d-none" id="reject-row-{{ $req->id }}">
                            <td colspan="6">
                                <form method="POST" action="{{ route('mentor.requests.reject', $req) }}" class="d-flex gap-2">
                                    @csrf @method('PATCH')
                                    <input type="text" name="rejection_reason" class="form-control form-control-sm"
                                           placeholder="Reason for rejection (optional)">
                                    <button type="submit" class="btn btn-sm btn-danger">Confirm Reject</button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No mentorship requests yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $requests->links() }}</div>
@endsection