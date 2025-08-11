<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NormalisasiBobot extends Model
{
    protected $table = 'normalisasi_bobot';
    protected $fillable = [
        'kriteria_id',
        'normalisasi',
    ];

    /**
     * Relasi ke model Kriteria
     */
    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class, "kriteria_id");
    }

    /**
     * Hitung normalisasi bobot berdasarkan metode ROC
     * @param  float $bobot
     * @param  float $totalBobot
     * @return float
     */
    public static function hitungNormalisasiBobot($bobot, $totalBobot)
    {
        // Normalisasi bobot berdasarkan ROC
        return $bobot / $totalBobot;
    }

    /**
     * Simpan normalisasi bobot untuk setiap kriteria
     * @return void
     */
    public static function simpanNormalisasiBobot()
    {
        $kriteria = Kriteria::all();
        $totalBobot = Kriteria::sum('bobot');  // Total bobot dari semua kriteria

        foreach ($kriteria as $item) {
            $normalisasi = self::hitungNormalisasiBobot($item->bobot, $totalBobot);

            // Simpan normalisasi bobot ke dalam tabel NormalisasiBobot
            self::create([
                'kriteria_id' => $item->id,
                'normalisasi' => $normalisasi
            ]);
        }
    }
}
