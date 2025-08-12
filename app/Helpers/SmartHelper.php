<?php

namespace App\Helpers;

use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\Alternatif;
use App\Models\NilaiAkhir;

class SmartHelper
{
    /**
     * Normalisasi nilai utilitas SMART
     */
    public static function hitungNilaiUtilitas($nilai, $min, $max, $atribut = 'benefit')
    {
        if ($max == $min) return 1;
        
        if ($atribut == 'benefit') {
            return ($nilai - $min) / ($max - $min);
        } else {
            return ($max - $nilai) / ($max - $min);
        }
    }

    /**
     * Hitung nilai akhir dengan ROC + SMART
     */
    public static function hitungNilaiAkhir($alternatifId, $periodeId = null)
    {
        $kriterias = Kriteria::all();
        $totalNilai = 0;

        foreach ($kriterias as $kriteria) {
            $penilaian = Penilaian::where('alternatif_id', $alternatifId)
                                  ->where('kriteria_id', $kriteria->id)
                                  ->first();
            
            if ($penilaian && $penilaian->nilai_normal) {
                // Nilai utilitas x Bobot ROC
                $nilaiKriteria = $penilaian->nilai_normal * $kriteria->bobot_roc;
                $totalNilai += $nilaiKriteria;
            }
        }

        return $totalNilai;
    }

    /**
     * Generate ranking siswa
     */
    public static function generateRanking($kelasFilter = null)
    {
        $query = NilaiAkhir::with('alternatif');
        
        if ($kelasFilter) {
            $query->whereHas('alternatif', function($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            });
        }

        $results = $query->orderByDesc('total')->get();
        
        $rank = 1;
        foreach ($results as $result) {
            $result->peringkat = $rank++;
            $result->save();
        }

        return $results;
    }
}