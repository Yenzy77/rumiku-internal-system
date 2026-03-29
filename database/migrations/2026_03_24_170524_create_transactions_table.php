<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Jalankan migrasi untuk membuat tabel transaksi.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date'); // Tanggal transaksi
            // capital = modal, operational_expense = biaya operasional, revenue = pendapatan
            $table->enum('type', ['capital', 'operational_expense', 'revenue']);
            $table->decimal('amount', 15, 2); // Nilai uang (maksimal 999 triliun dengan 2 desimal)
            $table->text('description')->nullable(); // Keterangan tambahan
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang mencatat
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi (drop table).
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};