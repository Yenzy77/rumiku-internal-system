<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Buat tabel untuk menyimpan akun media sosial per proyek.
     */
    public function up(): void
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('project')->default('umum'); // creedigo, roku, kyoomi, glocult, umum
            $table->string('platform'); // instagram, tiktok, twitter, youtube, dll
            $table->string('account_name'); // Nama tampilan akun
            $table->string('account_handle'); // @handle unik
            $table->text('access_token')->nullable(); // Encrypted di model, untuk integrasi API
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi (drop table).
     */
    public function down(): void
    {
        Schema::dropIfExists('social_accounts');
    }
};
