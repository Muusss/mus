<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Periode;

class PeriodeSeeder extends Seeder
{
    public function run(): void
    {
        $tahun = date('Y');
        
        // Semester Ganjil tahun ini
        Periode::create([
            'nama_periode' => "Semester Ganjil {$tahun}/" . ($tahun + 1),
            'semester' => '1',
            'tahun_ajaran' => $tahun,
            'is_active' => true // Set sebagai aktif
        ]);
        
        // Semester Genap tahun ini
        Periode::create([
            'nama_periode' => "Semester Genap {$tahun}/" . ($tahun + 1),
            'semester' => '2',
            'tahun_ajaran' => $tahun,
            'is_active' => false
        ]);
    }
}