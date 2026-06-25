@extends('layouts.app')
@section('title', 'Create Team')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body p-4">
                <h4 class="mb-1">Create a Team</h4>
                <p class="text-muted mb-4">For: <strong>{{ $idea->title }}</strong></p>

                <form method="POST" action="{{ route('founder.teams.store', $idea) }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Team Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="e.g. Team Innovate">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Description <span class="text-muted">(optional)</span></label>
                        <textarea name="description" rows="3"
                                  class="form-control">{{ old('description') }}</textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Create Team</button>
                        <a href="{{ route('founder.ideas.show', $idea) }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection