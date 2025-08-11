<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiUtility extends Model
{
    protected $table = 'nilai_utility';
    protected $fillable = [
        'alternatif_id',
        'kriteria_id',
        'nilai',
    ];

    /**
     * Relasi ke model Alternatif
     */
    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class, "alternatif_id");
    }

    /**
     * Relasi ke model Kriteria
     */
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, "kriteria_id");
    }

    /**
     * Hitung nilai utilitas untuk alternatif berdasarkan kriteria (SMART)
     * @param  float $nilaiSubKriteria
     * @param  string $jenisKriteria
     * @param  float $nilaiMax
     * @param  float $nilaiMin
     * @return float
     */
    public function hitungNilaiUtilitas($nilaiSubKriteria, $jenisKriteria, $nilaiMax, $nilaiMin)
    {
        if ($jenisKriteria == 'benefit') {
            // Nilai utilitas untuk kriteria benefit (lebih tinggi lebih baik)
            return ($nilaiSubKriteria - $nilaiMin) / ($nilaiMax - $nilaiMin);
        } elseif ($jenisKriteria == 'cost') {
            // Nilai utilitas untuk kriteria cost (lebih rendah lebih baik)
            return ($nilaiMax - $nilaiSubKriteria) / ($nilaiMax - $nilaiMin);
        }

        return 0; // Mengembalikan 0 jika jenis kriteria tidak dikenali
    }

    /**
     * Simpan nilai utilitas yang telah dihitung ke dalam database
     * @param  int $alternatifId
     * @param  int $kriteriaId
     * @param  float $nilaiUtilitas
     * @return void
     */
    public static function simpanNilaiUtilitas($alternatifId, $kriteriaId, $nilaiUtilitas)
    {
        // Simpan nilai utilitas ke dalam database
        self::create([
            'alternatif_id' => $alternatifId,
            'kriteria_id' => $kriteriaId,
            'nilai' => $nilaiUtilitas
        ]);
    }
}
