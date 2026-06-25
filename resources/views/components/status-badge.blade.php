@props(['status'])
@php
    $classes = match($status) {
        'approved' => 'badge bg-success',
        'rejected' => 'badge bg-danger',
        default    => 'badge bg-warning text-dark',
    };
@endphp
<span class="{{ $classes }}">{{ ucfirst($status) }}</span>