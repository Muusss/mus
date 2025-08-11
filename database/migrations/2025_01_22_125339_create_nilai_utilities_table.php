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
        Schema::create('nilai_utility', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternatif_id')->constrained('alternatif');  // Relasi ke tabel alternatif
            $table->foreignId('kriteria_id')->constrained('kriteria');  // Relasi ke tabel kriteria
            $table->double('nilai', 15, 8);  // Tipe data double dengan presisi lebih tinggi (15 digit, 8 angka di belakang koma)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_utility');
    }
};
