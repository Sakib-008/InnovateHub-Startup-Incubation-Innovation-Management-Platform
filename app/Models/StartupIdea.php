<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartupIdea extends Model
{
    use HasFactory;

    protected $fillable = [
        'founder_id',
        'title',
        'description',
        'category',
        'status',
        'pitch_file',
        'rejection_reason',
    ];

    // ---- Relationships ----
    public function founder()
    {
        return $this->belongsTo(User::class, 'founder_id');
    }

    public function team()
    {
        return $this->hasOne(Team::class);
    }

    // ---- Scopes ----
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // ---- Helpers ----
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getPitchFileUrlAttribute(): ?string
    {
        return $this->pitch_file
            ? asset('storage/' . $this->pitch_file)
            : null;
    }

    public function mentorshipRequests()
    {
        return $this->hasMany(MentorshipRequest::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function showcase()
    {
        return $this->hasOne(StartupShowcase::class);
    }

    public function investmentInterests()
    {
        return $this->hasMany(InvestmentInterest::class);
    }
}