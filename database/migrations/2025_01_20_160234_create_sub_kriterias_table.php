<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sub_kriterias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kriteria_id')->constrained('kriterias')->cascadeOnDelete();
            $table->string('label')->nullable();
            $table->unsignedTinyInteger('skor')->nullable(); // 1..4
            $table->integer('min_val')->nullable();
            $table->integer('max_val')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_kriterias');
    }
};
