<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'created_by',
    ];

    /**
     * All dynamic property values for this task.
     */
    public function values()
    {
        return $this->hasMany(TaskValue::class);
    }

    /**
     * The user who created this task.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get a specific property value by slug.
     */
    public function getValue(string $slug): ?string
    {
        $taskValue = $this->values->first(function ($tv) use ($slug) {
            return $tv->property && $tv->property->slug === $slug;
        });

        return $taskValue?->value;
    }

    /**
     * Set a property value by slug.
     */
    public function setValue(string $slug, ?string $value): void
    {
        $property = Property::where('slug', $slug)->first();
        if (!$property) return;

        $this->values()->updateOrCreate(
            ['property_id' => $property->id],
            ['value' => $value]
        );
    }

    /**
     * Backward-compatible accessor: get status from task_values.
     */
    public function getStatusAttribute(): ?string
    {
        return $this->getValue('status');
    }

    /**
     * Backward-compatible accessor: get assignee user from task_values.
     */
    public function getAssigneeAttribute(): ?User
    {
        $userId = $this->getValue('assignee');
        if (!$userId) return null;
        return User::find($userId);
    }

    /**
     * Backward-compatible accessor: get due_date from task_values.
     */
    public function getDueDateAttribute(): ?string
    {
        return $this->getValue('due_date');
    }

    /**
     * Backward-compatible accessor: get description from task_values.
     */
    public function getDescriptionAttribute(): ?string
    {
        return $this->getValue('description');
    }
}
