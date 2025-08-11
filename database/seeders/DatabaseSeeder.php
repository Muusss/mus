<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan penting
        $this->call([
            UserSeeder::class,
            KriteriaSeeder::class,
            AlternatifSeeder::class,
            SubKriteriaSeeder::class,
            PenilaianSeeder::class,
        ]);

        // Setelah data ada, langsung proses ROC + SMART + Ranking
        Kriteria::hitungROC();
        Penilaian::normalisasiSMART();      // periode & user opsional
        NilaiAkhir::hitungTotal();          // periode & user opsional
    }
}
