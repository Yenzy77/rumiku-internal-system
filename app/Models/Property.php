<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Property extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'type',
        'icon',
        'options',
        'sort_order',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'is_default' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Auto-generate slug from name if not provided.
     */
    protected static function booted(): void
    {
        static::creating(function (Property $property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->name, '_');
            }
        });
    }

    public function taskValues()
    {
        return $this->hasMany(TaskValue::class);
    }

    /**
     * Get the list of supported property types.
     */
    public static function supportedTypes(): array
    {
        return [
            'text'         => 'Teks',
            'number'       => 'Angka',
            'select'       => 'Pilih',
            'multi_select' => 'Multipilih',
            'status'       => 'Status',
            'date'         => 'Tanggal',
            'person'       => 'Orang',
            'checkbox'     => 'Kotak Centang',
            'url'          => 'URL',
        ];
    }
}
