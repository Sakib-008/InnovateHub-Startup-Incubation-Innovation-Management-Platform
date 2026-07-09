@extends('layouts.app')
@section('title', 'Manage Showcase')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Startup Showcase</h4>
        <small class="text-muted">{{ $idea->title }}</small>
    </div>
    <a href="{{ route('founder.ideas.show', $idea) }}" class="btn btn-outline-secondary btn-sm">
        Back to Idea
    </a>
</div>

<div class="row g-4">
    {{-- Showcase form --}}
    <div class="col-md-7">
        <div class="card">
            <div class="card-body p-4">
                <h6 class="mb-3">Showcase Details</h6>

                <form method="POST" action="{{ route('founder.showcase.update', $idea) }}"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Tagline</label>
                        <input type="text" name="tagline"
                               value="{{ old('tagline', $showcase->tagline) }}"
                               class="form-control" placeholder="One-line pitch for your startup">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Achievements</label>
                        <textarea name="achievements" rows="4" class="form-control"
                                  placeholder="Key milestones, awards, traction, users...">{{ old('achievements', $showcase->achievements) }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Website</label>
                        <input type="url" name="website"
                               value="{{ old('website', $showcase->website) }}"
                               class="form-control" placeholder="https://yourstartup.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Add Gallery Images (max 6 total)</label>
                        <input type="file" name="gallery_images[]" multiple
                               class="form-control @error('gallery_images.*') is-invalid @enderror"
                               accept="image/*">
                        <div class="form-text">Upload up to 6 images. Each max 2MB.</div>
                        @error('gallery_images.*')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_public" id="is_public"
                               class="form-check-input" value="1"
                               {{ old('is_public', $showcase->is_public ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_public">
                            Make this showcase publicly visible to investors
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Showcase</button>
                </form>
            </div>
        </div>

        {{-- Gallery --}}
        @if (!empty($showcase->gallery_images))
            <div class="card mt-3">
                <div class="card-body">
                    <h6 class="mb-3">Current Gallery</h6>
                    <div class="row g-2">
                        @foreach ($showcase->gallery_images as $index => $image)
                            <div class="col-4">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image) }}"
                                         class="img-fluid rounded" style="height:100px; width:100%; object-fit:cover">
                                    <form method="POST"
                                          action="{{ route('founder.showcase.image.delete', [$idea, $index]) }}"
                                          class="position-absolute top-0 end-0 m-1">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger btn-sm py-0 px-1"
                                                onclick="return confirm('Remove image?')">×</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Investor interests --}}
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><strong>Investor Interests</strong></div>
            @forelse ($interests as $interest)
                <div class="list-group-item p-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <img src="{{ $interest->investor->avatar_url }}"
                                     class="rounded-circle" width="28" height="28">
                                <strong>{{ $interest->investor->name }}</strong>
                            </div>
                            @if ($interest->investor->investorProfile)
                                <small class="text-muted">
                                    {{ $interest->investor->investorProfile->company_name }}
                                    · {{ $interest->investor->investorProfile->investment_focus }}
                                </small>
                            @endif
                            @if ($interest->message)
                                <p class="small mt-1 mb-1">{{ $interest->message }}</p>
                            @endif
                            <small class="text-muted">{{ $interest->created_at->diffForHumans() }}</small>
                        </div>
                        <form method="POST"
                              action="{{ route('founder.interests.status', [$idea, $interest]) }}">
                            @csrf @method('PATCH')
                            <select name="status" class="form-select form-select-sm"
                                    onchange="this.form.submit()">
                                <option value="pending"   {{ $interest->status === 'pending'   ? 'selected' : '' }}>Pending</option>
                                <option value="contacted" {{ $interest->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="declined"  {{ $interest->status === 'declined'  ? 'selected' : '' }}>Declined</option>
                            </select>
                        </form>
                    </div>
                </div>
            @empty
                <div class="card-body text-muted small">No investor interest yet.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection