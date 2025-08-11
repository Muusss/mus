<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Alternatif;

class AlternatifSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['nis'=>'6001','nama_siswa'=>'Aisyah','jk'=>'Pr','kelas'=>'6A'],
            ['nis'=>'6002','nama_siswa'=>'Budi',  'jk'=>'Lk','kelas'=>'6A'],
            ['nis'=>'6003','nama_siswa'=>'Citra', 'jk'=>'Pr','kelas'=>'6B'],
            ['nis'=>'6004','nama_siswa'=>'Dimas', 'jk'=>'Lk','kelas'=>'6B'],
        ];

        foreach ($rows as $r) {
            Alternatif::updateOrCreate(['nis' => $r['nis']], $r);
        }
    }
}
