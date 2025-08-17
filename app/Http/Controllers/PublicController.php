<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NilaiAkhir;
use App\Models\Periode;

class PublicController extends Controller
{
    public function hasilPublik(Request $request)
    {
        // Get periode aktif
        $periodeAktif = Periode::getActive();
        
        // Get filter kelas
        $kelasFilter = $request->get('kelas', 'all');
        
        // Get list kelas
        $kelasList = ['6A', '6B', '6C', '6D'];
        
        // Query nilai akhir
        $nilaiAkhir = collect();
        
        if ($periodeAktif) {
            $nilaiAkhir = NilaiAkhir::with('alternatif')
                ->where('periode_id', $periodeAktif->id)
                ->when($kelasFilter && $kelasFilter !== 'all', function($q) use ($kelasFilter) {
                    $q->whereHas('alternatif', function($query) use ($kelasFilter) {
                        $query->where('kelas', $kelasFilter);
                    });
                })
                ->orderByDesc('total')
                ->get();
            
            // Recalculate ranking untuk kelas yang difilter
            $rank = 1;
            foreach ($nilaiAkhir as $row) {
                $row->peringkat_kelas = $rank++;
            }
        }
        
        return view('public.hasil', compact(
            'nilaiAkhir',
            'kelasFilter',
            'kelasList',
            'periodeAktif'
        ));
    }
}

