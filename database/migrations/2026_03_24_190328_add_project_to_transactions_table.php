<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Menambahkan kolom project setelah kolom type
            $table->string('project')->default('umum')->after('type');
        });
    }
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('project');
        });
    }
};
