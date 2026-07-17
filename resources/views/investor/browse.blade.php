@extends('layouts.app')
@section('title', 'Browse Startups')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Browse Startups</h4>
        <small id="resultCount" class="text-muted">Loading...</small>
    </div>
    <a href="{{ route('investor.interests.index') }}" class="btn btn-outline-secondary btn-sm">
        My Interests
    </a>
</div>

{{-- Currency Converter Widget --}}
<div id="currencyWidget" class="card mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <h6 class="mb-0">💱 Investment Currency Converter</h6>
            <small class="text-muted">Live rates via Frankfurter API</small>
        </div>

        <div id="currentRate" class="mb-3">
            <span class="text-muted small">Loading exchange rates...</span>
        </div>

        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small">Amount (USD $)</label>
                <input type="number" id="convertAmount" class="form-control"
                       placeholder="e.g. 50000" min="0" step="1000">
            </div>
            <div class="col-md-3">
                <label class="form-label small">Convert to</label>
                <select id="convertTo" class="form-select">
                    <option value="EUR">EUR — Euro</option>
                    <option value="GBP">GBP — British Pound</option>
                    <option value="SGD">SGD — Singapore Dollar</option>
                    <option value="JPY">JPY — Japanese Yen</option>
                    <option value="INR">INR — Indian Rupee</option>
                    <option value="AUD">AUD — Australian Dollar</option>
                    <option value="CAD">CAD — Canadian Dollar</option>
                    <option value="CHF">CHF — Swiss Franc</option>
                </select>
            </div>
            <div class="col-md-5">
                <div id="convertResult" class="p-2 bg-light rounded">
                    <span class="text-muted small">Enter an amount to convert</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Live search filters --}}
<div class="row g-2 mb-4">
    <div class="col-md-7">
        <div class="input-group">
            <span class="input-group-text">🔍</span>
            <input type="text" id="liveSearchInput" class="form-control"
                   placeholder="Search startups by name or description...">
        </div>
    </div>
    <div class="col-md-3">
        <select id="liveCategorySelect" class="form-select">
            <option value="">All categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <div id="searchLoader" class="d-none d-flex align-items-center gap-2 h-100">
            <div class="spinner-border spinner-border-sm text-primary"></div>
            <small>Searching...</small>
        </div>
    </div>
</div>

{{-- Results grid — populated by JS --}}
<div class="row g-3" id="searchResultsGrid">
    <div class="col-12 text-center py-4">
        <div class="spinner-border text-primary"></div>
        <p class="text-muted mt-2">Loading startups...</p>
    </div>
</div>

@push('scripts')
<script>
    // Pass any pre-existing interested IDs to JS so the cards render correctly
    window.interestedIds = @json($interestedIds);
</script>
@endpush
@endsection