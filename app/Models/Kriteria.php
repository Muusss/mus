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

    public function subKriteria()
    {
        return $this->hasMany(SubKriteria::class, "kriteria_id");
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, "kriteria_id");
    }

    public function normalisasiBobot()
    {
        return $this->hasMany(NormalisasiBobot::class, "kriteria_id");
    }

    public function nilaiUtility()
    {
        return $this->hasMany(NilaiUtility::class, "kriteria_id");
    }

    public function nilaiAkhir()
    {
        return $this->hasMany(NilaiAkhir::class, "kriteria_id");
    }
}
