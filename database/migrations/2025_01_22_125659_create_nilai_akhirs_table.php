<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nilai_akhirs', function (Blueprint $t) {
            $t->id();
            $t->foreignId('alternatif_id')->constrained('alternatifs')->cascadeOnDelete();
            $t->decimal('total', 10, 6)->default(0);
            $t->unsignedInteger('peringkat')->nullable();
            $t->unsignedBigInteger('periode_id')->nullable()->index();
            $t->unique(['alternatif_id','periode_id']);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_akhirs');
    }
};
