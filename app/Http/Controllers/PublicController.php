<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // <-- yang benar
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\Alternatif;
use App\Models\NilaiAkhir;
use App\Models\Periode;
use App\Models\Penilaian;

class PublicController extends Controller
{
    public function welcome()
    {
        $periode       = Periode::where('is_active', true)->first();
        $totalSiswa    = Alternatif::count();
        $totalKriteria = Kriteria::count();

        return view('welcome', compact('periode', 'totalSiswa', 'totalKriteria'));
    }

    // Alias agar cocok dengan route lama (hasil.publik)
    public function hasilPublik()
    {
        return $this->hasil();
    }

    public function hasil()
    {
        $periode = Periode::where('is_active', true)->first();

        $nilaiAkhir = $periode
            ? NilaiAkhir::with('alternatif')
                ->where('periode_id', $periode->id)
                ->orderByDesc('total')
                ->get()
            : collect();

        return view('public.hasil', compact('nilaiAkhir', 'periode'));
    }

    public function informasiSPK()
    {
        $kriteria      = Kriteria::with('subKriteria')->orderBy('urutan_prioritas')->get();
        $periodeAktif  = Periode::where('is_active', true)->first();
        $statistik     = $this->generateStatistik();

        return view('public.informasi', compact('kriteria', 'periodeAktif', 'statistik'));
    }

    public function cariAnak(Request $request)
    {
        $request->validate([
            'nis'  => 'required',
            'nama' => 'required',
        ]);

        // cari case-insensitive
        $siswa = Alternatif::where('nis', $request->nis)
            ->whereRaw('LOWER(nama) = ?', [mb_strtolower($request->nama)])
            ->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan. Pastikan NIS dan nama yang dimasukkan benar.',
            ]);
        }

        $periodeAktif = Periode::where('is_active', true)->first();
        if (!$periodeAktif) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada periode penilaian aktif.',
            ]);
        }

        $nilaiAkhir = NilaiAkhir::where('alternatif_id', $siswa->id)
            ->where('periode_id', $periodeAktif->id)
            ->first();

        if (!$nilaiAkhir) {
            return response()->json([
                'success' => false,
                'message' => 'Belum ada data penilaian untuk siswa ini.',
            ]);
        }

        $peringkatKelas = NilaiAkhir::join('alternatifs', 'nilai_akhirs.alternatif_id', '=', 'alternatifs.id')
            ->where('alternatifs.kelas', $siswa->kelas)
            ->where('nilai_akhirs.periode_id', $periodeAktif->id)
            ->where('nilai_akhirs.total', '>=', $nilaiAkhir->total)
            ->count();

        $totalKelas = Alternatif::where('kelas', $siswa->kelas)->count();

        $peringkatSekolah = NilaiAkhir::where('periode_id', $periodeAktif->id)
            ->where('total', '>=', $nilaiAkhir->total)
            ->count();

        $totalSekolah = Alternatif::count();

        $breakdown = $this->getBreakdownScores($siswa->id, $periodeAktif->id);

        return response()->json([
            'success'           => true,
            'siswa'             => ['nama' => $siswa->nama, 'nis' => $siswa->nis, 'kelas' => $siswa->kelas],
            'peringkat_kelas'   => $peringkatKelas,
            'total_kelas'       => $totalKelas,
            'peringkat_sekolah' => $peringkatSekolah,
            'total_sekolah'     => $totalSekolah,
            'breakdown'         => $breakdown,
        ]);
    }

    private function generateStatistik(): array
    {
        $periodeAktif = Periode::where('is_active', true)->first();
        if (!$periodeAktif) {
            return ['total_siswa' => 0, 'rata_rata_per_kriteria' => []];
        }

        $kriteria = Kriteria::orderBy('urutan_prioritas')->get();
        $rataRataPerKriteria = [];

        $hasPenilaianUtility = Schema::hasColumn('penilaians', 'nilai_utility');
        $hasSubUtility       = Schema::hasColumn('sub_kriterias', 'nilai_utility');
        $hasSubNilai         = Schema::hasColumn('sub_kriterias', 'nilai');

        foreach ($kriteria as $k) {
            if ($hasPenilaianUtility) {
                $avg = Penilaian::where('kriteria_id', $k->id)
                    ->where('periode_id', $periodeAktif->id)
                    ->avg('nilai_utility');
            } elseif ($hasSubUtility || $hasSubNilai) {
                $col = $hasSubUtility ? 'nilai_utility' : 'nilai';
                $avg = Penilaian::where('penilaians.kriteria_id', $k->id)
                    ->where('penilaians.periode_id', $periodeAktif->id)
                    ->join('sub_kriterias as sk', 'penilaians.sub_kriteria_id', '=', 'sk.id')
                    ->avg("sk.$col");
            } else {
                $avg = 0;
            }

            $rataRataPerKriteria[] = round((float) ($avg ?? 0), 2);
        }

        return [
            'total_siswa'              => Alternatif::count(),
            'rata_rata_per_kriteria'   => $rataRataPerKriteria,
        ];
    }

    private function getBreakdownScores(int $alternatifId, int $periodeId): array
    {
        $kriteria   = Kriteria::orderBy('urutan_prioritas')->get();
        $breakdown  = [];

        $hasSubUtility = Schema::hasColumn('sub_kriterias', 'nilai_utility');
        $hasSubNilai   = Schema::hasColumn('sub_kriterias', 'nilai');
        $col           = $hasSubUtility ? 'nilai_utility' : ($hasSubNilai ? 'nilai' : null);

        foreach ($kriteria as $k) {
            $penilaian = Penilaian::where('alternatif_id', $alternatifId)
                ->where('kriteria_id', $k->id)
                ->where('periode_id', $periodeId)
                ->with('subKriteria')
                ->first();

            if ($penilaian && $penilaian->subKriteria && $col) {
                $nilai  = (float) $penilaian->subKriteria->$col;
                $status = $nilai >= 3.5 ? 'Sangat Baik'
                    : ($nilai >= 2.5 ? 'Baik'
                    : ($nilai >= 1.5 ? 'Cukup Baik' : 'Perlu Bimbingan'));

                $breakdown[] = [
                    'kriteria' => $k->nama_kriteria,
                    'status'   => $status,
                    'saran'    => $this->getSaran($k->nama_kriteria, $nilai),
                ];
            } else {
                $breakdown[] = [
                    'kriteria' => $k->nama_kriteria,
                    'status'   => 'Belum dinilai',
                    'saran'    => 'Menunggu penilaian dari guru',
                ];
            }
        }

        return $breakdown;
    }

    private function getSaran(string $kriteria, float $nilai): string
    {
        $saran = [
            'Nilai Raport Umum' => [
                'rendah' => 'Perlu bimbingan belajar tambahan dan pendampingan PR',
                'sedang' => 'Tingkatkan konsistensi belajar di rumah',
                'tinggi' => 'Pertahankan prestasi dan bantu teman yang kesulitan',
            ],
            'Nilai Raport Diniyah' => [
                'rendah' => 'Perlu mengulang pelajaran agama di rumah',
                'sedang' => 'Perbanyak membaca buku agama',
                'tinggi' => 'Pertahankan dan amalkan ilmu yang dipelajari',
            ],
            'Akhlak' => [
                'rendah' => 'Perlu bimbingan intensif dari orang tua dan guru',
                'sedang' => 'Tingkatkan kesadaran berakhlak mulia',
                'tinggi' => 'Pertahankan dan jadilah teladan bagi teman',
            ],
            'Hafalan Al-Quran' => [
                'rendah' => 'Mulai dengan target kecil, 1 ayat per hari',
                'sedang' => 'Tingkatkan murajaah dan tambah hafalan baru',
                'tinggi' => 'Pertahankan hafalan dan bantu teman menghafal',
            ],
            'Kehadiran' => [
                'rendah' => 'Evaluasi penyebab sering tidak hadir',
                'sedang' => 'Tingkatkan kedisiplinan waktu',
                'tinggi' => 'Pertahankan kedisiplinan yang baik',
            ],
            'Ekstrakurikuler' => [
                'rendah' => 'Pilih minimal 1 ekstrakurikuler sesuai minat',
                'sedang' => 'Tingkatkan partisipasi dan keaktifan',
                'tinggi' => 'Pertahankan prestasi dan motivasi teman',
            ],
        ];

        $level = $nilai < 2 ? 'rendah' : ($nilai < 3 ? 'sedang' : 'tinggi');
        return $saran[$kriteria][$level] ?? 'Terus tingkatkan prestasi';
    }
}
