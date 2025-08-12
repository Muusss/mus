<?php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\Penilaian;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function pdf_hasil()
    {
        $judul = 'Laporan Hasil Akhir';
        $user = Auth::user();
        
        // Filter berdasarkan role
        $filterKelas = function($q) use ($user) {
            if ($user && $user->role === 'wali_kelas') {
                $q->where('kelas', $user->kelas);
            }
        };
        
        // Data penilaian dengan filter
        $tabelPenilaian = Penilaian::with('kriteria', 'subKriteria', 'alternatif')
            ->whereHas('alternatif', $filterKelas)
            ->get();
        
        // Data normalisasi bobot (dari kriteria dengan bobot ROC)
        $tabelNormalisasi = Kriteria::orderBy('kode')
            ->select('id', 'kode', 'kriteria', 'bobot_roc as normalisasi')
            ->get()
            ->map(function($item) {
                return (object)[
                    'kriteria' => (object)[
                        'kode' => $item->kode,
                        'kriteria' => $item->kriteria
                    ],
                    'normalisasi' => $item->normalisasi
                ];
            });
        
        // Data utility (dari penilaian dengan nilai normal)
        $tabelUtility = Penilaian::with('kriteria', 'alternatif')
            ->whereHas('alternatif', $filterKelas)
            ->get()
            ->map(function($item) {
                return (object)[
                    'alternatif_id' => $item->alternatif_id,
                    'kriteria_id' => $item->kriteria_id,
                    'nilai' => $item->nilai_normal
                ];
            });
        
        // Data nilai akhir
        $tabelNilaiAkhir = Penilaian::query()
            ->join('kriterias as k', 'k.id', '=', 'penilaians.kriteria_id')
            ->whereHas('alternatif', $filterKelas)
            ->selectRaw('alternatif_id, kriteria_id, (nilai_normal * k.bobot_roc) as nilai')
            ->get();
        
        // Data perankingan
        $tabelPerankingan = NilaiAkhir::query()
            ->join('alternatifs as a', 'a.id', '=', 'nilai_akhirs.alternatif_id')
            ->selectRaw("a.nis as kode, a.nama_siswa as alternatif, nilai_akhirs.total as nilai")
            ->when($user && $user->role === 'wali_kelas', function($q) use ($user) {
                $q->where('a.kelas', $user->kelas);
            })
            ->orderBy('nilai', 'desc')
            ->get();
        
        // Data kriteria
        $kriteria = Kriteria::orderBy('kode')->get(['id', 'kriteria']);
        
        // Data alternatif
        $alternatif = Alternatif::query()
            ->when($user && $user->role === 'wali_kelas', function($q) use ($user) {
                $q->where('kelas', $user->kelas);
            })
            ->orderBy('nis')
            ->selectRaw("id, nama_siswa as alternatif")
            ->get();

        $pdf = PDF::setOptions(['defaultFont' => 'sans-serif'])
            ->loadview('dashboard.pdf.hasil_akhir', compact(
                'judul',
                'tabelPenilaian', 
                'tabelNormalisasi',
                'tabelUtility',
                'tabelNilaiAkhir',
                'tabelPerankingan',
                'kriteria',
                'alternatif'
            ));

        return $pdf->stream();
    }
}