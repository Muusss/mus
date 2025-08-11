<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

use App\Models\Alternatif;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\NilaiAkhir;
use App\Models\User;

class AlternatifController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Alternatif::class, 'alternatif');
    }

    /** Helper: cek apakah user wali_kelas */
    private function userIsWaliKelas(?User $user): bool
    {
        return $user !== null && ($user->role ?? null) === 'wali_kelas';
    }

    public function index()
    {
        $title = 'Data Siswa';
        $user  = Auth::user();

        $q = Alternatif::query();

        if ($this->userIsWaliKelas($user)) {
            $q->where('kelas', $user->kelas);
        }

        $alternatif = $q->orderBy('nis', 'asc')->get();

        return view('dashboard.alternatif.index', compact('title', 'alternatif'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nis'         => ['required','string','max:30','unique:alternatif,nis'],
            'nama_siswa'  => ['required','string','max:100'],
            'jk'          => ['required', Rule::in(['Lk','Pr'])],
            'kelas'       => ['required', Rule::in(['6A','6B','6C','6D'])],
        ]);

        if ($this->userIsWaliKelas($user)) {
            $data['kelas'] = $user->kelas; // kunci ke kelas wali
        }

        $ok = Alternatif::create($data);

        return to_route('alternatif')->with($ok ? 'success' : 'error', $ok ? 'Siswa berhasil disimpan' : 'Siswa gagal disimpan');
    }

    public function edit(Request $request)
    {
        $alternatif = Alternatif::findOrFail($request->alternatif_id);
        $this->authorize('view', $alternatif);

        return response()->json($alternatif);
    }

    public function update(Request $request)
    {
        $alternatif = Alternatif::findOrFail($request->id);
        $this->authorize('update', $alternatif);

        $user = Auth::user();

        $data = $request->validate([
            'nis'         => ['required','string','max:30', Rule::unique('alternatif','nis')->ignore($alternatif->id)],
            'nama_siswa'  => ['required','string','max:100'],
            'jk'          => ['required', Rule::in(['Lk','Pr'])],
            'kelas'       => ['required', Rule::in(['6A','6B','6C','6D'])],
        ]);

        if ($this->userIsWaliKelas($user)) {
            $data['kelas'] = $user->kelas; // kunci ke kelas wali
        }

        $ok = $alternatif->update($data);

        return to_route('alternatif')->with($ok ? 'success' : 'error', $ok ? 'Siswa berhasil diperbarui' : 'Siswa gagal diperbarui');
    }

    public function delete(Request $request)
    {
        $alternatif = Alternatif::findOrFail($request->id);
        $this->authorize('delete', $alternatif);

        $ok = $alternatif->delete();

        return to_route('alternatif')->with($ok ? 'success' : 'error', $ok ? 'Siswa berhasil dihapus' : 'Siswa gagal dihapus');
    }

    public function perhitunganNilaiAkhir()
    {
        Kriteria::hitungROC();
        Penilaian::normalisasiSMART(null, Auth::user());
        NilaiAkhir::hitungTotal(null, Auth::user());

        return to_route('alternatif')->with('success', 'Perhitungan ROC + SMART selesai');
    }

    public function indexPerhitungan()
    {
        $title = "Hasil Perhitungan ROC + SMART";
        $user  = Auth::user();

        $kriteria = Kriteria::orderBy('kode', 'asc')->get();
        $sumBobotKriteria = (float) $kriteria->sum('bobot_roc');

        $hasil = NilaiAkhir::query()
            ->when($this->userIsWaliKelas($user), function ($q) use ($user) {
                $q->whereHas('alternatif', fn($s) => $s->where('kelas', $user->kelas));
            })
            ->with('alternatif')
            ->orderByDesc('total')
            ->get();

        $alternatif = Alternatif::query()
            ->when($this->userIsWaliKelas($user), function ($q) use ($user) {
                $q->where('kelas', $user->kelas);
            })
            ->orderBy('nis','asc')
            ->get();

        return view('dashboard.perhitungan.index', compact('title', 'kriteria', 'sumBobotKriteria', 'hasil', 'alternatif'));
    }

    public function perhitunganMetode()
    {
        return $this->perhitunganNilaiAkhir();
    }
}
