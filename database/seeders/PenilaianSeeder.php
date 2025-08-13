<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;

class PenilaianSeeder extends Seeder
{
    public function run(): void
    {
        $alternatifs = Alternatif::all();
        $kriterias   = Kriteria::all();

        if ($alternatifs->isEmpty() || $kriterias->isEmpty()) return;

        // Array bobot nilai untuk variasi yang lebih realistis
        // Siswa teladan cenderung nilai 3-4, siswa biasa 2-3, siswa kurang 1-2
        $nilaiVariasi = [
            // Top performers (10%)
            'top' => [3, 4, 4, 4, 3, 4],
            // Good performers (30%)
            'good' => [3, 3, 4, 3, 3, 3],
            // Average performers (40%)
            'average' => [2, 3, 3, 2, 3, 2],
            // Below average (20%)
            'below' => [2, 2, 2, 1, 2, 2],
        ];

        foreach ($alternatifs as $index => $alt) {
            // Tentukan kategori siswa berdasarkan index
            if ($index < 4) {
                // Top 10% (4 siswa)
                $kategori = 'top';
            } elseif ($index < 16) {
                // Good 30% (12 siswa)
                $kategori = 'good';
            } elseif ($index < 32) {
                // Average 40% (16 siswa)
                $kategori = 'average';
            } else {
                // Below 20% (8 siswa)
                $kategori = 'below';
            }

            $nilaiKategori = $nilaiVariasi[$kategori];
            
            foreach ($kriterias as $kritIndex => $kr) {
                // Ambil nilai dari template kategori
                $nilaiDasar = $nilaiKategori[$kritIndex] ?? 2;
                
                // Tambah sedikit randomisasi (+/- 1, tapi tetap dalam range 1-4)
                $randomizer = rand(-1, 1);
                $nilai = max(1, min(4, $nilaiDasar + $randomizer));
                
                // Untuk kriteria tertentu, beri bobot khusus
                // Misalnya untuk nilai raport (C1 dan C2), top performer selalu bagus
                if ($kategori === 'top' && in_array($kr->kode, ['C1', 'C2'])) {
                    $nilai = 4;
                }
                
                // Untuk kehadiran (C5), most students should have good attendance
                if ($kr->kode === 'C5' && $kategori !== 'below') {
                    $nilai = max(3, $nilai);
                }

                Penilaian::updateOrCreate(
                    [
                        'alternatif_id' => $alt->id, 
                        'kriteria_id' => $kr->id
                    ],
                    [
                        'nilai_asli' => $nilai,
                        'nilai_normal' => null // akan dihitung saat normalisasi
                    ]
                );
            }
        }

        // Buat beberapa siswa unggulan yang konsisten tinggi nilainya
        // Misalnya siswa dengan NIS 2024001, 2024011, 2024021, 2024031 (1 per kelas)
        $siswaUnggulan = ['2024001', '2024011', '2024021', '2024031'];
        
        foreach ($siswaUnggulan as $nis) {
            $siswa = Alternatif::where('nis', $nis)->first();
            if ($siswa) {
                foreach ($kriterias as $kr) {
                    // Nilai tinggi untuk siswa unggulan
                    $nilai = ($kr->kode === 'C6') ? rand(3, 4) : 4; // Ekstrakurikuler bisa 3 atau 4
                    
                    Penilaian::updateOrCreate(
                        [
                            'alternatif_id' => $siswa->id,
                            'kriteria_id' => $kr->id
                        ],
                        ['nilai_asli' => $nilai]
                    );
                }
            }
        }
    }
}