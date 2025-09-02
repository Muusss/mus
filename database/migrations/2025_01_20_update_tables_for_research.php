// database/migrations/2025_01_20_update_tables_for_research.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update tabel alternatifs sesuai penelitian
        Schema::table('alternatifs', function (Blueprint $table) {
            if (!Schema::hasColumn('alternatifs', 'nis')) {
                $table->string('nis', 30)->unique()->after('id');
            }
        });

        // Update tabel kriterias dengan urutan prioritas
        Schema::table('kriterias', function (Blueprint $table) {
            if (!Schema::hasColumn('kriterias', 'urutan_prioritas')) {
                $table->unsignedTinyInteger('urutan_prioritas')->after('atribut');
            }
            if (!Schema::hasColumn('kriterias', 'bobot_roc')) {
                $table->decimal('bobot_roc', 8, 6)->nullable()->after('urutan_prioritas');
            }
        });

        // Buat tabel setting untuk profil sekolah
        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('nama_sekolah')->default('SDIT As Sunnah Cirebon');
                $table->text('alamat')->nullable();
                $table->string('telepon')->nullable();
                $table->string('path_logo')->nullable();
                $table->string('email')->nullable();
                $table->timestamps();
            });
        }

        // Tabel periode untuk semester
        if (!Schema::hasTable('periodes')) {
            Schema::create('periodes', function (Blueprint $table) {
                $table->id();
                $table->string('nama_periode');
                $table->enum('semester', ['1', '2']);
                $table->year('tahun_ajaran');
                $table->boolean('is_active')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // Putuskan FK dari penilaians ke periodes jika masih ada
        if (Schema::hasTable('penilaians')) {
            Schema::table('penilaians', function (Blueprint $table) {
                if (Schema::hasColumn('penilaians', 'periode_id')) {
                    // Boleh pakai salah satu:
                    // $table->dropForeign('penilaians_periode_id_foreign');
                    $table->dropForeign(['periode_id']);
                }
            });
        }

        // Matikan constraint sementara sebagai “payung”
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('settings');
        Schema::dropIfExists('periodes');

        Schema::enableForeignKeyConstraints();
    }
};