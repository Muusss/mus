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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternatif_id')->constrained('alternatif');  // Relasi ke tabel alternatif
            $table->foreignId('kriteria_id')->constrained('kriteria');  // Relasi ke tabel kriteria
            $table->foreignId('sub_kriteria_id')->nullable()->constrained('sub_kriteria');  // Relasi ke sub_kriteria
            $table->float('nilai_akhir')->nullable();  // Menambahkan kolom untuk menyimpan nilai akhir per alternatif
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
