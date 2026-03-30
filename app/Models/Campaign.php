<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'type',
        'content_body',
        'segment_filters',
        'status',
        'scheduled_at',
    ];

    protected $casts = [
        'segment_filters' => 'array',
        'scheduled_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
