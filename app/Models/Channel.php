<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    protected $fillable = ['project', 'platform', 'provider_id', 'status'];

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }
}
