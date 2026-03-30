<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskValue extends Model
{
    protected $fillable = [
        'task_id',
        'property_id',
        'value',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
