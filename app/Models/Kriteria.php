<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriterias';

    protected $fillable = ['kode','kriteria','atribut','urutan_prioritas','bobot_roc']; // atribut: benefit|cost

    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class, 'kriteria_id');
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'kriteria_id');
    }

    // Hitung bobot ROC dari urutan_prioritas (1 paling penting)
    public static function hitungROC(): void
    {
        $rows = static::orderBy('urutan_prioritas')->get();
        $m = $rows->count();
        if ($m === 0) return;

        foreach ($rows as $idx => $kr) {
            $rank = $idx + 1; // 1..m
            $sum = 0;
            for ($i = $rank; $i <= $m; $i++) $sum += 1 / $i;
            $kr->bobot_roc = $sum / $m;
            $kr->save();
        }

        // normalisasi agar total tepat 1
        $total = static::sum('bobot_roc');
        if ($total > 0) {
            foreach (static::all() as $kr) {
                $kr->bobot_roc = $kr->bobot_roc / $total;
                $kr->save();
            }
        }
    }
}
