<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_idea_id',
        'name',
        'description',
    ];

    public function startupIdea()
    {
        return $this->belongsTo(StartupIdea::class);
    }

    public function members()
    {
        return $this->hasMany(TeamMember::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'team_members')
                    ->withPivot('role_in_team')
                    ->withTimestamps();
    }
}