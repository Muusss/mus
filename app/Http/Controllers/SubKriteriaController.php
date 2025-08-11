<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubKriteriaRequest;
use App\Http\Resources\KriteriaResource;
use App\Http\Resources\SubKriteriaResource;
use App\Imports\SubKriteriaImport;
use App\Models\Kriteria;
use App\Models\Penilaian;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SubKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = "Sub Kriteria";
        $kriteria = KriteriaResource::collection(Kriteria::orderBy('created_at', 'asc')->get());
        $subKriteria = SubKriteriaResource::collection(SubKriteria::with('kriteria')->orderBy('bobot', 'desc')->get());
        return view('dashboard.sub-kriteria.index', compact('title', 'kriteria', 'subKriteria'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubKriteriaRequest $request)
    {
        $validated = $request->validated();

        // Check if the sub-kriteria already exists for this kriteria
        $subKriteriaExists = SubKriteria::where('sub_kriteria', $request->sub_kriteria)
                                         ->where('kriteria_id', $request->kriteria_id)
                                         ->exists();

        if ($subKriteriaExists) {
            return to_route('sub-kriteria')->with('error', 'Sub Kriteria sudah ada.');
        }

        // Create sub-kriteria
        $simpan = SubKriteria::create($validated);
        if ($simpan) {
            return to_route('sub-kriteria')->with('success', 'Sub Kriteria '.$request->sub_kriteria.' Berhasil Disimpan');
        } else {
            return to_route('sub-kriteria')->with('error', 'Sub Kriteria '.$request->sub_kriteria.' Gagal Disimpan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $subKriteria = SubKriteria::with('kriteria')->find($request->sub_kriteria_id);
        return $subKriteria;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubKriteriaRequest $request)
    {
        $validated = $request->validated();

        // Check if the sub-kriteria already exists for this kriteria
        $subKriteriaExists = SubKriteria::where('sub_kriteria', $request->sub_kriteria)
                                         ->where('kriteria_id', $request->kriteria_id)
                                         ->where('id', '!=', $request->id) // Ensure it's not the same one being updated
                                         ->exists();

        if ($subKriteriaExists) {
            return to_route('sub-kriteria')->with('error', 'Sub Kriteria ini sudah ada.');
        }

        // Update sub-kriteria
        $perbarui = SubKriteria::where('id', $request->id)->update($validated);
        if ($perbarui) {
            return to_route('sub-kriteria')->with('success', 'Sub Kriteria '.$request->sub_kriteria.' Berhasil Diperbarui');
        } else {
            return to_route('sub-kriteria')->with('error', 'Sub Kriteria '.$request->sub_kriteria.' Gagal Diperbarui');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Request $request)
    {
        // Update penilaian yang menggunakan sub_kriteria_id yang akan dihapus
        Penilaian::where('sub_kriteria_id', $request->sub_kriteria_id)->update(['sub_kriteria_id' => null]);
        
        // Delete the sub-kriteria
        $hapus = SubKriteria::where('id', $request->sub_kriteria_id)->delete();
        if ($hapus) {
            return to_route('sub-kriteria')->with('success', 'Sub Kriteria berhasil Dihapus');
        } else {
            return to_route('sub-kriteria')->with('error', 'Sub Kriteria gagal Dihapus');
        }
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
        $import = Excel::import(new SubKriteriaImport, $file);

        if ($import) {
            // Setelah import selesai, hitung bobot jika diperlukan
            return to_route('sub-kriteria')->with('success', 'Sub Kriteria Berhasil Disimpan');
        } else {
            return to_route('sub-kriteria')->with('error', 'Sub Kriteria Gagal Disimpan');
        }
    }
}
