<?php

namespace App\Http\Controllers;

use App\Http\Requests\PenilaianRequest;
use App\Http\Requests\PenilaianStoreRequest;
use App\Http\Resources\PenilaianResource;
use App\Services\SubKriteriaMatcher;
use App\Imports\PenilaianImport;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use App\Models\NormalisasiBobot;  // Model untuk menyimpan normalisasi bobot
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Penilaian Alternatif";
        $kriteria = Kriteria::orderBy('id', 'asc')->get(['id', 'kriteria']);
        $subKriteria = SubKriteria::orderBy('kriteria_id', 'asc')->get();
        $alternatif = Alternatif::orderBy('id', 'asc')->get(['id', 'alternatif']);
        $penilaian = PenilaianResource::collection(Penilaian::orderBy('kriteria_id', 'asc')->get());
        return view('dashboard.penilaian.index', compact('title', 'kriteria', 'subKriteria', 'alternatif', 'penilaian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $penilaian = Penilaian::where('alternatif_id', $request->alternatif_id)->get();
        return $penilaian;
    }

    public function store(PenilaianStoreRequest $request)
    {
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
            ],
            [
                'sub_kriteria_id' => $subId,
                'nilai_asli'      => $skor,     // 1..4
                'nilai_normal'    => null,      // akan dihitung saat proses SMART
            ]
        );

        return back()->with($row ? 'success' : 'error', $row ? 'Nilai tersimpan.' : 'Gagal menyimpan nilai.');
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(PenilaianStoreRequest $request, Penilaian $penilaian)
    {
        [$subId, $skor] = $this->resolveSubAndSkor(
            $request->input('kriteria_id'),
            $request->input('nilai_angka'),
            $request->input('sub_kriteria_id'),
            $request->input('label')
        );

        if (!$subId || !$skor) {
            return back()->with('error','Tidak menemukan sub kriteria yang cocok. Periksa input.');
        }

        $ok = $penilaian->update([
            'alternatif_id'   => $request->input('alternatif_id'),
            'kriteria_id'     => $request->input('kriteria_id'),
            'sub_kriteria_id' => $subId,
            'nilai_asli'      => $skor,
            'nilai_normal'    => null,
        ]);

        return back()->with($ok ? 'success' : 'error', $ok ? 'Nilai diperbarui.' : 'Gagal memperbarui nilai.');
    }

    /**
     * Mapping input (angka / id / label) -> (sub_kriteria_id, skor)
     */
    private function resolveSubAndSkor(int $kriteriaId, ?float $nilaiAngka, ?int $subId, ?string $label): array
    {
        // Jika user pilih langsung sub_kriteria_id → pakai itu
        if ($subId) {
            $sub = SubKriteria::where('kriteria_id',$kriteriaId)->where('id',$subId)->first();
            return $sub ? [$sub->id, (int)$sub->skor] : [null, null];
        }

        // Kalau pakai label (C3, C4, C5)
        if ($label) {
            $sub = SubKriteriaMatcher::matchByLabel($kriteriaId, $label);
            return $sub ? [$sub->id, (int)$sub->skor] : [null, null];
        }

        // Kalau pakai nilai angka (C1, C2, C6)
        if ($nilaiAngka !== null) {
            $sub = SubKriteriaMatcher::matchNumeric($kriteriaId, (float)$nilaiAngka);
            return $sub ? [$sub->id, (int)$sub->skor] : [null, null];
        }

        return [null, null];
    }
    
    /**
     * Import data penilaian
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_data' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('import_data');
        $import = Excel::import(new PenilaianImport, $file);

        if ($import) {
            return to_route('penilaian')->with('success', 'Penilaian Berhasil Disimpan');
        } else {
            return to_route('penilaian')->with('error', 'Penilaian Gagal Disimpan');
        }
    }

    /**
     * Hitung bobot menggunakan metode ROC
     */
    protected function hitungBobotROC()
    {
        // Ambil data kriteria dan urutkan berdasarkan prioritas
        $kriteria = Kriteria::all();
        $totalKriteria = $kriteria->count();

        foreach ($kriteria as $index => $k) {
            // Hitung bobot berdasarkan urutan prioritas dengan ROC
            $bobot = (1 / ($index + 1)) / (1 + $totalKriteria);
            
            // Simpan bobot ke dalam tabel normalisasi bobot
            NormalisasiBobot::create([
                'kriteria_id' => $k->id,
                'bobot' => $bobot
            ]);
        }
    }

    /**
     * Hitung nilai akhir menggunakan metode SMART
     */
    protected function hitungNilaiSMART()
    {
        $alternatif = Alternatif::all();

        foreach ($alternatif as $alt) {
            $totalNilai = 0;
            $penilaians = Penilaian::where('alternatif_id', $alt->id)->get();

            foreach ($penilaians as $penilaian) {
                // Ambil nilai subkriteria untuk alternatif
                $subKriteria = SubKriteria::find($penilaian->sub_kriteria_id);
                // Ambil bobot kriteria yang sudah dihitung sebelumnya dengan ROC
                $bobot = NormalisasiBobot::where('kriteria_id', $penilaian->kriteria_id)->first()->bobot;

                // Hitung nilai utilitas untuk setiap kriteria (SMART)
                $nilaiUtilitas = $this->hitungUtilitas($subKriteria->nilai, $penilaian->kriteria_id);
                
                // Hitung nilai akhir alternatif
                $totalNilai += $nilaiUtilitas * $bobot;
            }

            // Simpan nilai akhir ke dalam tabel hasil perhitungan
            Penilaian::where('alternatif_id', $alt->id)->update(['nilai_akhir' => $totalNilai]);
        }
    }

    /**
     * Hitung nilai utilitas (SMART)
     * @param $nilaiSubKriteria
     * @param $kriteria_id
     * @return float
     */
    protected function hitungUtilitas($nilaiSubKriteria, $kriteria_id)
    {
        $min = SubKriteria::where('kriteria_id', $kriteria_id)->min('nilai');
        $max = SubKriteria::where('kriteria_id', $kriteria_id)->max('nilai');

        // Hitung nilai utilitas berdasarkan formula SMART
        return ($nilaiSubKriteria - $min) / ($max - $min);
    }
}
