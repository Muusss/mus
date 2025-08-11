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
        Schema::create('sub_kriteria', function (Blueprint $table) {
            $table->id();
            $table->string('sub_kriteria');  // Nama subkriteria (misalnya: "Nilai Akhlak")
            $table->float('bobot', 8, 2);    // Kolom bobot diubah menjadi float dengan presisi 8 dan skala 2
            $table->float('nilai', 8, 2);    // Kolom nilai untuk perhitungan SMART, dengan presisi 8 dan skala 2
            $table->foreignId('kriteria_id')->constrained('kriteria');  // Relasi ke tabel kriteria
            $table->timestamps();  // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_kriteria');
    }
};
