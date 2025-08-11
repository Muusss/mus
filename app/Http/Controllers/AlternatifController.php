<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlternatifResource;
use App\Http\Resources\KriteriaResource;
use App\Http\Resources\NilaiAkhirResource;
use App\Http\Resources\NilaiUtilityResource;
use App\Http\Resources\NormalisasiBobotResource;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\NilaiUtility;
use App\Models\NormalisasiBobot;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class AlternatifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Alternatif";
        $alternatif = AlternatifResource::collection(Alternatif::orderBy('kode', 'asc')->get());
        return view('dashboard.alternatif.index', compact('title', 'alternatif'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string|unique:alternatifs,kode',
            'nama' => 'required|string',
        ]);

        $alternatif = Alternatif::create($validated);

        if ($alternatif) {
            return to_route('alternatif')->with('success', 'Alternatif Berhasil Disimpan');
        } else {
            return to_route('alternatif')->with('error', 'Alternatif Gagal Disimpan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $alternatif = Alternatif::find($request->alternatif_id);
        return response()->json($alternatif);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'kode' => 'required|string',
            'nama' => 'required|string',
        ]);

        $alternatif = Alternatif::find($request->id);
        $alternatif->update($validated);

        if ($alternatif) {
            return to_route('alternatif')->with('success', 'Alternatif Berhasil Diperbarui');
        } else {
            return to_route('alternatif')->with('error', 'Alternatif Gagal Diperbarui');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        $alternatif = Alternatif::find($request->id);
        $hapus = $alternatif->delete();

        if ($hapus) {
            return to_route('alternatif')->with('success', 'Alternatif Berhasil Dihapus');
        } else {
            return to_route('alternatif')->with('error', 'Alternatif Gagal Dihapus');
        }
    }

    /**
     * Perhitungan Nilai Akhir untuk Alternatif
     * Menggunakan metode SMART dan ROC
     */
    public function perhitunganNilaiAkhir()
    {
        $alternatif = Alternatif::all();
        $kriteria = Kriteria::all();

        // Pastikan nilai akhir dihapus sebelum perhitungan baru
        NilaiAkhir::truncate();

        // Perhitungan nilai akhir untuk setiap alternatif berdasarkan kriteria
        foreach ($alternatif as $item) {
            $totalNilai = 0;
            foreach ($kriteria as $value) {
                // Ambil nilai utility berdasarkan perhitungan sebelumnya
                $nilaiUtility = NilaiUtility::where('kriteria_id', $value->id)->where('alternatif_id', $item->id)->first()->nilai;
                
                // Ambil bobot normalisasi dari tabel NormalisasiBobot
                $normalisasiBobot = NormalisasiBobot::where('kriteria_id', $value->id)->first()->normalisasi;
                
                // Hitung nilai akhir untuk alternatif berdasarkan bobot dan nilai utility
                $nilaiAkhir = $normalisasiBobot * $nilaiUtility;
                $createNilaiAkhir = NilaiAkhir::create([
                    'alternatif_id' => $item->id,
                    'kriteria_id' => $value->id,
                    'nilai' => $nilaiAkhir,
                ]);

                // Jumlahkan nilai akhir untuk alternatif
                $totalNilai += $nilaiAkhir;
            }

            // Simpan total nilai akhir untuk setiap alternatif
            Penilaian::where('alternatif_id', $item->id)->update(['nilai_akhir' => $totalNilai]);
        }

        return to_route('alternatif')->with('success', 'Perhitungan Nilai Akhir Alternatif Berhasil Dilakukan');
    }

    /**
     * Menampilkan perhitungan metode SMART untuk alternatif
     */
    public function indexPerhitungan()
    {
        $title = "Perhitungan Metode SMART";
        
        $normalisasiBobot = NormalisasiBobotResource::collection(NormalisasiBobot::with('kriteria')->orderBy('kriteria_id', 'asc')->get());
        $nilaiUtility = NilaiUtilityResource::collection(NilaiUtility::orderBy('alternatif_id', 'asc')->orderBy('kriteria_id', 'asc')->get());
        $nilaiAkhir = NilaiAkhirResource::collection(NilaiAkhir::orderBy('alternatif_id', 'asc')->orderBy('kriteria_id', 'asc')->get());
        
        $alternatif = AlternatifResource::collection(Alternatif::orderBy('kode', 'asc')->get());
        $kriteria = KriteriaResource::collection(Kriteria::orderBy('kode', 'asc')->get());
        $sumBobotKriteria = $kriteria->sum('bobot');

        return view('dashboard.perhitungan.index', compact('title', 'normalisasiBobot', 'nilaiUtility', 'nilaiAkhir', 'alternatif', 'kriteria', 'sumBobotKriteria'));
    }

    /**
     * Menjalankan perhitungan metode SMART untuk alternatif
     */
    public function perhitunganMetode()
    {
        $this->perhitunganNilaiAkhir();
        
        return to_route('perhitungan')->with('success', 'Perhitungan Metode SMART untuk Alternatif Berhasil Dilakukan');
    }
}
