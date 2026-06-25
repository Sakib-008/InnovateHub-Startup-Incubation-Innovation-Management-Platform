@extends('layouts.app')
@section('title', 'Submit Startup Idea')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body p-4">
                <h4 class="mb-4">Submit a Startup Idea</h4>

                <form method="POST" action="{{ route('founder.ideas.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Idea Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="form-control @error('title') is-invalid @enderror"
                               placeholder="e.g. AI-powered crop monitoring platform">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category" class="form-select @error('category') is-invalid @enderror">
                            <option value="">Select a category</option>
                            @foreach (['AgriTech','EdTech','FinTech','HealthTech','SaaS','E-Commerce','CleanTech','LogisTech','Other'] as $cat)
                                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                        @error('category') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description <span class="text-danger">*</span></label>
                        <textarea name="description" rows="5"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Describe your idea, the problem it solves, and your target market...">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Pitch Deck / Document <span class="text-muted">(optional)</span></label>
                        <input type="file" name="pitch_file"
                               class="form-control @error('pitch_file') is-invalid @enderror"
                               accept=".pdf,.doc,.docx,.ppt,.pptx">
                        <div class="form-text">PDF, Word, or PowerPoint — max 5MB</div>
                        @error('pitch_file') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Submit Idea</button>
                        <a href="{{ route('founder.ideas.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection