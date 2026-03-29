<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Field yang diizinkan untuk diisi secara massal
    protected $fillable = [
        'transaction_date',
        'type',
        'project', // Tambahkan ini
        'amount',
        'description',
        'user_id', ];

    /**
     * Relasi ke model User (Siapa yang menginput transaksi ini)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
