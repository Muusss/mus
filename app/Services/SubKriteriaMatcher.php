<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\SubKriteria;

class SubKriteriaMatcher
{
    /**
     * Cari sub_kriteria berdasarkan KODE kriteria (C1..C6) dan nilai angka (0..100).
     * Mengembalikan SubKriteria atau null jika tidak ada yang cocok.
     */
    public static function matchNumericByKode(string $kode, float|int $value): ?SubKriteria
    {
        $kriteriaId = Kriteria::where('kode', $kode)->value('id');
        if (!$kriteriaId) return null;
        return self::matchNumeric($kriteriaId, (float)$value);
    }

    /**
     * Cari sub_kriteria by kriteria_id dan nilai angka menggunakan rentang min_val/max_val.
     */
    public static function matchNumeric(int $kriteriaId, float $value): ?SubKriteria
    {
        return SubKriteria::where('kriteria_id', $kriteriaId)
            ->where(function ($q) use ($value) {
                $q->where(function ($r) use ($value) {
                    $r->whereNotNull('min_val')->whereNotNull('max_val')
                      ->where('min_val', '<=', $value)
                      ->where('max_val', '>=', $value);
                })
                // fallback kalau ada baris tanpa min/max (jarang, tapi aman)
                ->orWhere(function ($r) use ($value) {
                    $r->whereNull('min_val')->whereNull('max_val');
                });
            })
            ->orderBy('skor') // urut 1→4
            ->first();
    }

    /**
     * Cari sub_kriteria berdasarkan label (case-insensitive, trimming).
     */
    public static function matchByLabel(int $kriteriaId, string $label): ?SubKriteria
    {
        $norm = trim(mb_strtolower($label));
        return SubKriteria::where('kriteria_id', $kriteriaId)
            ->get()
            ->first(fn($sk) => trim(mb_strtolower($sk->label)) === $norm);
    }
}
