<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class NilaiAkhir extends Model
{
    protected $table = 'nilai_akhirs';

    protected $fillable = ['alternatif_id','total','peringkat','periode_id'];

    protected $casts = [
        'total' => 'float',
        'peringkat' => 'integer',
        'periode_id' => 'integer',
    ];

    public function alternatif()
    {
        return $this->belongsTo(Alternatif::class,'alternatif_id');
    }
    
    public function periode()
    {
        return $this->belongsTo(Periode::class,'periode_id');
    }

    // Scopes
    public function scopePeriode(Builder $q, ?int $periodeId): Builder
    {
        if ($periodeId !== null) {
            $q->where('periode_id', $periodeId);
        }
        return $q;
    }

    public function scopeForUser(Builder $q, ?User $user): Builder
    {
        if ($user && ($user->role ?? null) === 'wali_kelas') {
            $q->whereHas('alternatif', fn(Builder $s) => $s->where('kelas', $user->kelas));
        }
        return $q;
    }

    // Hitung total & ranking dengan periode
    public static function hitungTotal(?int $periodeId = null, ?User $user = null): void
    {
        // Gunakan periode aktif jika tidak ada yang dipilih
        if ($periodeId === null) {
            $aktivePeriode = Periode::getActive();
            $periodeId = $aktivePeriode ? $aktivePeriode->id : null;
        }
        
        if (!$periodeId) {
            throw new \Exception('Periode harus dipilih atau diaktifkan');
        }

        $bobot = Kriteria::pluck('bobot_roc','id');

        $penQ = Penilaian::query()->where('periode_id', $periodeId);
        
        if ($user && ($user->role ?? null) === 'wali_kelas') {
            $penQ->whereHas('alternatif', fn(Builder $s) => $s->where('kelas', $user->kelas));
        }

        $altIds = $penQ->select('alternatif_id')->distinct()->pluck('alternatif_id');
        if ($altIds->isEmpty()) return;

        DB::transaction(function () use ($altIds, $bobot, $periodeId) {
            // Delete existing nilai akhir untuk periode ini
            static::query()
                ->whereIn('alternatif_id', $altIds)
                ->where('periode_id', $periodeId)
                ->delete();

            foreach ($altIds as $altId) {
                $items = Penilaian::where('alternatif_id', $altId)
                    ->where('periode_id', $periodeId)
                    ->get(['kriteria_id','nilai_normal']);

                $total = 0.0;
                foreach ($items as $it) {
                    $total += (float)($bobot[$it->kriteria_id] ?? 0) * (float)($it->nilai_normal ?? 0);
                }

                static::create([
                    'alternatif_id' => $altId,
                    'total' => round($total, 6),
                    'periode_id' => $periodeId
                ]);
            }

            // Update ranking untuk periode ini
            $ranked = static::query()
                ->whereIn('alternatif_id', $altIds)
                ->where('periode_id', $periodeId)
                ->orderByDesc('total')
                ->orderBy('alternatif_id')
                ->get();
                
            $rank = 1;
            foreach ($ranked as $row) {
                $row->peringkat = $rank++;
                $row->save();
            }
        });
    }
}