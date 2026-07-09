<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentInterest extends Model
{
    use HasFactory;

    protected $fillable = [
        'investor_id',
        'startup_idea_id',
        'message',
        'status',
    ];

    public function investor()
    {
        return $this->belongsTo(User::class, 'investor_id');
    }

    public function startupIdea()
    {
        return $this->belongsTo(StartupIdea::class);
    }

    public function isPending(): bool   { return $this->status === 'pending'; }
    public function isContacted(): bool { return $this->status === 'contacted'; }
    public function isDeclined(): bool  { return $this->status === 'declined'; }
}