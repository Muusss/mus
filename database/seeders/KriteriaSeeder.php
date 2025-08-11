<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // Urutan_prioritas: 1 paling penting
        $rows = [
            ['kode'=>'C1','kriteria'=>'Nilai Raport Umum',   'atribut'=>'benefit','urutan_prioritas'=>1],
            ['kode'=>'C2','kriteria'=>'Nilai Raport Diniyah','atribut'=>'benefit','urutan_prioritas'=>2],
            ['kode'=>'C3','kriteria'=>'Sikap',               'atribut'=>'benefit','urutan_prioritas'=>3],
            ['kode'=>'C4','kriteria'=>"Hafalan Al-Qur'an",   'atribut'=>'benefit','urutan_prioritas'=>4],
            ['kode'=>'C5','kriteria'=>'Ekstrakurikuler',     'atribut'=>'benefit','urutan_prioritas'=>5],
            ['kode'=>'C6','kriteria'=>'Kehadiran',           'atribut'=>'benefit','urutan_prioritas'=>6],
        ];

        foreach ($rows as $r) {
            Kriteria::updateOrCreate(['kode' => $r['kode']], $r);
        }
    }
}
