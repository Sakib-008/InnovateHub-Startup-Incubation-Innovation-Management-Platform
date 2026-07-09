<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'title',
        'description',
        'location',
        'event_date',
        'banner_image',
        'max_attendees',
        'status',
    ];

    protected $casts = [
        'event_date' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_registrations')
                    ->withPivot('attended')
                    ->withTimestamps();
    }

    public function isRegistered(): bool
    {
        return $this->registrations()
                    ->where('user_id', auth()->id())
                    ->exists();
    }

    public function isFull(): bool
    {
        if (! $this->max_attendees) return false;
        return $this->registrations()->count() >= $this->max_attendees;
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner_image
            ? asset('storage/' . $this->banner_image)
            : null;
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now())->orderBy('event_date');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }
}