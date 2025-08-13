<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Dashboard';
        $user  = Auth::user();
        
        // Get filter kelas dari request
        $kelasFilter = $request->get('kelas', 'all');
        
        // Jika user adalah wali kelas, force filter ke kelasnya
        if ($user && $user->role === 'wali_kelas') {
            $kelasFilter = $user->kelas;
        }

        // Filter helper
        $filterKelas = function ($q) use ($kelasFilter) {
            if ($kelasFilter && $kelasFilter !== 'all') {
                $q->where('kelas', $kelasFilter);
            }
        };

        // === Kartu ringkas
        $jumlahSiswa = Alternatif::where(function($q) use ($kelasFilter) {
            if ($kelasFilter && $kelasFilter !== 'all') {
                $q->where('kelas', $kelasFilter);
            }
        })->count();
        
        $jumlahKriteria = Kriteria::count();
        
        $jumlahPenilaian = Penilaian::whereHas('alternatif', $filterKelas)->count();

        // === Tabel / ranking (filter per kelas)
        $nilaiAkhir = NilaiAkhir::query()
            ->with(['alternatif:id,nis,nama_siswa,kelas,jk'])
            ->whereHas('alternatif', $filterKelas)
            ->orderByDesc('total')
            ->get(['id','alternatif_id','total','peringkat']);

        // Recalculate ranking untuk kelas yang difilter
        $rank = 1;
        foreach ($nilaiAkhir as $row) {
            $row->peringkat_kelas = $rank++;
        }

        // Top 5 untuk widget cepat
        $top5 = $nilaiAkhir->take(5);

        // === Data untuk chart (labels & series)
        $chartLabels = [];
        $chartSeries = [];
        foreach ($nilaiAkhir->take(10) as $row) {
            $chartLabels[] = $row->alternatif->nama_siswa ?? ('Siswa '.$row->alternatif_id);
            $chartSeries[] = round((float) $row->total, 3);
        }

        // Get list kelas untuk dropdown
        $kelasList = ['6A', '6B', '6C', '6D'];

        return view('dashboard.index', compact(
            'title',
            'jumlahSiswa',
            'jumlahKriteria',
            'jumlahPenilaian',
            'nilaiAkhir',
            'top5',
            'chartLabels',
            'chartSeries',
            'kelasFilter',
            'kelasList'
        ));
    }

    public function hasilAkhir(Request $request)
    {
        $title = 'Hasil Akhir';
        $user = Auth::user();
        
        // Get filter kelas
        $kelasFilter = $request->get('kelas', 'all');
        
        // Force filter untuk wali kelas
        if ($user && $user->role === 'wali_kelas') {
            $kelasFilter = $user->kelas;
        }
        
        // Query dengan filter
        $nilaiAkhir = NilaiAkhir::with('alternatif')
            ->when($kelasFilter && $kelasFilter !== 'all', function($q) use ($kelasFilter) {
                $q->whereHas('alternatif', function($query) use ($kelasFilter) {
                    $query->where('kelas', $kelasFilter);
                });
            })
            ->orderByDesc('total')
            ->get();
        
        // Recalculate ranking untuk kelas yang difilter
        $rank = 1;
        foreach ($nilaiAkhir as $row) {
            $row->peringkat_kelas = $rank++;
        }
        
        $kelasList = ['6A', '6B', '6C', '6D'];

        return view('dashboard.hasil-akhir.index', compact('title', 'nilaiAkhir', 'kelasFilter', 'kelasList'));
    }
}