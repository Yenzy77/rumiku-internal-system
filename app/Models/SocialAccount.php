<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'project',
        'platform',
        'account_name',
        'account_handle',
        'access_token',
    ];

    /**
     * Encrypt access_token secara otomatis saat simpan/baca.
     */
    protected function casts(): array
    {
        return [
            'access_token' => 'encrypted',
        ];
    }

    /**
     * Relasi ke semua post dari akun ini.
     */
    public function posts()
    {
        return $this->hasMany(SocialPost::class);
    }
}
