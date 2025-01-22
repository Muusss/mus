<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Alternatif;
use App\Models\SubKriteria;
use Illuminate\Database\Seeder;
use Database\Seeders\PenilaianSeeder;
use Database\Seeders\UserSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $kriteria[] = Kriteria::factory()->create([
            'kode' => 'K00001',
            'kriteria' => 'SOC',
            'bobot' => 30,
            'jenis_kriteria' => 'benefit',
        ]);
        $kriteria[] = Kriteria::factory()->create([
            'kode' => 'K00002',
            'kriteria' => 'RAM',
            'bobot' => 15,
            'jenis_kriteria' => 'benefit',
        ]);
        $kriteria[] = Kriteria::factory()->create([
            'kode' => 'K00003',
            'kriteria' => 'Storage',
            'bobot' => 20,
            'jenis_kriteria' => 'benefit',
        ]);
        $kriteria[] = Kriteria::factory()->create([
            'kode' => 'K00004',
            'kriteria' => 'Battery',
            'bobot' => 10,
            'jenis_kriteria' => 'benefit',
        ]);
        $kriteria[] = Kriteria::factory()->create([
            'kode' => 'K00005',
            'kriteria' => 'Price',
            'bobot' => 25,
            'jenis_kriteria' => 'cost',
        ]);

        $subKriteria = ['Sangat Baik', 'Baik', 'Cukup', 'Buruk', 'Sangat Buruk'];
        $subKriteriaPrice = ['Sangat Murah', 'Murah', 'Cukup', 'Mahal', 'Sangat Mahal'];
        foreach ($kriteria as $item) {
            if ($item->jenis_kriteria == 'cost') {
                SubKriteria::factory()->create([
                    'sub_kriteria' => $subKriteriaPrice[0],
                    'bobot' => 2,
                    'kriteria_id' => $item->id,
                ]);
                SubKriteria::factory()->create([
                    'sub_kriteria' => $subKriteriaPrice[1],
                    'bobot' => 4,
                    'kriteria_id' => $item->id,
                ]);
                SubKriteria::factory()->create([
                    'sub_kriteria' => $subKriteriaPrice[2],
                    'bobot' => 6,
                    'kriteria_id' => $item->id,
                ]);
                SubKriteria::factory()->create([
                    'sub_kriteria' => $subKriteriaPrice[3],
                    'bobot' => 8,
                    'kriteria_id' => $item->id,
                ]);
                SubKriteria::factory()->create([
                    'sub_kriteria' => $subKriteriaPrice[4],
                    'bobot' => 10,
                    'kriteria_id' => $item->id,
                ]);
            } else if ($item->jenis_kriteria == 'benefit') {
                SubKriteria::factory()->create([
                    'sub_kriteria' => $subKriteria[0],
                    'bobot' => 10,
                    'kriteria_id' => $item->id,
                ]);
                SubKriteria::factory()->create([
                    'sub_kriteria' => $subKriteria[1],
                    'bobot' => 8,
                    'kriteria_id' => $item->id,
                ]);
                SubKriteria::factory()->create([
                    'sub_kriteria' => $subKriteria[2],
                    'bobot' => 6,
                    'kriteria_id' => $item->id,
                ]);
                SubKriteria::factory()->create([
                    'sub_kriteria' => $subKriteria[3],
                    'bobot' => 4,
                    'kriteria_id' => $item->id,
                ]);
                SubKriteria::factory()->create([
                    'sub_kriteria' => $subKriteria[4],
                    'bobot' => 2,
                    'kriteria_id' => $item->id,
                ]);
            }
        }

        $alternatif[] = Alternatif::factory()->create([
            'kode' => 'A00001',
        ]);
        $alternatif[] = Alternatif::factory()->create([
            'kode' => 'A00002',
        ]);
        $alternatif[] = Alternatif::factory()->create([
            'kode' => 'A00003',
        ]);
        $alternatif[] = Alternatif::factory()->create([
            'kode' => 'A00004',
        ]);
        // foreach ($alternatif as $item) {
        //     foreach ($kriteria as $value) {
        //         Penilaian::create([
        //             'alternatif_id' => $item->id,
        //             'kriteria_id' => $value->id,
        //             'sub_kriteria_id' => null,
        //         ]);
        //     }
        // }

        $this->call([
            UserSeeder::class,
            PenilaianSeeder::class,
        ]);
    }
}
