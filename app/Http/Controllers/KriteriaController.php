<?php

namespace App\Http\Controllers;

use App\Http\Requests\KriteriaRequest;
use App\Http\Resources\KriteriaResource;
use App\Imports\KriteriaImport;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\NilaiUtility;
use App\Models\NormalisasiBobot;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class KriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Kriteria";
        $kriteria = KriteriaResource::collection(Kriteria::orderBy('kode', 'asc')->get());
        $sumBobot = $kriteria->sum('bobot');
        $lastKode = Kriteria::orderBy('kode', 'desc')->first();
        if ($lastKode) {
            $kode = "K" . str_pad((int) substr($lastKode->kode, 1) + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $kode = "K00001";
        }
        return view('dashboard.kriteria.index', compact('title', 'kriteria', 'sumBobot', 'kode'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KriteriaRequest $request)
    {
        $validated = $request->validated();

        // Simpan kriteria baru
        $kriteria = Kriteria::create($validated);
        
        // Setelah kriteria baru disimpan, hitung bobot dengan metode ROC
        $this->hitungBobotROC();

        // Buat penilaian untuk alternatif
        $alternatif = Alternatif::all();
        foreach ($alternatif as $item) {
            Penilaian::updateOrCreate([
                'alternatif_id' => $item->id,
                'kriteria_id' => $kriteria->id,
            ], [
                'sub_kriteria_id' => null, // Default, jika sub-kriteria belum ada
            ]);
        }

        return to_route('kriteria')->with('success', 'Kriteria Berhasil Disimpan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        return new KriteriaResource(Kriteria::find($request->kriteria_id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KriteriaRequest $request)
    {
        $validated = $request->validated();

        // Update data kriteria
        $perbarui = Kriteria::where('id', $request->id)->update($validated);
        
        // Hitung ulang bobot ROC
        $this->hitungBobotROC();

        return $perbarui
            ? to_route('kriteria')->with('success', 'Kriteria Berhasil Diperbarui')
            : to_route('kriteria')->with('error', 'Kriteria Gagal Diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        Penilaian::where('kriteria_id', $request->kriteria_id)->delete();
        SubKriteria::where('kriteria_id', $request->kriteria_id)->delete();
        NormalisasiBobot::where('kriteria_id', $request->kriteria_id)->delete();
        NilaiUtility::where('kriteria_id', $request->kriteria_id)->delete();
        NilaiAkhir::where('kriteria_id', $request->kriteria_id)->delete();
        $hapus = Kriteria::where('id', $request->kriteria_id)->delete();
        return $hapus
            ? to_route('kriteria')->with('success', 'Kriteria Berhasil Dihapus')
            : to_route('kriteria')->with('error', 'Kriteria Gagal Dihapus');
    }

    /**
     * Import data kriteria
     */
    public function import(Request $request)
    {
        $request->validate([
            'import_data' => 'required|mimes:xls,xlsx'
        ]);

        $file = $request->file('import_data');
        Excel::import(new KriteriaImport, $file);

        // Setelah import data, hitung bobot ROC dan nilai akhir
        $this->hitungBobotROC();

        $kriteria = Kriteria::get('id');
        $alternatif = Alternatif::get('id');
        foreach ($kriteria as $value) {
            foreach ($alternatif as $item) {
                Penilaian::updateOrCreate([
                    'alternatif_id' => $item->id,
                    'kriteria_id' => $value->id,
                ], [
                    'sub_kriteria_id' => null,
                ]);
            }
        }

        return to_route('kriteria')->with('success', 'Kriteria Berhasil Disimpan');
    }

    /**
     * Hitung bobot menggunakan metode ROC
     */
    protected function hitungBobotROC()
    {
        $kriteria = Kriteria::all();
        $totalKriteria = $kriteria->count();

        foreach ($kriteria as $index => $k) {
            // Hitung bobot berdasarkan urutan prioritas dengan ROC
            $bobot = (1 / ($index + 1)) / (1 + $totalKriteria);
            
            // Simpan bobot ke dalam tabel normalisasi bobot
            NormalisasiBobot::updateOrCreate([
                'kriteria_id' => $k->id,
            ], [
                'bobot' => $bobot
            ]);
        }
    }
}
