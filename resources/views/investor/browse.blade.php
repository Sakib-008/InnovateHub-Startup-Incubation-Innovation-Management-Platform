@extends('layouts.app')
@section('title', 'Browse Startups')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Browse Startups</h4>
    <a href="{{ route('investor.interests.index') }}" class="btn btn-outline-secondary btn-sm">
        My Interests
    </a>
</div>

{{-- Filters --}}
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-6">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control" placeholder="Search startups...">
    </div>
    <div class="col-md-4">
        <select name="category" class="form-select">
            <option value="">All categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>
                    {{ $cat }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>

<div class="row g-3">
    @forelse ($startups as $startup)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                {{-- Gallery preview --}}
                @if (!empty($startup->showcase->gallery_images))
                    <img src="{{ asset('storage/' . $startup->showcase->gallery_images[0]) }}"
                         class="card-img-top" style="height:160px; object-fit:cover">
                @endif

                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-1">
                        <h6 class="mb-0">{{ $startup->title }}</h6>
                        <span class="badge bg-secondary">{{ $startup->category }}</span>
                    </div>

                    @if ($startup->showcase->tagline)
                        <p class="text-primary small mb-2">{{ $startup->showcase->tagline }}</p>
                    @endif

                    <p class="small text-muted mb-2">
                        By {{ $startup->founder->name }}
                    </p>
                    <p class="small">{{ Str::limit($startup->description, 100) }}</p>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        {{ $startup->investment_interests_count }} interested
                    </small>
                    <div class="d-flex gap-1">
                        @if (in_array($startup->id, $interestedIds))
                            <span class="badge bg-success align-self-center">Interested</span>
                        @endif
                        <a href="{{ route('investor.startup.show', $startup) }}"
                           class="btn btn-sm btn-outline-primary">View</a>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info">
                No showcased startups found.
                @if (request()->hasAny(['search', 'category']))
                    <a href="{{ route('investor.browse') }}">Clear filters</a>
                @endif
            </div>
        </div>
    @endforelse
</div>

<div class="mt-4">{{ $startups->links() }}</div>
@endsection