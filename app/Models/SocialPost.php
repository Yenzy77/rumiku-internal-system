<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_account_id',
        'user_id',
        'content_body',
        'media_path',
        'scheduled_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    /**
     * Relasi ke akun media sosial tempat post ini diterbitkan.
     */
    public function account()
    {
        return $this->belongsTo(SocialAccount::class, 'social_account_id');
    }

    /**
     * Relasi ke user yang membuat konten ini.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke metrik performa post ini.
     */
    public function metrics()
    {
        return $this->hasMany(PostMetric::class);
    }
}
