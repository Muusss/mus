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
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 6)->unique();  // Kode kriteria (misalnya "K00001")
            $table->string('kriteria');           // Nama kriteria (misalnya "Nilai Raport")
            $table->float('bobot');               // Bobot kriteria (sekarang bisa memiliki nilai desimal)
            $table->enum('jenis_kriteria', ['cost', 'benefit'])->default('benefit');  // Jenis kriteria (cost atau benefit)
            $table->float('normalisasi_bobot')->nullable();  // Kolom untuk menyimpan normalisasi bobot hasil perhitungan ROC
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
