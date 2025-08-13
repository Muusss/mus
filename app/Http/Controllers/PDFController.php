<?php
// app/Http/Controllers/PDFController.php

namespace App\Http\Controllers;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\Auth;

class PDFController extends Controller
{
    public function pdf_hasil()
    {
        $judul = 'Laporan Hasil Akhir';
        $user = Auth::user();

        // Ambil filter kelas dari query (?kelas=...); default 'all'
        $kelasFilter = request()->get('kelas', 'all');

        // Paksa filter kelas untuk role wali_kelas
        if ($user && isset($user->role) && $user->role === 'wali_kelas') {
            $kelasFilter = $user->kelas ?? $kelasFilter;
        }

        // Update judul bila ada filter kelas
        if ($kelasFilter && $kelasFilter !== 'all') {
            $judul .= ' - Kelas ' . $kelasFilter;
        }

        // Helper closure untuk filter kelas
        $filterKelas = function($q) use ($kelasFilter) {
            if ($kelasFilter && $kelasFilter !== 'all') {
                $q->where('kelas', $kelasFilter);
            }
        };

        // Data kriteria
        $kriteria = Kriteria::orderBy('urutan_prioritas')->get();
        
        // Data alternatif
        $alternatif = Alternatif::query()
            ->when($user && $user->role === 'wali_kelas', function($q) use ($user) {
                $q->where('kelas', $user->kelas);
            })
            ->when($kelasFilter && $kelasFilter !== 'all', function($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            })
            ->orderBy('nis')
            ->get();
        
        // Data penilaian dengan fix null handling
        $tabelPenilaian = Penilaian::with(['kriteria', 'subKriteria', 'alternatif'])
            ->whereHas('alternatif', $filterKelas)
            ->get()
            ->map(function($item) {
                return (object)[
                    'alternatif' => $item->alternatif,
                    'kriteria' => $item->kriteria,
                    'sub_kriteria' => $item->subKriteria ? $item->subKriteria->label : 'Nilai: ' . $item->nilai_asli,
                    'nilai_asli' => $item->nilai_asli,
                    'nilai_normal' => $item->nilai_normal
                ];
            });
        
        // Data hasil akhir dengan ranking
        $tabelPerankingan = NilaiAkhir::query()
            ->join('alternatifs as a', 'a.id', '=', 'nilai_akhirs.alternatif_id')
            ->selectRaw("a.nis as kode, a.nama_siswa as alternatif, nilai_akhirs.total as nilai")
            ->when($kelasFilter && $kelasFilter !== 'all', function($q) use ($kelasFilter) {
                $q->where('a.kelas', $kelasFilter);
            })
            ->orderBy('nilai', 'desc')
            ->get();
        
        // Info Sekolah
        $sekolah = (object)[
            'nama' => 'SDIT As Sunnah Cirebon',
            'alamat' => 'Jl. Pendidikan No. 123, Cirebon',
            'tahun_ajaran' => date('Y') . '/' . (date('Y') + 1),
            'semester' => 'Ganjil'
        ];
        
        // Info tambahan
        $tanggal_cetak = now()->format('d F Y');
        $kelas_filter = ($user && $user->role === 'wali_kelas') ? $user->kelas : 'Semua Kelas';

        // Generate PDF
        $pdf = PDF::setOptions([
            'dpi' => 150,
            'defaultFont' => 'sans-serif',
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true
        ])->loadview('dashboard.pdf.hasil_akhir', compact(
            'judul',
            'sekolah',
            'tanggal_cetak',
            'kelas_filter',
            'tabelPenilaian', 
            'tabelPerankingan',
            'kriteria',
            'alternatif'
        ));

        $pdf->setPaper('A4', 'portrait');
        return $pdf->stream('Laporan_Siswa_Teladan_' . date('Y-m-d') . '.pdf');
    }
}