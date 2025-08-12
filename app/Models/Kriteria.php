<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriterias';
    
    protected $fillable = [
        'kode',
        'kriteria', 
        'atribut',
        'urutan_prioritas',
        'bobot_roc'
    ];

    protected $casts = [
        'bobot_roc' => 'float',
        'urutan_prioritas' => 'integer'
    ];

    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class, 'kriteria_id');
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'kriteria_id');
    }

    /**
     * Hitung bobot ROC berdasarkan urutan prioritas
     * Sesuai dengan rumus di penelitian
     */
    public static function hitungROC(): void
    {
        $kriterias = static::orderBy('urutan_prioritas', 'asc')->get();
        $m = $kriterias->count();
        
        if ($m === 0) return;

        foreach ($kriterias as $index => $kriteria) {
            $rank = $index + 1;
            $sum = 0;
            
            // Hitung sesuai rumus ROC: Wm = (1/m) * Σ(1/i) dari i=rank sampai m
            for ($i = $rank; $i <= $m; $i++) {
                $sum += 1 / $i;
            }
            
            $bobot = $sum / $m;
            $kriteria->bobot_roc = $bobot;
            $kriteria->save();
        }

        // Normalisasi agar total = 1
        $total = static::sum('bobot_roc');
        if ($total > 0) {
            foreach (static::all() as $kriteria) {
                $kriteria->bobot_roc = $kriteria->bobot_roc / $total;
                $kriteria->save();
            }
        }
    }
}
