@props(['status'])
@php
    $classes = match($status) {
        'completed'   => 'badge bg-success',
        'in_progress' => 'badge bg-primary',
        default       => 'badge bg-secondary',
    };
    $label = match($status) {
        'completed'   => 'Completed',
        'in_progress' => 'In Progress',
        default       => 'Not Started',
    };
@endphp
<span class="{{ $classes }}">{{ $label }}</span>