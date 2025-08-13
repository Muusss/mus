<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Periode extends Model
{
    use HasFactory;

    protected $table = 'periodes';
    
    protected $fillable = [
        'nama_periode',
        'semester', 
        'tahun_ajaran',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tahun_ajaran' => 'integer'
    ];

    // Relasi
    public function penilaians()
    {
        return $this->hasMany(Penilaian::class, 'periode_id');
    }

    public function nilaiAkhirs()
    {
        return $this->hasMany(NilaiAkhir::class, 'periode_id');
    }

    // Helper method
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    public function getFullNameAttribute()
    {
        return "Semester {$this->semester} - {$this->tahun_ajaran}/" . ($this->tahun_ajaran + 1);
    }
}