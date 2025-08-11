<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penilaians', function (Blueprint $t) {
            $t->id();

            $t->foreignId('alternatif_id')->constrained('alternatifs')->cascadeOnDelete();
            $t->foreignId('kriteria_id')->constrained('kriterias')->cascadeOnDelete();
            $t->foreignId('sub_kriteria_id')->nullable()
              ->constrained('sub_kriterias')->nullOnDelete();

            $t->unsignedTinyInteger('nilai_asli')->default(1);
            $t->decimal('nilai_normal', 8, 6)->nullable();

            // $t->unsignedBigInteger('periode_id')->nullable()->index(); // opsional
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaians');
    }
};
