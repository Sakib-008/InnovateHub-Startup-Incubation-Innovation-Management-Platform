<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StartupShowcase extends Model
{
    use HasFactory;

    protected $fillable = [
        'startup_idea_id',
        'achievements',
        'tagline',
        'website',
        'gallery_images',
        'is_public',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'is_public'      => 'boolean',
    ];

    public function startupIdea()
    {
        return $this->belongsTo(StartupIdea::class);
    }
}