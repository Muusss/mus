<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenilaianStoreRequest;
use App\Services\SubKriteriaMatcher;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get filter kelas
        $kelasFilter = $request->get('kelas', 'all');
        
        // Force filter untuk wali kelas
        if ($user && $user->role === 'wali_kelas' && $user->kelas) {
            $kelasFilter = $user->kelas;
        }
        
        // Get alternatif dengan filter kelas
        $alternatif = Alternatif::query()
            ->when($kelasFilter && $kelasFilter !== 'all', function($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            })
            ->orderBy('kelas')
            ->orderBy('nis')
            ->get();
            
        $kriteria = Kriteria::orderBy('kode')->get();
        
        // Get periode aktif atau dari request
        $periodeId = $request->get('periode_id');
        
        if ($periodeId) {
            $periodeAktif = Periode::findOrFail($periodeId);
        } else {
            $periodeAktif = Periode::getActive();
        }
        
        // Get all periods for dropdown
        $periodes = Periode::orderBy('tahun_ajaran', 'desc')
                          ->orderBy('semester', 'desc')
                          ->get();
        
        if (!$periodeAktif) {
            return redirect()->route('periode')
                ->with('error', 'Silakan aktifkan periode terlebih dahulu');
        }

        // Filter penilaian berdasarkan periode dan alternatif yang sudah difilter
        $altIds = $alternatif->pluck('id')->toArray();
        $penilaian = Penilaian::where('periode_id', $periodeAktif->id)
            ->whereIn('alternatif_id', $altIds)
            ->get()
            ->groupBy(['alternatif_id','kriteria_id']);

        // Get list kelas untuk dropdown
        $kelasList = ['6A', '6B', '6C', '6D'];

        return view('dashboard.penilaian.index', compact(
            'alternatif','kriteria','penilaian','periodeAktif','periodes',
            'kelasFilter','kelasList'
        ));
    }

    public function edit($id, Request $request)
    {
        $alternatif = Alternatif::findOrFail($id);
        $kriteria   = Kriteria::orderBy('kode')->get();
        
        // Get periode dari request atau aktif
        $periodeId = $request->get('periode_id');
        if ($periodeId) {
            $periodeAktif = Periode::findOrFail($periodeId);
        } else {
            $periodeAktif = Periode::getActive();
        }
        
        if (!$periodeAktif) {
            return redirect()->route('periode')
                ->with('error', 'Silakan aktifkan periode terlebih dahulu');
        }
        
        // Get penilaian untuk periode ini
        $rows = Penilaian::where('alternatif_id', $alternatif->id)
                        ->where('periode_id', $periodeAktif->id)
                        ->get()
                        ->groupBy('kriteria_id');

        if (request()->ajax()) {
            return view('dashboard.penilaian._form', compact(
                'alternatif','kriteria','rows','periodeAktif'
            ));
        }
        
        return view('dashboard.penilaian.edit', compact(
            'alternatif','kriteria','rows','periodeAktif'
        ));
    }

    public function update(Request $request, $id)
    {
        $alternatif = Alternatif::findOrFail($id);
        
        // Get periode dari request atau aktif
        $periodeId = $request->get('periode_id');
        if ($periodeId) {
            $periodeAktif = Periode::findOrFail($periodeId);
        } else {
            $periodeAktif = Periode::getActive();
        }
        
        if (!$periodeAktif) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Silakan aktifkan periode terlebih dahulu'
                ], 400);
            }
            
            return redirect()->route('periode')
                ->with('error', 'Silakan aktifkan periode terlebih dahulu');
        }

        $data = $request->validate([
            'nilai_asli'   => ['required','array'],
            'nilai_asli.*' => ['nullable','numeric','min:1','max:4'],
        ]);

        $updatedValues = [];
        
        foreach ($data['nilai_asli'] as $kriteriaId => $nilai) {
            if ($nilai !== null && $nilai !== '') {
                Penilaian::updateOrCreate(
                    [
                        'alternatif_id' => $alternatif->id, 
                        'kriteria_id' => $kriteriaId,
                        'periode_id' => $periodeAktif->id
                    ],
                    [
                        'nilai_asli' => $nilai,
                        'nilai_normal' => null // akan dihitung saat normalisasi
                    ]
                );
                
                $updatedValues[$kriteriaId] = $nilai;
            }
        }

        // Check if request is AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Penilaian {$alternatif->nama_siswa} berhasil disimpan",
                'data' => [
                    'alternatif_id' => $alternatif->id,
                    'values' => $updatedValues
                ]
            ]);
        }

        return redirect()->route('penilaian', ['periode_id' => $periodeAktif->id])
            ->with('success', "Penilaian {$alternatif->nama_siswa} untuk {$periodeAktif->nama_periode} berhasil disimpan.");
    }

    // Method untuk quick edit semua siswa dalam satu kelas
    public function editKelas(Request $request)
    {
        $kelas = $request->get('kelas');
        $periodeId = $request->get('periode_id');
        
        if (!$kelas) {
            return redirect()->route('penilaian')->with('error', 'Kelas tidak valid');
        }
        
        $periodeAktif = $periodeId ? Periode::find($periodeId) : Periode::getActive();
        
        if (!$periodeAktif) {
            return redirect()->route('periode')
                ->with('error', 'Silakan aktifkan periode terlebih dahulu');
        }
        
        $alternatifs = Alternatif::where('kelas', $kelas)
            ->orderBy('nis')
            ->get();
            
        $kriteria = Kriteria::orderBy('kode')->get();
        
        // Get all penilaian for this class
        $altIds = $alternatifs->pluck('id')->toArray();
        $penilaians = Penilaian::where('periode_id', $periodeAktif->id)
            ->whereIn('alternatif_id', $altIds)
            ->get()
            ->groupBy(['alternatif_id', 'kriteria_id']);
        
        return view('dashboard.penilaian.edit-kelas', compact(
            'alternatifs', 'kriteria', 'penilaians', 'periodeAktif', 'kelas'
        ));
    }
    
    // Method untuk update semua nilai dalam satu kelas
    public function updateKelas(Request $request)
    {
        $kelas = $request->get('kelas');
        $periodeId = $request->get('periode_id');
        
        $periodeAktif = $periodeId ? Periode::find($periodeId) : Periode::getActive();
        
        if (!$periodeAktif) {
            return redirect()->route('periode')
                ->with('error', 'Silakan aktifkan periode terlebih dahulu');
        }
        
        $data = $request->validate([
            'nilai' => ['required', 'array'],
            'nilai.*.*' => ['nullable', 'numeric', 'min:1', 'max:4'],
        ]);
        
        foreach ($data['nilai'] as $alternatifId => $kriteriaValues) {
            foreach ($kriteriaValues as $kriteriaId => $nilai) {
                if ($nilai !== null && $nilai !== '') {
                    Penilaian::updateOrCreate(
                        [
                            'alternatif_id' => $alternatifId,
                            'kriteria_id' => $kriteriaId,
                            'periode_id' => $periodeAktif->id
                        ],
                        [
                            'nilai_asli' => $nilai,
                            'nilai_normal' => null
                        ]
                    );
                }
            }
        }
        
        return redirect()->route('penilaian', ['kelas' => $kelas])
            ->with('success', "Penilaian kelas {$kelas} berhasil disimpan.");
    }
}