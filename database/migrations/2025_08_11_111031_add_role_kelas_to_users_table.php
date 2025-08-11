<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $t) {
            if (!Schema::hasColumn('users', 'role')) {
                $t->enum('role', ['admin','wali_kelas'])
                  ->default('wali_kelas')
                  ->after('password');
            }
            if (!Schema::hasColumn('users', 'kelas')) {
                $t->enum('kelas', ['6A','6B','6C','6D'])
                  ->nullable()
                  ->after('role'); // admin = null
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $t) {
            if (Schema::hasColumn('users','kelas'))  $t->dropColumn('kelas');
            if (Schema::hasColumn('users','role'))   $t->dropColumn('role');
        });
    }
};
