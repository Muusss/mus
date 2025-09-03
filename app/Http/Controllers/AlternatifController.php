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
use App\Models\Periode;

class AlternatifController extends Controller
{
    /** Helper sederhana: cek wali_kelas */
    private function isWali(?User $u): bool
    {
        return $u && ($u->role ?? null) === 'wali_kelas';
    }

    /** LIST */
    public function index(Request $request): View
    {
        $title = 'Data Siswa';
        $user  = Auth::user();
        
        // Get filter kelas
        $kelasFilter = $request->get('kelas', 'all');
        
        // Force filter untuk wali kelas
        if ($this->isWali($user) && $user->kelas) {
            $kelasFilter = $user->kelas;
        }

        $q = Alternatif::query();
        
        // Apply filter
        if ($kelasFilter && $kelasFilter !== 'all') {
            $q->where('kelas', $kelasFilter);
        }

        $alternatif = $q->orderBy('kelas', 'asc')->orderBy('nis', 'asc')->get();
        
        $kelasList = ['6A', '6B', '6C', '6D'];
        
        // Calculate statistics per class
        $stats = [];
        foreach($kelasList as $kelas) {
            $stats[$kelas] = [
                'total' => Alternatif::where('kelas', $kelas)->count(),
                'lk' => Alternatif::where('kelas', $kelas)->where('jk', 'Lk')->count(),
                'pr' => Alternatif::where('kelas', $kelas)->where('jk', 'Pr')->count(),
            ];
        }

        return view('dashboard.alternatif.index', compact('title', 'alternatif', 'kelasFilter', 'kelasList', 'stats'));
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
        $periodeAktif = Periode::getActive();
        
        if (!$periodeAktif) {
            return redirect()->route('periode')
                ->with('error', 'Silakan aktifkan periode terlebih dahulu');
        }
        
        Kriteria::hitungROC();
        Penilaian::normalisasiSMART($periodeAktif->id, Auth::user());
        NilaiAkhir::hitungTotal($periodeAktif->id, Auth::user());

        return redirect()->route('alternatif')->with('success', 
            "Perhitungan ROC + SMART untuk {$periodeAktif->nama_periode} selesai");
    }

    // Method untuk halaman perhitungan
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