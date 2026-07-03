@props(['status'])
@php
    $classes = match($status) {
        'done'        => 'badge bg-success',
        'in_progress' => 'badge bg-warning text-dark',
        default       => 'badge bg-secondary',
    };
    $label = match($status) {
        'done'        => 'Done',
        'in_progress' => 'In Progress',
        default       => 'To Do',
    };
@endphp
<span class="{{ $classes }}">{{ $label }}</span>