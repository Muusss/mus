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

        foreach ($alternatifs as $alt) {
            foreach ($kriterias as $kr) {
                // Contoh nilai 1..4 (boleh diganti real)
                $nilai = random_int(2, 4);

                Penilaian::updateOrCreate(
                    ['alternatif_id' => $alt->id, 'kriteria_id' => $kr->id],
                    ['nilai_asli' => $nilai] // nilai_normal dihitung setelahnya
                );
            }
        }
    }
}
