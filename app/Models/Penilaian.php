<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';
    protected $fillable = [
        'alternatif_id',
        'kriteria_id',
        'sub_kriteria_id',
        'nilai_akhir',  // Menambahkan kolom untuk menyimpan nilai akhir
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
     * Relasi ke model SubKriteria
     */
    public function subKriteria()
    {
        return $this->belongsTo(SubKriteria::class, "sub_kriteria_id");
    }

    /**
     * Hitung nilai akhir berdasarkan nilai utilitas dan normalisasi bobot
     * @return void
     */
    public function hitungNilaiAkhir()
    {
        // Ambil nilai utilitas berdasarkan perhitungan SMART
        $nilaiUtility = NilaiUtility::where('kriteria_id', $this->kriteria_id)
                                    ->where('alternatif_id', $this->alternatif_id)
                                    ->first()->nilai;

        // Ambil normalisasi bobot kriteria yang telah dihitung dengan metode ROC
        $normalisasiBobot = NormalisasiBobot::where('kriteria_id', $this->kriteria_id)
                                           ->first()->normalisasi;

        // Hitung nilai akhir dengan mengalikan nilai utilitas dan normalisasi bobot
        $nilaiAkhir = $nilaiUtility * $normalisasiBobot;

        // Simpan nilai akhir ke dalam kolom nilai_akhir
        $this->update(['nilai_akhir' => $nilaiAkhir]);
    }
}
