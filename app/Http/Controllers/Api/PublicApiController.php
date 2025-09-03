<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kriteria;
use App\Models\NilaiAkhir;
use App\Models\Periode;
use Illuminate\Http\Request;

class PublicApiController extends Controller
{
    /**
     * Get statistik untuk chart
     */
    public function getStatistik(Request $request)
    {
        $periode = Periode::where('is_active', true)->first();
        
        if (!$periode) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada periode aktif'
            ]);
        }
        
        // Get top 10 students
        $topStudents = NilaiAkhir::with('alternatif')
            ->where('periode_id', $periode->id)
            ->orderBy('total', 'desc')
            ->take(10)
            ->get();
            
        // Calculate average per kriteria
        $kriteria = Kriteria::orderBy('urutan_prioritas')->get();
        $avgPerKriteria = [];
        
        foreach ($kriteria as $k) {
            // Calculate average score for this kriteria
            $avg = \DB::table('penilaians')
                ->join('sub_kriterias', 'penilaians.sub_kriteria_id', '=', 'sub_kriterias.id')
                ->where('penilaians.kriteria_id', $k->id)
                ->where('penilaians.periode_id', $periode->id)
                ->avg('sub_kriterias.nilai_utility');
                
            $avgPerKriteria[] = [
                'kriteria' => $k->nama_kriteria,
                'average' => round($avg, 2)
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'top_students' => $topStudents,
                'avg_per_kriteria' => $avgPerKriteria,
                'total_students' => \App\Models\Alternatif::count(),
                'periode' => $periode->nama_periode
            ]
        ]);
    }
    
    /**
     * Simulate calculation
     */
    public function simulate(Request $request)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|numeric|min:1|max:4'
        ]);
        
        $kriteria = Kriteria::orderBy('urutan_prioritas')->get();
        $totalScore = 0;
        
        foreach ($kriteria as $index => $k) {
            $score = $request->scores[$k->id] ?? 2.5;
            $totalScore += $score * $k->bobot_roc;
        }
        
        // Estimate ranking
        $periode = Periode::where('is_active', true)->first();
        if ($periode) {
            $betterCount = NilaiAkhir::where('periode_id', $periode->id)
                ->where('total', '>=', $totalScore)
                ->count();
                
            $totalCount = NilaiAkhir::where('periode_id', $periode->id)->count();
            
            return response()->json([
                'success' => true,
                'total_score' => round($totalScore, 3),
                'estimated_rank' => $betterCount + 1,
                'total_students' => $totalCount,
                'percentage' => round((($totalCount - $betterCount) / $totalCount) * 100, 1)
            ]);
        }
        
        return response()->json([
            'success' => true,
            'total_score' => round($totalScore, 3),
            'message' => 'Tidak dapat memperkirakan peringkat karena tidak ada data periode aktif'
        ]);
    }
}
