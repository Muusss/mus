<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NilaiAkhir;
use App\Models\Periode;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Support\Facades\DB;

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
     * Halaman informasi sistem dengan data real
     */
    public function informasi()
    {
        // Get periode aktif
        $periodeAktif = Periode::getActive();
        
        // Get all periods untuk dropdown
        $periodes = Periode::orderBy('tahun_ajaran', 'desc')
                          ->orderBy('semester', 'desc')
                          ->get();
        
        // Get kriteria dengan bobot ROC yang sudah dihitung
        $kriterias = Kriteria::orderBy('urutan_prioritas')->get();
        
        // Get sub kriteria untuk setiap kriteria
        $subKriterias = SubKriteria::with('kriteria')
                                   ->orderBy('kriteria_id')
                                   ->orderBy('skor')
                                   ->get()
                                   ->groupBy('kriteria_id');
        
        // Get top 10 siswa untuk chart (jika ada periode aktif)
        $topStudents = collect();
        if ($periodeAktif) {
            $topStudents = NilaiAkhir::with('alternatif')
                ->where('periode_id', $periodeAktif->id)
                ->orderByDesc('total')
                ->take(10)
                ->get()
                ->map(function($item) {
                    return [
                        'name' => $item->alternatif->nama_siswa ?? 'N/A',
                        'nis' => $item->alternatif->nis ?? '',
                        'kelas' => $item->alternatif->kelas ?? '',
                        'score' => round($item->total * 100, 2) // Convert to percentage
                    ];
                });
        }
        
        // Get statistics
        $statistics = [
            'total_siswa' => Alternatif::count(),
            'total_kelas' => 4, // 6A, 6B, 6C, 6D
            'total_kriteria' => $kriterias->count(),
            'periode_aktif' => $periodeAktif ? $periodeAktif->nama_periode : null
        ];
        
        // Get distribusi nilai per kriteria untuk pie chart
        $kriteriaDistribution = $kriterias->map(function($kriteria) {
            return [
                'label' => $kriteria->kriteria,
                'kode' => $kriteria->kode,
                'value' => round($kriteria->bobot_roc * 100, 1), // Convert to percentage
                'color' => $this->getColorForIndex($kriteria->urutan_prioritas)
            ];
        });
        
        // Get sample data untuk contoh visual (ambil random siswa)
        $sampleStudent = null;
        if ($periodeAktif) {
            $randomStudent = Alternatif::inRandomOrder()->first();
            if ($randomStudent) {
                $samplePenilaian = Penilaian::where('alternatif_id', $randomStudent->id)
                    ->where('periode_id', $periodeAktif->id)
                    ->with('kriteria')
                    ->get();
                
                $sampleStudent = [
                    'nama' => $randomStudent->nama_siswa,
                    'nis' => $randomStudent->nis,
                    'kelas' => $randomStudent->kelas,
                    'penilaian' => $samplePenilaian->map(function($p) {
                        return [
                            'kriteria' => $p->kriteria->kriteria ?? '',
                            'kode' => $p->kriteria->kode ?? '',
                            'nilai_asli' => $p->nilai_asli,
                            'nilai_normal' => round($p->nilai_normal * 100, 1)
                        ];
                    })
                ];
            }
        }
        
        // Get kelas list
        $kelasList = ['6A', '6B', '6C', '6D'];
        
        return view('public.informasi', compact(
            'periodeAktif',
            'periodes',
            'kriterias',
            'subKriterias',
            'topStudents',
            'statistics',
            'kriteriaDistribution',
            'sampleStudent',
            'kelasList'
        ));
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
     * Proses pencarian nilai siswa dengan AJAX support
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
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Periode penilaian tidak ditemukan'
                ], 404);
            }
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
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }
            return back()->with('warning', 'Data siswa tidak ditemukan. Pastikan NISN atau nama yang dimasukkan benar.');
        }

        // Get kriteria
        $kriteria = Kriteria::orderBy('kode')->get();
        
        // Get penilaian siswa dengan sub kriteria
        $penilaian = Penilaian::where('alternatif_id', $siswa->id)
                              ->where('periode_id', $periode->id)
                              ->with(['kriteria', 'subKriteria'])
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
        
        // Get rata-rata kelas untuk perbandingan
        $rataRataKelas = NilaiAkhir::where('periode_id', $periode->id)
            ->whereHas('alternatif', function($q) use ($siswa) {
                $q->where('kelas', $siswa->kelas);
            })
            ->avg('total');
        
        // Prepare result data
        $hasilPencarian = [
            'siswa' => $siswa,
            'periode' => $periode,
            'kriteria' => $kriteria,
            'penilaian' => $penilaian,
            'nilaiAkhir' => $nilaiAkhir,
            'rankingKelas' => $rankingKelas,
            'totalSiswaKelas' => $totalSiswaKelas,
            'rataRataKelas' => $rataRataKelas ? round($rataRataKelas * 100, 2) : 0,
            'waktuAkses' => now()->format('d F Y, H:i:s')
        ];
        
        // Return JSON for AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'data' => $hasilPencarian,
                'html' => view('public.partials.hasil-nilai', compact('hasilPencarian'))->render()
            ]);
        }
        
        // Get all periods for dropdown
        $periodes = Periode::orderBy('tahun_ajaran', 'desc')
                          ->orderBy('semester', 'desc')
                          ->get();
        
        // Return view with search results
        return view('public.hasil-nilai', compact('hasilPencarian', 'periodes'))
            ->with('success', 'Data nilai berhasil ditemukan');
    }
    
    /**
     * Autocomplete search untuk nama siswa
     */
    public function searchAutocomplete(Request $request)
    {
        $term = $request->get('term');
        
        $siswa = Alternatif::where('nama_siswa', 'LIKE', '%' . $term . '%')
            ->orWhere('nis', 'LIKE', '%' . $term . '%')
            ->select('id', 'nis', 'nama_siswa', 'kelas')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'value' => $item->nis,
                    'label' => $item->nama_siswa . ' (' . $item->nis . ') - Kelas ' . $item->kelas,
                    'nama' => $item->nama_siswa,
                    'kelas' => $item->kelas
                ];
            });
            
        return response()->json($siswa);
    }
    
    /**
     * Download PDF nilai siswa
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
        
        // Get penilaian with sub kriteria
        $penilaian = Penilaian::where('alternatif_id', $siswa->id)
                              ->where('periode_id', $periode->id)
                              ->with(['kriteria', 'subKriteria'])
                              ->get()
                              ->keyBy('kriteria_id');
        
        // Get nilai akhir
        $nilaiAkhir = NilaiAkhir::where('alternatif_id', $siswa->id)
                                ->where('periode_id', $periode->id)
                                ->first();
        
        // Calculate ranking
        $ranking = null;
        if ($nilaiAkhir) {
            $ranking = NilaiAkhir::where('periode_id', $periode->id)
                ->whereHas('alternatif', function($q) use ($siswa) {
                    $q->where('kelas', $siswa->kelas);
                })
                ->where('total', '>', $nilaiAkhir->total)
                ->count() + 1;
        }
        
        // School info
        $sekolah = (object)[
            'nama' => 'SDIT As Sunnah Cirebon',
            'alamat' => 'Jl. Pendidikan No. 123, Cirebon',
            'tahun_ajaran' => $periode->tahun_ajaran . '/' . ($periode->tahun_ajaran + 1),
            'semester' => $periode->semester == 1 ? 'Ganjil' : 'Genap'
        ];
        
        // Generate PDF
        $pdf = \PDF::loadView('public.pdf.nilai-siswa', compact(
            'siswa', 'periode', 'kriteria', 'penilaian', 'nilaiAkhir', 'ranking', 'sekolah'
        ));
        
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Nilai_' . str_replace(' ', '_', $siswa->nama_siswa) . '_' . date('Y-m-d') . '.pdf';
        
        return $pdf->download($filename);
    }
    
    /**
     * Send email nilai to parent
     */
    public function sendNilaiEmail(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:alternatifs,id',
            'periode_id' => 'required|exists:periodes,id',
            'email' => 'required|email'
        ]);
        
        // Get data
        $siswa = Alternatif::findOrFail($request->siswa_id);
        $periode = Periode::findOrFail($request->periode_id);
        
        // Send email (implement Mail class)
        try {
            \Mail::to($request->email)->send(new \App\Mail\NilaiSiswa($siswa, $periode));
            
            return response()->json([
                'success' => true,
                'message' => 'Email berhasil dikirim ke ' . $request->email
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper function to get color for chart
     */
    private function getColorForIndex($index)
    {
        $colors = [
            '#ff6b35', // Orange (primary)
            '#ff8c5a', // Light orange
            '#ffa374', // Lighter orange
            '#ffb891', // Even lighter
            '#ffcdb0', // Very light
            '#ffe2cf'  // Lightest
        ];
        
        return $colors[($index - 1) % count($colors)];
    }
    
    /**
     * Get chart data untuk visualisasi
     */
    public function getChartData()
    {
        $periodeAktif = Periode::getActive();
        
        if (!$periodeAktif) {
            return response()->json([
                'error' => 'Tidak ada periode aktif'
            ], 404);
        }
        
        // Get top students per class
        $topPerClass = [];
        $kelasList = ['6A', '6B', '6C', '6D'];
        
        foreach ($kelasList as $kelas) {
            $top = NilaiAkhir::with('alternatif')
                ->where('periode_id', $periodeAktif->id)
                ->whereHas('alternatif', function($q) use ($kelas) {
                    $q->where('kelas', $kelas);
                })
                ->orderByDesc('total')
                ->first();
                
            if ($top) {
                $topPerClass[] = [
                    'kelas' => $kelas,
                    'nama' => $top->alternatif->nama_siswa,
                    'nilai' => round($top->total * 100, 2)
                ];
            }
        }
        
        // Get average per kriteria
        $avgPerKriteria = [];
        $kriterias = Kriteria::orderBy('kode')->get();
        
        foreach ($kriterias as $kriteria) {
            $avg = Penilaian::where('kriteria_id', $kriteria->id)
                ->where('periode_id', $periodeAktif->id)
                ->avg('nilai_normal');
                
            $avgPerKriteria[] = [
                'kriteria' => $kriteria->kriteria,
                'kode' => $kriteria->kode,
                'rata_rata' => round($avg * 100, 2)
            ];
        }
        
        return response()->json([
            'topPerClass' => $topPerClass,
            'avgPerKriteria' => $avgPerKriteria,
            'periode' => $periodeAktif->nama_periode
        ]);
    }
}