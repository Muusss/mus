<?php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SMARTController;
use App\Http\Controllers\SubKriteriaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // INDEX bisa diakses semua user login (admin & wali_kelas)
    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria');
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/hasil-akhir', [DashboardController::class, 'hasilAkhir'])->name('hasil-akhir');

    // Kriteria Routes
    Route::middleware(['auth','admin'])->group(function () {
        Route::get('/kriteria',            [KriteriaController::class, 'index'])->name('kriteria');
        Route::post('/kriteria/store',     [KriteriaController::class, 'store'])->name('kriteria.store');
        Route::get('/kriteria/edit',       [KriteriaController::class, 'edit'])->name('kriteria.edit');
        Route::post('/kriteria/update',    [KriteriaController::class, 'update'])->name('kriteria.update');
        Route::post('/kriteria/delete',    [KriteriaController::class, 'delete'])->name('kriteria.delete');

        // Proses ROC + SMART (dipakai tombol di halaman kriteria)
        Route::get('/spk/proses',          [KriteriaController::class, 'proses'])->name('spk.proses');
    });

    // Sub-Kriteria Routes
    Route::middleware('auth')->group(function () {
        Route::get('/sub-kriteria',        [SubKriteriaController::class, 'index'])->name('subkriteria');
        Route::post('/sub-kriteria/store', [SubKriteriaController::class, 'store'])->name('subkriteria.store');
        Route::get('/sub-kriteria/edit',   [SubKriteriaController::class, 'edit'])->name('subkriteria.edit');
        Route::post('/sub-kriteria/update',[SubKriteriaController::class, 'update'])->name('subkriteria.update');
        Route::post('/sub-kriteria/delete',[SubKriteriaController::class, 'delete'])->name('subkriteria.delete');
    });

    // Alternatif Routes
    Route::group([
        'prefix' => 'alternatif',
    ], function() {
        Route::get('/', [AlternatifController::class, 'index'])->name('alternatif');
        Route::get('/lihat', [AlternatifController::class, 'show'])->name('alternatif.show');
        Route::post('/simpan', [AlternatifController::class, 'store'])->name('alternatif.store');
        Route::get('/ubah', [AlternatifController::class, 'edit'])->name('alternatif.edit');
        Route::post('/ubah', [AlternatifController::class, 'update'])->name('alternatif.update');
        Route::post('/hapus', [AlternatifController::class, 'delete'])->name('alternatif.delete');
        Route::post('/impor', [AlternatifController::class, 'import'])->name('alternatif.import');
    });

    // Penilaian Routes
    Route::group([
        'prefix' => 'penilaian',
    ], function() {
        Route::get('/', [PenilaianController::class, 'index'])->name('penilaian');
        Route::get('/ubah', [PenilaianController::class, 'edit'])->name('penilaian.edit');
        Route::post('/ubah', [PenilaianController::class, 'update'])->name('penilaian.update');
        Route::post('/impor', [PenilaianController::class, 'import'])->name('penilaian.import');
    });

    // SMART (Metode Perhitungan) Routes
    Route::group([
        'prefix' => 'smart',
    ], function() {
        // Normalisasi Bobot Routes
        Route::get('/normalisasi-bobot', [SMARTController::class, 'indexNormalisasiBobot'])->name('normalisasi-bobot');
        Route::post('/normalisasi-bobot', [SMARTController::class, 'perhitunganNormalisasiBobot'])->name('normalisasi-bobot.perhitungan');

        // Nilai Utility Routes
        Route::get('/nilai-utility', [SMARTController::class, 'indexNilaiUtility'])->name('nilai-utility');
        Route::post('/nilai-utility', [SMARTController::class, 'perhitunganNilaiUtility'])->name('nilai-utility.perhitungan');

        // Nilai Akhir Routes
        Route::get('/nilai-akhir', [SMARTController::class, 'indexNilaiAkhir'])->name('nilai-akhir');
        Route::post('/nilai-akhir', [SMARTController::class, 'perhitunganNilaiAkhir'])->name('nilai-akhir.perhitungan');

        // Perhitungan Metode SMART Routes
        Route::get('/perhitungan', [SMARTController::class, 'indexPerhitungan'])->name('perhitungan');
        Route::post('/perhitungan', [SMARTController::class, 'perhitunganMetode'])->name('perhitungan.smart');

        // PDF Hasil Akhir
        Route::get('/pdf-hasil-akhir', [PDFController::class, 'pdf_hasil'])->name('pdf.hasilAkhir');
    });
});

require __DIR__.'/auth.php';
