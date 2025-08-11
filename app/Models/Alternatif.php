<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Alternatif extends Model
{
    use HasFactory;

    // PAKAI TABEL PLURAL
    protected $table = 'alternatifs';

    // Field siswa
    protected $fillable = ['nis','nama_siswa','jk','kelas'];

    // Relasi
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'alternatif_id');
    }

    public function nilaiAkhir()
    {
        // kalau nanti pakai periode, ganti ke hasMany
        return $this->hasOne(NilaiAkhir::class, 'alternatif_id');
    }
}
