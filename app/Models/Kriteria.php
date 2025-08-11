<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriteria';
    protected $fillable = [
        'kode',
        'kriteria',
        'bobot',
        'jenis_kriteria',
    ];

    /**
     * Relasi ke SubKriteria
     */
    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class, "kriteria_id");
    }

    /**
     * Relasi ke Penilaian
     */
    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, "kriteria_id");
    }

    /**
     * Relasi ke NormalisasiBobot
     */
    public function normalisasiBobot()
    {
        return $this->hasMany(NormalisasiBobot::class, "kriteria_id");
    }

    /**
     * Relasi ke NilaiUtility
     */
    public function nilaiUtility()
    {
        return $this->hasMany(NilaiUtility::class, "kriteria_id");
    }

    /**
     * Relasi ke NilaiAkhir
     */
    public function nilaiAkhir()
    {
        return $this->hasMany(NilaiAkhir::class, "kriteria_id");
    }

    /**
     * Hitung normalisasi bobot berdasarkan ROC
     * @return void
     */
    public function hitungNormalisasiBobot()
    {
        $totalBobot = Kriteria::sum('bobot');
        $normalisasi = $this->bobot / $totalBobot;

        // Simpan normalisasi bobot ke dalam tabel NormalisasiBobot
        NormalisasiBobot::create([
            'kriteria_id' => $this->id,
            'normalisasi' => $normalisasi
        ]);
    }

    /**
     * Hitung nilai utilitas berdasarkan SMART
     * @param  float $nilaiSubKriteria
     * @param  string $jenisKriteria
     * @param  float $nilaiMax
     * @param  float $nilaiMin
     * @return float
     */
    public function hitungNilaiUtilitas($nilaiSubKriteria, $jenisKriteria, $nilaiMax, $nilaiMin)
    {
        if ($jenisKriteria == 'benefit') {
            // Nilai utilitas untuk kriteria benefit
            return ($nilaiSubKriteria - $nilaiMin) / ($nilaiMax - $nilaiMin);
        } elseif ($jenisKriteria == 'cost') {
            // Nilai utilitas untuk kriteria cost
            return ($nilaiMax - $nilaiSubKriteria) / ($nilaiMax - $nilaiMin);
        }

        return 0;
    }

    /**
     * Hitung nilai akhir berdasarkan nilai utilitas dan normalisasi bobot
     * @param  int $nilaiUtility
     * @param  float $normalisasiBobot
     * @return float
     */
    public function hitungNilaiAkhir($nilaiUtility, $normalisasiBobot)
    {
        // Hitung nilai akhir dengan mengalikan nilai utilitas dan normalisasi bobot
        return $nilaiUtility * $normalisasiBobot;
    }
}
