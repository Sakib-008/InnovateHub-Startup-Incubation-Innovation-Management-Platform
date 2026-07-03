@extends('layouts.app')
@section('title', 'Messages')

@section('content')
<h4 class="mb-4">Messages</h4>

@if ($conversations->isEmpty())
    <div class="alert alert-info">
        No conversations yet. You can start a conversation from a mentor's or founder's profile.
    </div>
@else
    <div class="list-group">
        @foreach ($conversations as $conv)
            @php $other = $conv->otherUser(); $unread = $conv->unreadCount(); @endphp
            <a href="{{ route('messages.show', $conv) }}"
               class="list-group-item list-group-item-action d-flex align-items-center gap-3 {{ $unread > 0 ? 'fw-bold' : '' }}">
                <img src="{{ $other->avatar_url }}" class="rounded-circle flex-shrink-0" width="44" height="44">
                <div class="flex-grow-1 overflow-hidden">
                    <div class="d-flex justify-content-between">
                        <span>{{ $other->name }}</span>
                        <small class="text-muted">
                            {{ $conv->latestMessage?->created_at?->diffForHumans() ?? '' }}
                        </small>
                    </div>
                    <small class="text-muted text-truncate d-block">
                        {{ Str::limit($conv->latestMessage?->body, 60) ?? 'No messages yet.' }}
                    </small>
                </div>
                @if ($unread > 0)
                    <span class="badge bg-primary rounded-pill">{{ $unread }}</span>
                @endif
            </a>
        @endforeach
    </div>
@endif
@endsection