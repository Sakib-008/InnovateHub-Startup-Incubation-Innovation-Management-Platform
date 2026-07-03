@extends('layouts.app')
@section('title', 'Chat with ' . $other->name)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header d-flex align-items-center gap-2">
                <img src="{{ $other->avatar_url }}" class="rounded-circle" width="32" height="32">
                <strong>{{ $other->name }}</strong>
                <span class="badge bg-secondary ms-1">{{ ucfirst($other->role) }}</span>
            </div>

            {{-- Message thread --}}
            <div class="card-body" style="height:420px; overflow-y:auto;" id="thread">
                @forelse ($messages as $msg)
                    @php $mine = $msg->sender_id === auth()->id(); @endphp
                    <div class="d-flex {{ $mine ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                        @if (!$mine)
                            <img src="{{ $msg->sender->avatar_url }}" class="rounded-circle me-2 align-self-end"
                                 width="28" height="28">
                        @endif
                        <div class="p-2 px-3 rounded-3 {{ $mine ? 'bg-primary text-white' : 'bg-light' }}"
                             style="max-width:70%">
                            <div>{{ $msg->body }}</div>
                            <div class="text-end mt-1">
                                <small class="{{ $mine ? 'text-white-50' : 'text-muted' }}" style="font-size:0.7rem">
                                    {{ $msg->created_at->format('h:i A') }}
                                    @if ($mine) · {{ $msg->isRead() ? '✓✓' : '✓' }} @endif
                                </small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-4">
                        No messages yet. Say hello!
                    </div>
                @endforelse
            </div>

            {{-- Send form --}}
            <div class="card-footer">
                <form method="POST" action="{{ route('messages.send', $conversation) }}">
                    @csrf
                    <div class="d-flex gap-2">
                        <input type="text" name="body" class="form-control"
                               placeholder="Type a message..." autofocus autocomplete="off"
                               value="{{ old('body') }}">
                        <button type="submit" class="btn btn-primary px-4">Send</button>
                    </div>
                    @error('body') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-scroll thread to bottom on load
    const thread = document.getElementById('thread');
    thread.scrollTop = thread.scrollHeight;
</script>
@endpush
@endsection