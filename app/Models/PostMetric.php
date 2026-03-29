<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_post_id',
        'reach',
        'engagement',
        'impressions',
        'date_recorded',
    ];

    protected function casts(): array
    {
        return [
            'date_recorded' => 'date',
        ];
    }

    /**
     * Relasi ke post yang memiliki metrik ini.
     */
    public function post()
    {
        return $this->belongsTo(SocialPost::class, 'social_post_id');
    }
}
