<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;

class AlternatifController extends Controller
{
    // (opsional) aktifkan kalau sudah punya AlternatifPolicy
    // public function __construct()
    // {
    //     $this->authorizeResource(Alternatif::class, 'alternatif');
    // }

    /** Helper sederhana: cek wali_kelas */
    private function isWali(?User $u): bool
    {
        return $u && ($u->role ?? null) === 'wali_kelas';
    }

    /** LIST */
    public function index(): View
    {
        $title = 'Data Siswa';
        $user  = Auth::user();

        $q = Alternatif::query();

        // filter wali_kelas HANYA kalau kelas-nya terisi
        if ($this->isWali($user) && $user->kelas) {
            $q->where('kelas', $user->kelas);
        }

        $alternatif = $q->orderBy('nis', 'asc')->get(); // <-- no named args

        return view('dashboard.alternatif.index', compact('title', 'alternatif'));
    }

    /** STORE */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $data = $request->validate([
            'nis'        => ['required','string','max:30','unique:alternatifs,nis'],
            'nama_siswa' => ['required','string','max:100'],
            'jk'         => ['required', Rule::in(['Lk','Pr'])],
            'kelas'      => ['required', Rule::in(['6A','6B','6C','6D'])],
        ]);

        if ($this->isWali($user) && $user->kelas) {
            $data['kelas'] = $user->kelas; // wali hanya bisa ke kelasnya
        }

        $ok = Alternatif::create($data);

        return to_route('alternatif')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Siswa berhasil disimpan' : 'Siswa gagal disimpan'
        );
    }

    /** EDIT (AJAX) */
    public function edit(Request $request)
    {
        $row = Alternatif::findOrFail($request->alternatif_id);
        return response()->json($row);
    }

    /** UPDATE */
    public function update(Request $request): RedirectResponse
    {
        $row  = Alternatif::findOrFail($request->id);
        $user = Auth::user();

        $data = $request->validate([
            'nis'        => ['required','string','max:30', Rule::unique('alternatifs','nis')->ignore($row->id)],
            'nama_siswa' => ['required','string','max:100'],
            'jk'         => ['required', Rule::in(['Lk','Pr'])],
            'kelas'      => ['required', Rule::in(['6A','6B','6C','6D'])],
        ]);

        if ($this->isWali($user) && $user->kelas) {
            $data['kelas'] = $user->kelas;
        }

        $ok = $row->update($data);

        return to_route('alternatif')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Siswa berhasil diperbarui' : 'Siswa gagal diperbarui'
        );
    }

    /** DELETE */
    public function delete(Request $request): RedirectResponse
    {
        $row = Alternatif::findOrFail($request->id);
        $ok  = $row->delete();

        return to_route('alternatif')->with(
            $ok ? 'success' : 'error',
            $ok ? 'Siswa berhasil dihapus' : 'Siswa gagal dihapus'
        );
    }

    /** === PERHITUNGAN ROC + SMART === */
    public function perhitunganNilaiAkhir(): RedirectResponse
    {
        Kriteria::hitungROC();
        Penilaian::normalisasiSMART(null, Auth::user());
        NilaiAkhir::hitungTotal(null, Auth::user());

        return to_route('alternatif')->with('success', 'Perhitungan ROC + SMART selesai');
    }

    // Tambahkan method ini di AlternatifController
    public function indexPerhitungan()
    {
        $title = "Hasil Perhitungan ROC + SMART";
        $user  = Auth::user();

        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $sumBobotKriteria = (float) $kriteria->sum('bobot_roc');

        $hasil = NilaiAkhir::query()
            ->when($this->isWali($user), function ($q) use ($user) {
                $q->whereHas('alternatif', fn($s) => $s->where('kelas', $user->kelas));
            })
            ->with('alternatif')
            ->orderByDesc('total')
            ->get();

        $alternatif = Alternatif::query()
            ->when($this->isWali($user), fn($q) => $q->where('kelas', $user->kelas))
            ->orderBy('nis','asc')
            ->get();
            
        // Data untuk tabel normalisasi
        $penilaian = Penilaian::with(['alternatif', 'kriteria'])
            ->when($this->isWali($user), function($q) use ($user) {
                $q->whereHas('alternatif', fn($s) => $s->where('kelas', $user->kelas));
            })
            ->get();

        return view('dashboard.perhitungan.index', compact(
            'title','kriteria','sumBobotKriteria','hasil','alternatif','penilaian'
        ));
    }

    public function perhitunganMetode(): RedirectResponse
    {
        return $this->perhitunganNilaiAkhir();
    }
}
