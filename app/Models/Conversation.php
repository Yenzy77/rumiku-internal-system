<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = ['channel_id', 'external_contact_id', 'contact_name', 'last_message'];

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
