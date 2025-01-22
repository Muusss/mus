<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\SubKriteria;

class DashboardController extends Controller
{
    public function index()
    {
        $title = "Dashboard";

        $jmlKriteria = Kriteria::count();
        $jmlSubKriteria = SubKriteria::count();
        $jmlAlternatif = Alternatif::count();

        $nilaiAkhir = NilaiAkhir::selectRaw('alternatif_id, SUM(nilai) as nilai')->groupBy('alternatif_id')->orderBy('alternatif_id', 'asc')->get();

        return view('dashboard/index', compact('title', 'jmlKriteria', 'jmlSubKriteria', 'jmlAlternatif', 'nilaiAkhir'));
    }

    public function hasilAkhir()
    {
        $title = "Hasil Akhir";
        $nilaiAkhir = NilaiAkhir::query()
            ->join('alternatif as a', 'a.id', '=', 'nilai_akhir.alternatif_id')
            ->selectRaw("a.kode, a.alternatif, SUM(nilai) as nilai")
            ->groupBy('a.kode', 'a.alternatif')
            ->orderBy('nilai', 'desc')
            ->get();
        return view('dashboard.hasil-akhir.index', compact('title', 'nilaiAkhir'));
    }
}
