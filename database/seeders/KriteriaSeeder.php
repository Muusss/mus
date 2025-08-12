<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // Data kriteria sesuai penelitian
        $kriterias = [
            [
                'kode' => 'C1',
                'kriteria' => 'Nilai Raport Umum',
                'atribut' => 'benefit',
                'urutan_prioritas' => 1
            ],
            [
                'kode' => 'C2',
                'kriteria' => 'Nilai Raport Diniyah',
                'atribut' => 'benefit',
                'urutan_prioritas' => 2
            ],
            [
                'kode' => 'C3',
                'kriteria' => 'Akhlak',
                'atribut' => 'benefit',
                'urutan_prioritas' => 3
            ],
            [
                'kode' => 'C4',
                'kriteria' => 'Hafalan Al-Quran',
                'atribut' => 'benefit',
                'urutan_prioritas' => 4
            ],
            [
                'kode' => 'C5',
                'kriteria' => 'Kehadiran',
                'atribut' => 'benefit',
                'urutan_prioritas' => 5
            ],
            [
                'kode' => 'C6',
                'kriteria' => 'Ekstrakurikuler',
                'atribut' => 'benefit',
                'urutan_prioritas' => 6
            ]
        ];

        foreach ($kriterias as $kriteria) {
            Kriteria::updateOrCreate(
                ['kode' => $kriteria['kode']],
                $kriteria
            );
        }

        // Hitung bobot ROC
        Kriteria::hitungROC();
    }
}