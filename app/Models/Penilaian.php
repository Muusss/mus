<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;

class Penilaian extends Model
{
    protected $table = 'penilaians';

    protected $fillable = [
        'alternatif_id',
        'kriteria_id',
        'sub_kriteria_id', // opsional
        'nilai_asli',      // 1..4
        'nilai_normal',    // 0..1
        // 'periode_id',    // aktifkan kalau kamu pakai periode
    ];

    protected $casts = [
        'nilai_asli'   => 'float',
        'nilai_normal' => 'float',
    ];

    /* ===== Relasi ===== */
    public function alternatif(): BelongsTo
    {
        return $this->belongsTo(Alternatif::class, 'alternatif_id');
    }

    public function kriteria(): BelongsTo
    {
        return $this->belongsTo(Kriteria::class, 'kriteria_id');
    }

    public function subKriteria(): BelongsTo
    {
        return $this->belongsTo(SubKriteria::class, 'sub_kriteria_id');
    }

    /* ===== Scopes ===== */
    public function scopePeriode(Builder $q, ?int $periodeId): Builder
    {
        // hindari named args, dan import Schema di atas
        if ($periodeId !== null && Schema::hasColumn($this->getTable(), 'periode_id')) {
            $q->where('periode_id', $periodeId);
        }
        return $q;
    }

    public function scopeForUser(Builder $q, ?User $user): Builder
    {
        if ($user && ($user->role ?? null) === 'wali_kelas') {
            $q->whereHas('alternatif', function (Builder $s) use ($user) {
                $s->where('kelas', $user->kelas);
            });
        }
        return $q;
    }

    public function scopeForKriteria(Builder $q, int $kriteriaId): Builder
    {
        return $q->where('kriteria_id', $kriteriaId);
    }

    /* ===== Mutator kecil untuk jaga range 1..4 ===== */
    public function setNilaiAsliAttribute($value): void
    {
        $v = (int) $value;
        $this->attributes['nilai_asli'] = max(1, min(4, $v));
    }

    /* ===== Normalisasi SMART (min-max) ===== */
    public static function normalisasiSMART(?int $periodeId = null, ?User $user = null): void
    {
        $kriterias = Kriteria::all();
        foreach ($kriterias as $kr) {
            $q = static::query()
                ->where('kriteria_id', $kr->id)
                ->periode($periodeId)
                ->forUser($user);

            $min = (clone $q)->min('nilai_asli');
            $max = (clone $q)->max('nilai_asli');

            $rows = $q->get(['id','nilai_asli']);
            if ($rows->isEmpty()) continue;

            foreach ($rows as $row) {
                if ($max == $min) {
                    $u = 1.0;
                } else {
                    $u = ($kr->atribut === 'cost')
                        ? ($max - $row->nilai_asli) / ($max - $min)
                        : ($row->nilai_asli - $min) / ($max - $min);
                }
                static::where('id', $row->id)->update(['nilai_normal' => round($u, 6)]);
            }
        }
    }
}
