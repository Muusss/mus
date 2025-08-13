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
        $alternatif = Alternatif::orderBy('nis')->get();
        $kriteria   = Kriteria::orderBy('kode')->get();
        
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

        // Filter penilaian berdasarkan periode
        $penilaian = Penilaian::where('periode_id', $periodeAktif->id)
            ->get()
            ->groupBy(['alternatif_id','kriteria_id']);

        return view('dashboard.penilaian.index', compact(
            'alternatif','kriteria','penilaian','periodeAktif','periodes'
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
            return redirect()->route('periode')
                ->with('error', 'Silakan aktifkan periode terlebih dahulu');
        }

        $data = $request->validate([
            'nilai_asli'   => ['required','array'],
            'nilai_asli.*' => ['nullable','numeric','min:1','max:4'],
        ]);

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
            }
        }

        return redirect()->route('penilaian', ['periode_id' => $periodeAktif->id])
            ->with('success', "Penilaian {$alternatif->nama_siswa} untuk {$periodeAktif->nama_periode} berhasil disimpan.");
    }

    public function store(PenilaianStoreRequest $request)
    {
        $periodeAktif = Periode::getActive();
        
        if (!$periodeAktif) {
            return back()->with('error', 'Silakan aktifkan periode terlebih dahulu');
        }

        [$subId, $skor] = $this->resolveSubAndSkor(
            $request->input('kriteria_id'),
            $request->input('nilai_angka'),
            $request->input('sub_kriteria_id'),
            $request->input('label')
        );

        if (!$subId || !$skor) {
            return back()->with('error','Tidak menemukan sub kriteria yang cocok. Periksa input.');
        }

        $row = Penilaian::updateOrCreate(
            [
                'alternatif_id' => $request->input('alternatif_id'),
                'kriteria_id'   => $request->input('kriteria_id'),
                'periode_id'    => $periodeAktif->id
            ],
            [
                'sub_kriteria_id' => $subId,
                'nilai_asli'      => $skor,
                'nilai_normal'    => null,
            ]
        );

        return back()->with($row ? 'success' : 'error', 
            $row ? 'Nilai tersimpan untuk ' . $periodeAktif->nama_periode : 'Gagal menyimpan nilai.');
    }

    private function resolveSubAndSkor(int $kriteriaId, ?float $nilaiAngka, ?int $subId, ?string $label): array
    {
        if ($subId) {
            $sub = SubKriteria::where('kriteria_id',$kriteriaId)->where('id',$subId)->first();
            return $sub ? [$sub->id, (int)$sub->skor] : [null, null];
        }

        if ($label) {
            $sub = SubKriteriaMatcher::matchByLabel($kriteriaId, $label);
            return $sub ? [$sub->id, (int)$sub->skor] : [null, null];
        }

        if ($nilaiAngka !== null) {
            $sub = SubKriteriaMatcher::matchNumeric($kriteriaId, (float)$nilaiAngka);
            return $sub ? [$sub->id, (int)$sub->skor] : [null, null];
        }

        return [null, null];
    }
}