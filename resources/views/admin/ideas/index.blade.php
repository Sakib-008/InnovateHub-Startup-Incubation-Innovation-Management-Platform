@extends('layouts.app')
@section('title', 'Manage Ideas')

@section('content')
<h2 class="h4 mb-4">Startup Ideas</h2>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Title</th>
                    <th>Founder</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($ideas as $idea)
                    <tr>
                        <td>{{ $idea->title }}</td>
                        <td>{{ $idea->founder->name }}</td>
                        <td>{{ $idea->category }}</td>
                        <td><x-status-badge :status="$idea->status" /></td>
                        <td>{{ $idea->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('admin.ideas.show', $idea) }}" class="btn btn-sm btn-outline-primary">Review</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No ideas submitted yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $ideas->links() }}</div>
@endsection