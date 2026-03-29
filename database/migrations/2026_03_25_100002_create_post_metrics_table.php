<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel untuk menyimpan metrik performa setiap post.
     */
    public function up(): void
    {
        Schema::create('post_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_post_id')->constrained('social_posts')->onDelete('cascade');
            $table->unsignedInteger('reach')->default(0); // Jumlah reach
            $table->unsignedInteger('engagement')->default(0); // Jumlah engagement (likes, comments, shares)
            $table->unsignedInteger('impressions')->default(0); // Jumlah impressions
            $table->date('date_recorded'); // Tanggal pencatatan metrik
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi (drop table).
     */
    public function down(): void
    {
        Schema::dropIfExists('post_metrics');
    }
};
