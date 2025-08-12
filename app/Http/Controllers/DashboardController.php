<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;

class DashboardController extends Controller
{
    public function index()
    {
        $title = 'Dashboard';
        $user  = Auth::user();

        // Filter helper: wali_kelas hanya kelasnya
        $filterKelas = function ($q) use ($user) {
            if ($user && ($user->role ?? null) === 'wali_kelas') {
                $q->where('kelas', $user->kelas);
            }
        };

        // === Kartu ringkas
        $jumlahSiswa = Alternatif::where($filterKelas)->count();
        $jumlahKriteria = Kriteria::count();
        $jumlahPenilaian = Penilaian::whereHas('alternatif', $filterKelas)->count();

        // === Tabel / ranking (sudah 1 baris per siswa, kolom TOTAL)
        $nilaiAkhir = NilaiAkhir::query()
            ->with(['alternatif:id,nis,nama_siswa,kelas'])
            ->whereHas('alternatif', $filterKelas)
            ->orderByDesc('total')
            ->get(['id','alternatif_id','total','peringkat']);

        // Top 5 untuk widget cepat (opsional)
        $top5 = NilaiAkhir::query()
            ->with(['alternatif:id,nis,nama_siswa,kelas'])
            ->whereHas('alternatif', $filterKelas)
            ->orderByDesc('total')
            ->limit(5)
            ->get(['id','alternatif_id','total','peringkat']);

        // === Data untuk chart (labels & series)
        // Hindari referensi ke kolom `nilai` —> pakai `total`
        $chartLabels = [];
        $chartSeries = [];
        foreach ($nilaiAkhir as $row) {
            $chartLabels[] = $row->alternatif->nama_siswa ?? ('Siswa '.$row->alternatif_id);
            $chartSeries[] = round((float) $row->total, 3);
        }

        return view('dashboard.index', compact(
            'title',
            'jumlahSiswa',
            'jumlahKriteria',
            'jumlahPenilaian',
            'nilaiAkhir',
            'top5',
            'chartLabels',
            'chartSeries'
        ));
    }
}
