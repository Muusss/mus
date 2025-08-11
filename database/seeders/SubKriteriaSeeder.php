<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kriteria;
use App\Models\SubKriteria;

class SubKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        // helper: tambahkan subkriteria per KODE kriteria
        $add = function (string $kode, array $items, bool $replace = false) {
            $kriteriaId = Kriteria::where('kode', $kode)->value('id');
            if (!$kriteriaId) return;

            if ($replace) {
                SubKriteria::where('kriteria_id', $kriteriaId)->delete();
            }

            foreach ($items as $it) {
                SubKriteria::updateOrCreate(
                    ['kriteria_id' => $kriteriaId, 'label' => $it['label']],
                    [
                        'skor'    => $it['skor'],
                        'min_val' => $it['min_val'] ?? null,
                        'max_val' => $it['max_val'] ?? null,
                    ]
                );
            }
        };

        /* C1: Nilai Raport Umum (benefit) — URUT 1→4 */
        $add('C1', [
            ['label'=>'≤ 80',  'skor'=>1, 'min_val'=>0,  'max_val'=>80],
            ['label'=>'81–85', 'skor'=>2, 'min_val'=>81, 'max_val'=>85],
            ['label'=>'86–90', 'skor'=>3, 'min_val'=>86, 'max_val'=>90],
            ['label'=>'≥ 91',  'skor'=>4, 'min_val'=>91, 'max_val'=>100],
        ], true);

        /* C2: Nilai Raport Diniyah (benefit) — URUT 1→4 */
        $add('C2', [
            ['label'=>'≤ 80',  'skor'=>1, 'min_val'=>0,  'max_val'=>80],
            ['label'=>'81–85', 'skor'=>2, 'min_val'=>81, 'max_val'=>85],
            ['label'=>'86–90', 'skor'=>3, 'min_val'=>86, 'max_val'=>90],
            ['label'=>'≥ 91',  'skor'=>4, 'min_val'=>91, 'max_val'=>100],
        ], true);

        /* C3: Sikap (benefit) — URUT 1→4 */
        $add('C3', [
            ['label'=>'Kurang',       'skor'=>1],
            ['label'=>'Cukup',        'skor'=>2],
            ['label'=>'Baik',         'skor'=>3],
            ['label'=>'Sangat Baik',  'skor'=>4],
        ], true);

        /* C4: Hafalan Al-Qur’an (benefit) — URUT 1→4 (sesuai permintaanmu) */
        $add('C4', [
            ['label'=>'1/2 Juz', 'skor'=>1],
            ['label'=>'1 Juz',   'skor'=>2],
            ['label'=>'2 Juz',   'skor'=>3],
            ['label'=>'> 2 Juz', 'skor'=>4],
        ], true);

        /* C5: Ekstrakurikuler (benefit) — URUT 1→4 */
        $add('C5', [
            ['label'=>'Tidak Aktif',                'skor'=>1],
            ['label'=>'Kurang Aktif',               'skor'=>2],
            ['label'=>'Aktif (1 kegiatan)',         'skor'=>3],
            ['label'=>'Sangat Aktif (≥2 kegiatan)', 'skor'=>4],
        ], true);

        /* C6: Kehadiran (benefit) — URUT 1→4 */
        $add('C6', [
            ['label'=>'≤ 85%',  'skor'=>1, 'min_val'=>0,  'max_val'=>85],
            ['label'=>'86–90%', 'skor'=>2, 'min_val'=>86, 'max_val'=>90],
            ['label'=>'91–95%', 'skor'=>3, 'min_val'=>91, 'max_val'=>95],
            ['label'=>'≥ 96%',  'skor'=>4, 'min_val'=>96, 'max_val'=>100],
        ], true);
    }
}
