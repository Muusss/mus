<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NilaiAkhir;
use App\Models\Periode;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;

class PublicController extends Controller
{
    /**
     * Halaman hasil peringkat publik
     */
    public function hasilPublik(Request $request)
    {
        // Get periode aktif
        $periodeAktif = Periode::getActive();
        
        // Get filter kelas
        $kelasFilter = $request->get('kelas', 'all');
        
        // Get list kelas
        $kelasList = ['6A', '6B', '6C', '6D'];
        
        // Query nilai akhir
        $nilaiAkhir = collect();
        
        if ($periodeAktif) {
            $nilaiAkhir = NilaiAkhir::with('alternatif')
                ->where('periode_id', $periodeAktif->id)
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
        }
        
        return view('public.hasil', compact(
            'nilaiAkhir',
            'kelasFilter',
            'kelasList',
            'periodeAktif'
        ));
    }

    /**
     * Halaman informasi sistem
     */
    public function informasi()
    {
        // Get periode aktif untuk badge
        $periodeAktif = Periode::getActive();
        
        // Get all periods untuk dropdown form
        $periodes = Periode::orderBy('tahun_ajaran', 'desc')
                          ->orderBy('semester', 'desc')
                          ->get();
        
        return view('public.informasi', compact('periodeAktif', 'periodes'));
    }

    /**
     * Form cek nilai siswa (GET)
     */
    public function cekNilai(Request $request)
    {
        // Jika ada parameter, lakukan pencarian
        if ($request->has('search') || $request->has('nisn')) {
            return $this->prosesNilai($request);
        }
        
        // Tampilkan halaman informasi dengan form
        return $this->informasi();
    }

    /**
     * Proses pencarian nilai siswa (POST/GET with params)
     */
    public function prosesNilai(Request $request)
    {
        // Validasi input
        $request->validate([
            'search' => 'required_without:nisn|string',
            'nisn' => 'required_without:search|string',
        ], [
            'search.required_without' => 'Mohon masukkan NISN atau nama siswa',
            'nisn.required_without' => 'Mohon masukkan NISN atau nama siswa',
        ]);

        // Get search term
        $searchTerm = $request->search ?? $request->nisn;
        $kelas = $request->kelas;
        $periodeId = $request->periode_id;
        
        // Get periode
        if ($periodeId) {
            $periode = Periode::find($periodeId);
        } else {
            $periode = Periode::getActive();
        }
        
        if (!$periode) {
            return back()->with('error', 'Periode penilaian tidak ditemukan. Silakan hubungi admin.');
        }

        // Cari siswa
        $query = Alternatif::query();
        
        // Search by NISN or name
        $query->where(function($q) use ($searchTerm) {
            $q->where('nis', 'LIKE', '%' . $searchTerm . '%')
              ->orWhere('nama_siswa', 'LIKE', '%' . $searchTerm . '%');
        });
        
        // Filter by class if provided
        if ($kelas) {
            $query->where('kelas', $kelas);
        }
        
        $siswa = $query->first();
        
        if (!$siswa) {
            return back()->with('warning', 'Data siswa tidak ditemukan. Pastikan NISN atau nama yang dimasukkan benar.');
        }

        // Get kriteria
        $kriteria = Kriteria::orderBy('kode')->get();
        
        // Get penilaian siswa
        $penilaian = Penilaian::where('alternatif_id', $siswa->id)
                              ->where('periode_id', $periode->id)
                              ->with('kriteria')
                              ->get()
                              ->keyBy('kriteria_id');
        
        // Get nilai akhir
        $nilaiAkhir = NilaiAkhir::where('alternatif_id', $siswa->id)
                                ->where('periode_id', $periode->id)
                                ->first();
        
        // Calculate ranking in class
        $rankingKelas = null;
        $totalSiswaKelas = 0;
        
        if ($nilaiAkhir) {
            $rankingKelas = NilaiAkhir::where('periode_id', $periode->id)
                ->whereHas('alternatif', function($q) use ($siswa) {
                    $q->where('kelas', $siswa->kelas);
                })
                ->where('total', '>', $nilaiAkhir->total)
                ->count() + 1;
                
            $totalSiswaKelas = NilaiAkhir::where('periode_id', $periode->id)
                ->whereHas('alternatif', function($q) use ($siswa) {
                    $q->where('kelas', $siswa->kelas);
                })
                ->count();
        }
        
        // Prepare result data
        $hasilPencarian = [
            'siswa' => $siswa,
            'periode' => $periode,
            'kriteria' => $kriteria,
            'penilaian' => $penilaian,
            'nilaiAkhir' => $nilaiAkhir,
            'rankingKelas' => $rankingKelas,
            'totalSiswaKelas' => $totalSiswaKelas,
            'waktuAkses' => now()->format('d F Y, H:i:s')
        ];
        
        // Get all periods for dropdown
        $periodes = Periode::orderBy('tahun_ajaran', 'desc')
                          ->orderBy('semester', 'desc')
                          ->get();
        
        // Return view with search results
        return view('public.hasil-nilai', compact('hasilPencarian', 'periodes'))
            ->with('success', 'Data nilai berhasil ditemukan');
    }

    /**
     * Download PDF hasil nilai siswa
     */
    public function downloadNilaiPDF(Request $request)
    {
        // Validasi request
        $request->validate([
            'siswa_id' => 'required|exists:alternatifs,id',
            'periode_id' => 'required|exists:periodes,id'
        ]);
        
        $siswa = Alternatif::findOrFail($request->siswa_id);
        $periode = Periode::findOrFail($request->periode_id);
        $kriteria = Kriteria::orderBy('kode')->get();
        
        // Get penilaian
        $penilaian = Penilaian::where('alternatif_id', $siswa->id)
                              ->where('periode_id', $periode->id)
                              ->with('kriteria')
                              ->get()
                              ->keyBy('kriteria_id');
        
        // Get nilai akhir
        $nilaiAkhir = NilaiAkhir::where('alternatif_id', $siswa->id)
                                ->where('periode_id', $periode->id)
                                ->first();
        
        // Generate PDF
        $pdf = \PDF::loadView('public.pdf.nilai-siswa', compact(
            'siswa', 'periode', 'kriteria', 'penilaian', 'nilaiAkhir'
        ));
        
        $filename = 'Nilai_' . str_replace(' ', '_', $siswa->nama_siswa) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
}