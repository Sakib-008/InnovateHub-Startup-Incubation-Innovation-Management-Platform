<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'bio',
        'avatar',
        'linkedin',
        'expertise',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // ---- Role helpers ----
    public function isFounder(): bool
    {
        return $this->role === 'founder';
    }

    public function isMentor(): bool
    {
        return $this->role === 'mentor';
    }

    public function isInvestor(): bool
    {
        return $this->role === 'investor';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // ---- Relationships
    public function startupIdeas()
    {
        return $this->hasMany(StartupIdea::class, 'founder_id');
    }

    public function investorProfile()
    {
        return $this->hasOne(InvestorProfile::class);
    }

    public function teamMemberships()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=0D6EFD&color=fff';
    }
}