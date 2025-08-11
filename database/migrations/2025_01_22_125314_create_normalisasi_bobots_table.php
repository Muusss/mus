<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('normalisasi_bobot', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id')->constrained('kriteria');  // Relasi ke tabel kriteria
            $table->double('normalisasi', 15, 8);  // Menambahkan presisi untuk normalisasi bobot (15 digit, 8 angka di belakang koma)
            $table->timestamps();  // Menambahkan kolom timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('normalisasi_bobot');
    }
};
