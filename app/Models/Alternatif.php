<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatif';
    protected $fillable = [
        'kode',
        'alternatif',
        'keterangan',
    ];

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, "alternatif_id");
    }

    public function nilaiUtility()
    {
        return $this->hasMany(NilaiUtility::class, "alternatif_id");
    }

    public function nilaiAkhir()
    {
        return $this->hasMany(NilaiAkhir::class, "alternatif_id");
    }
}
