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
        $this->command->info('🌱 Memulai seeding database...');
        
        // Urutan penting - jangan diubah
        $this->call([
            UserSeeder::class,
            PeriodeSeeder::class,
            KriteriaSeeder::class,
            AlternatifSeeder::class,    // 40 siswa (10 per kelas)
            SubKriteriaSeeder::class,
            PenilaianSeeder::class,
        ]);

        $this->command->info('📊 Menghitung ROC + SMART...');
        
        // Setelah data ada, langsung proses ROC + SMART + Ranking
        Kriteria::hitungROC();
        Penilaian::normalisasiSMART();
        NilaiAkhir::hitungTotal();
        
        $this->command->info('✅ Seeding selesai! Database siap digunakan.');
        
        // Tampilkan informasi login
        $this->command->table(
            ['Role', 'Email', 'Password'],
            [
                ['Admin', 'admin@assunnah.sch.id', 'admin123'],
                ['Wali 6A', 'wali6a@assunnah.sch.id', 'wali123'],
                ['Wali 6B', 'wali6b@assunnah.sch.id', 'wali123'],
                ['Wali 6C', 'wali6c@assunnah.sch.id', 'wali123'],
                ['Wali 6D', 'wali6d@assunnah.sch.id', 'wali123'],
            ]
        );
    }
}