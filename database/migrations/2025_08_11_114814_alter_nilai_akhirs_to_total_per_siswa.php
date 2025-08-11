<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Standarkan nama tabel ke plural
        if (!Schema::hasTable('nilai_akhirs') && Schema::hasTable('nilai_akhir')) {
            Schema::rename('nilai_akhir', 'nilai_akhirs');
        }

        // Jika tabel belum ada sama sekali, buat baru dengan struktur final
        if (!Schema::hasTable('nilai_akhirs')) {
            Schema::create('nilai_akhirs', function (Blueprint $t) {
                $t->id();
                $t->foreignId('alternatif_id')->constrained('alternatifs')->cascadeOnDelete();
                $t->decimal('total', 10, 6)->default(0);
                $t->unsignedInteger('peringkat')->nullable();
                $t->unsignedBigInteger('periode_id')->nullable()->index();
                $t->unique(['alternatif_id','periode_id']);
                $t->timestamps();
            });
            return;
        }

        // Jika tabel sudah ada: rapikan kolom-kolomnya
        Schema::table('nilai_akhirs', function (Blueprint $t) {
            // Buang sisa struktur lama (per-kriteria) jika masih ada
            if (Schema::hasColumn('nilai_akhirs','kriteria_id')) {
                try { $t->dropForeign(['kriteria_id']); } catch (\Throwable $e) {}
                $t->dropColumn('kriteria_id');
            }
            if (Schema::hasColumn('nilai_akhirs','nilai')) {
                $t->dropColumn('nilai');
            }

            // Pastikan struktur final tersedia
            if (!Schema::hasColumn('nilai_akhirs','alternatif_id')) {
                $t->foreignId('alternatif_id')->after('id')->constrained('alternatifs')->cascadeOnDelete();
            }
            if (!Schema::hasColumn('nilai_akhirs','total')) {
                $t->decimal('total', 10, 6)->default(0)->after('alternatif_id');
            }
            if (!Schema::hasColumn('nilai_akhirs','peringkat')) {
                $t->unsignedInteger('peringkat')->nullable()->after('total');
            }
            if (!Schema::hasColumn('nilai_akhirs','periode_id')) {
                $t->unsignedBigInteger('periode_id')->nullable()->index()->after('peringkat');
            }

            // Unique (alternatif_id, periode_id) — coba tambah, abaikan jika sudah ada
            try {
                $t->unique(['alternatif_id','periode_id'], 'nilai_akhirs_alt_periode_unique');
            } catch (\Throwable $e) {}
        });
    }

    public function down(): void
    {
        // Tidak wajib rollback penuh—biarkan aman
        // Jika ingin, bisa drop unique:
        // Schema::table('nilai_akhirs', function (Blueprint $t) {
        //     try { $t->dropUnique('nilai_akhirs_alt_periode_unique'); } catch (\Throwable $e) {}
        // });
    }
};
