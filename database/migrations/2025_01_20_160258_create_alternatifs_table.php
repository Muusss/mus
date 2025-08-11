<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('alternatifs', function (Blueprint $t) {
            $t->id();
            $t->string('nis',30)->unique();
            $t->string('nama_siswa',100);
            $t->enum('jk',['Lk','Pr']);
            $t->enum('kelas',['6A','6B','6C','6D']);
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternatifs');
    }
};
