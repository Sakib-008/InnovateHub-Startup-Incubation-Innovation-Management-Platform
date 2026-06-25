@extends('layouts.app')
@section('title', 'Edit Idea')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <h4 class="mb-4">Edit Startup Idea</h4>

                @if ($idea->isRejected())
                    <div class="alert alert-warning">
                        <strong>Rejection reason:</strong> {{ $idea->rejection_reason }}<br>
                        <small>Editing will resubmit this idea for approval.</small>
                    </div>
                @endif

                <form method="POST" action="{{ route('founder.ideas.update', $idea) }}" enctype="multipart/form-data">
                    @csrf @method('PATCH')

                    <div class="mb-3">
                        <label class="form-label">Idea Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $idea->title) }}"
                               class="form-control @error('title') is-invalid @enderror">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror">
                            @foreach (['AgriTech','EdTech','FinTech','HealthTech','SaaS','E-Commerce','CleanTech','LogisTech','Other'] as $cat)
                                <option value="{{ $cat }}" {{ old('category', $idea->category) === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="5"
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $idea->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Replace Pitch Deck <span class="text-muted">(optional)</span></label>
                        @if ($idea->pitch_file_url)
                            <div class="mb-2">
                                Current: <a href="{{ $idea->pitch_file_url }}" target="_blank">View uploaded file</a>
                            </div>
                        @endif
                        <input type="file" name="pitch_file"
                               class="form-control @error('pitch_file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.ppt,.pptx">
                        @error('pitch_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="{{ route('founder.ideas.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection