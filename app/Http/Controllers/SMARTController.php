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

class SMARTController extends Controller
{
    public function indexNormalisasiBobot()
    {
        $title = "Normalisasi Bobot";
        $normalisasiBobot = NormalisasiBobotResource::collection(NormalisasiBobot::with('kriteria')->orderBy('kriteria_id', 'asc')->get());
        $sumBobot = Kriteria::sum('bobot');
        return view('dashboard.normalisasi-bobot.index', compact('title', 'normalisasiBobot', 'sumBobot'));
    }

    public function perhitunganNormalisasiBobot()
    {
        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $sumBobot = $kriteria->sum('bobot');
        NormalisasiBobot::truncate(); // This will remove all data in the table

        foreach ($kriteria as $item) {
            NormalisasiBobot::create([
                'kriteria_id' => $item->id,
                'normalisasi' => $item->bobot / $sumBobot,
            ]);
        }

        return to_route('normalisasi-bobot')->with('success', 'Normalisasi Bobot Kriteria Berhasil Dilakukan');
    }

    public function indexNilaiUtility()
    {
        $title = "Nilai Utility";
        $nilaiUtility = NilaiUtilityResource::collection(NilaiUtility::orderBy('alternatif_id', 'asc')->orderBy('kriteria_id', 'asc')->get());
        $alternatif = AlternatifResource::collection(Alternatif::orderBy('kode', 'asc')->get());
        $kriteria = KriteriaResource::collection(Kriteria::orderBy('kode', 'asc')->get());
        return view('dashboard.nilai-utility.index', compact('title', 'nilaiUtility', 'alternatif', 'kriteria'));
    }

    public function perhitunganNilaiUtility()
    {
        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $alternatif = Alternatif::orderBy('kode', 'asc')->get();
        NilaiUtility::truncate();

        $nilaiMaxMin = Penilaian::query()
            ->join('kriteria as k', 'k.id', '=', 'penilaian.kriteria_id')
            ->join('sub_kriteria as sk', 'sk.id', '=', 'penilaian.sub_kriteria_id')
            ->selectRaw("penilaian.kriteria_id, k.kriteria, MAX(sk.bobot) as nilaiMax, MIN(sk.bobot) as nilaiMin")
            ->groupBy('penilaian.kriteria_id', 'k.kriteria')
            ->get();

        foreach ($alternatif as $item) {
            foreach ($kriteria as $value) {
                $nilaiMax = $nilaiMaxMin->where('kriteria_id', $value->id)->first()->nilaiMax;
                $nilaiMin = $nilaiMaxMin->where('kriteria_id', $value->id)->first()->nilaiMin;
                $subKriteria = Penilaian::where('kriteria_id', $value->id)->where('alternatif_id', $item->id)->first()->subKriteria->bobot;

                $nilai = ($value->jenis_kriteria == 'benefit')
                    ? ($subKriteria - $nilaiMin) / ($nilaiMax - $nilaiMin)
                    : ($nilaiMax - $subKriteria) / ($nilaiMax - $nilaiMin);

                NilaiUtility::create([
                    'alternatif_id' => $item->id,
                    'kriteria_id' => $value->id,
                    'nilai' => $nilai,
                ]);
            }
        }

        return to_route('nilai-utility')->with('success', 'Perhitungan Nilai Utility Berhasil Dilakukan');
    }

    public function indexNilaiAkhir()
    {
        $title = "Nilai Akhir";
        $nilaiAkhir = NilaiAkhirResource::collection(NilaiAkhir::orderBy('alternatif_id', 'asc')->orderBy('kriteria_id', 'asc')->get());
        $alternatif = AlternatifResource::collection(Alternatif::orderBy('kode', 'asc')->get());
        $kriteria = KriteriaResource::collection(Kriteria::orderBy('kode', 'asc')->get());
        return view('dashboard.nilai-akhir.index', compact('title', 'nilaiAkhir', 'alternatif', 'kriteria'));
    }

    public function perhitunganNilaiAkhir()
    {
        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $alternatif = Alternatif::orderBy('kode', 'asc')->get();
        NilaiAkhir::truncate();

        foreach ($alternatif as $item) {
            foreach ($kriteria as $value) {
                $normalisasiBobot = NormalisasiBobot::where('kriteria_id', $value->id)->first()->normalisasi;
                $nilaiUtility = NilaiUtility::where('kriteria_id', $value->id)->where('alternatif_id', $item->id)->first()->nilai;
                $nilai = $normalisasiBobot * $nilaiUtility;

                NilaiAkhir::create([
                    'alternatif_id' => $item->id,
                    'kriteria_id' => $value->id,
                    'nilai' => $nilai,
                ]);
            }
        }

        return to_route('nilai-akhir')->with('success', 'Perhitungan Nilai Akhir Berhasil Dilakukan');
    }

    public function indexPerhitungan()
    {
        $title = "Perhitungan Metode";

        $normalisasiBobot = NormalisasiBobotResource::collection(NormalisasiBobot::with('kriteria')->orderBy('kriteria_id', 'asc')->get());
        $nilaiUtility = NilaiUtilityResource::collection(NilaiUtility::orderBy('alternatif_id', 'asc')->orderBy('kriteria_id', 'asc')->get());
        $nilaiAkhir = NilaiAkhirResource::collection(NilaiAkhir::orderBy('alternatif_id', 'asc')->orderBy('kriteria_id', 'asc')->get());

        $alternatif = AlternatifResource::collection(Alternatif::orderBy('kode', 'asc')->get());
        $kriteria = KriteriaResource::collection(Kriteria::orderBy('kode', 'asc')->get());
        $sumBobotKriteria = $kriteria->sum('bobot');

        return view('dashboard.perhitungan.index', compact('title', 'normalisasiBobot', 'nilaiUtility', 'nilaiAkhir', 'alternatif', 'kriteria', 'sumBobotKriteria'));
    }

    public function perhitunganMetode()
    {
        $this->perhitunganNormalisasiBobot();
        $this->perhitunganNilaiUtility();
        $perhitungan = $this->perhitunganNilaiAkhir();

        if ($perhitungan) {
            return to_route('perhitungan')->with('success', 'Perhitungan Metode SMART Berhasil Dilakukan');
        } else {
            return to_route('perhitungan')->with('error', 'Perhitungan Metode SMART Gagal Dilakukan');
        }
    }
}
