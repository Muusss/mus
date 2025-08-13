<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        $title = 'Data Periode';
        $periodes = Periode::orderBy('tahun_ajaran', 'desc')
                          ->orderBy('semester', 'desc')
                          ->get();
        
        return view('dashboard.periode.index', compact('title', 'periodes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'semester' => ['required', 'in:1,2'],
            'tahun_ajaran' => ['required', 'integer', 'min:2020', 'max:2100'],
        ]);

        // Generate nama periode
        $data['nama_periode'] = "Semester " . ($data['semester'] == 1 ? 'Ganjil' : 'Genap') . 
                                " {$data['tahun_ajaran']}/" . ($data['tahun_ajaran'] + 1);

        // Cek duplikasi
        $exists = Periode::where('semester', $data['semester'])
                        ->where('tahun_ajaran', $data['tahun_ajaran'])
                        ->exists();
        
        if ($exists) {
            return back()->with('error', 'Periode sudah ada');
        }

        Periode::create($data);
        
        return redirect()->route('periode')->with('success', 'Periode berhasil ditambahkan');
    }

    public function setActive(Request $request, $id)
    {
        // Set semua periode menjadi tidak aktif
        Periode::query()->update(['is_active' => false]);
        
        // Set periode yang dipilih menjadi aktif
        $periode = Periode::findOrFail($id);
        $periode->is_active = true;
        $periode->save();
        
        return redirect()->route('periode')
            ->with('success', "Periode {$periode->nama_periode} telah diaktifkan");
    }

    public function delete(Request $request)
    {
        $periode = Periode::findOrFail($request->id);
        
        // Cek apakah ada data penilaian
        if ($periode->penilaians()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus periode yang sudah memiliki data penilaian');
        }
        
        $periode->delete();
        
        return redirect()->route('periode')->with('success', 'Periode berhasil dihapus');
    }
}