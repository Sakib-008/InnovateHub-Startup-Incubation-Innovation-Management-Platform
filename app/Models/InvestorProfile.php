<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'investment_focus',
        'investment_range',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}