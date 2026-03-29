<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel untuk menyimpan konten posting media sosial.
     */
    public function up(): void
    {
        Schema::create('social_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_account_id')->constrained('social_accounts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yang membuat konten
            $table->text('content_body')->nullable(); // Caption / teks post
            $table->string('media_path')->nullable(); // Path ke file media (gambar/video)
            $table->dateTime('scheduled_at')->nullable(); // Jadwal posting
            $table->enum('status', ['draft', 'review', 'scheduled', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi (drop table).
     */
    public function down(): void
    {
        Schema::dropIfExists('social_posts');
    }
};
