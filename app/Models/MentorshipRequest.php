<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorshipRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_idea_id',
        'founder_id',
        'mentor_id',
        'status',
        'message',
        'rejection_reason',
    ];

    public function startupIdea()
    {
        return $this->belongsTo(StartupIdea::class);
    }

    public function founder()
    {
        return $this->belongsTo(User::class, 'founder_id');
    }

    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id');
    }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isAccepted(): bool { return $this->status === 'accepted'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}