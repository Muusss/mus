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
        Schema::create('periodes', function (Blueprint $t) {
            $t->id();
            $t->string('nama_periode');          // contoh: "Semester Ganjil 2025/2026"
            $t->unsignedTinyInteger('semester'); // 1=Ganjil, 2=Genap
            $t->unsignedSmallInteger('tahun_ajaran'); // 2025, dst
            $t->boolean('is_active')->default(false);
            $t->timestamps();

            $t->unique(['semester','tahun_ajaran']); // opsional
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodes');
    }
};
