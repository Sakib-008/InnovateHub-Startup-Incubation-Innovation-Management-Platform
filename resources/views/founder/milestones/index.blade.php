@extends('layouts.app')
@section('title', 'Progress Tracking')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Progress Tracking</h4>
        <small class="text-muted">{{ $idea->title }}</small>
    </div>
    <a href="{{ route('founder.ideas.show', $idea) }}" class="btn btn-outline-secondary btn-sm">Back to Idea</a>
</div>

@if (! $idea->isApproved())
    <div class="alert alert-warning">Your idea must be approved before tracking progress.</div>
@else
    {{-- Add milestone form --}}
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="mb-3">Add Milestone</h6>
            <form method="POST" action="{{ route('founder.milestones.store', $idea) }}">
                @csrf
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="Milestone title *">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="description" value="{{ old('description') }}"
                               class="form-control" placeholder="Description (optional)">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="due_date" value="{{ old('due_date') }}"
                               class="form-control">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Add</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Milestones list --}}
    @forelse ($milestones as $milestone)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $milestone->title }}</strong>
                    <x-milestone-status-badge :status="$milestone->status" class="ms-2" />
                    @if ($milestone->due_date)
                        <small class="text-muted ms-2">Due: {{ $milestone->due_date->format('M d, Y') }}</small>
                    @endif
                </div>
                <div class="d-flex align-items-center gap-3">
                    {{-- Progress bar --}}
                    @php $pct = $milestone->progressPercentage() @endphp
                    <div style="width:120px">
                        <div class="progress" style="height:8px">
                            <div class="progress-bar" role="progressbar" style="width:{{ $pct }}%"></div>
                        </div>
                        <small class="text-muted">{{ $pct }}% done</small>
                    </div>
                    <form method="POST" action="{{ route('founder.milestones.destroy', [$idea, $milestone]) }}"
                          onsubmit="return confirm('Delete this milestone and all its tasks?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </div>
            </div>

            <div class="card-body">
                {{-- Tasks list --}}
                @forelse ($milestone->tasks as $task)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <span>{{ $task->title }}</span>
                            <small class="text-muted ms-2">→ {{ $task->assignee->name }}</small>
                            @if ($task->due_date)
                                <small class="text-muted ms-1">({{ $task->due_date->format('M d') }})</small>
                            @endif
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <x-task-status-badge :status="$task->status" />

                            {{-- Quick status toggle --}}
                            <form method="POST" action="{{ route('founder.tasks.update', [$idea, $milestone, $task]) }}">
                                @csrf @method('PATCH')
                                <select name="status" class="form-select form-select-sm" style="width:auto"
                                        onchange="this.form.submit()">
                                    <option value="todo"        {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="done"        {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
                                </select>
                            </form>

                            <form method="POST" action="{{ route('founder.tasks.destroy', [$idea, $milestone, $task]) }}"
                                  onsubmit="return confirm('Delete this task?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">×</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-muted small mb-0">No tasks yet.</p>
                @endforelse

                {{-- Add task form --}}
                <div class="mt-3">
                    <form method="POST" action="{{ route('founder.tasks.store', [$idea, $milestone]) }}">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-4">
                                <input type="text" name="title" class="form-control form-control-sm"
                                       placeholder="Task title *">
                            </div>
                            <div class="col-md-3">
                                <select name="assigned_to" class="form-select form-select-sm">
                                    <option value="">Assign to...</option>
                                    @if ($idea->team)
                                        @foreach ($idea->team->members as $member)
                                            <option value="{{ $member->user_id }}">{{ $member->user->name }}</option>
                                        @endforeach
                                    @else
                                        <option value="{{ auth()->id() }}">Me</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="due_date" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-sm btn-outline-primary w-100">+ Task</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">No milestones yet. Add one above to start tracking progress.</div>
    @endforelse
@endif
@endsection