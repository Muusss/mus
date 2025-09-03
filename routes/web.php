<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\SMARTController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

Route::get('/', [PublicController::class, 'welcome'])->name('welcome');
Route::get('/informasi-spk', [PublicController::class, 'informasiSPK'])->name('public.informasi');
Route::get('/hasil-peringkat', [PublicController::class, 'hasilPublik'])->name('hasil.publik');
Route::post('/cari-anak', [PublicController::class, 'cariAnak'])->name('public.cari-anak');

// Download public information as PDF
Route::get('/informasi-spk/download', [PublicController::class, 'downloadInformasi'])->name('public.informasi.download');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    /*
    |--------------------------------------------------------------------------
    | Admin Only Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        // User management
        Route::resource('users', UserController::class);
        
        // Master data management
        Route::resource('kriteria', KriteriaController::class);
        Route::resource('subkriteria', SubKriteriaController::class);
        Route::resource('alternatif', AlternatifController::class);
        Route::resource('periode', PeriodeController::class);
        
        // Activate periode
        Route::post('/periode/{periode}/activate', [PeriodeController::class, 'activate'])->name('periode.activate');
        
        // Import data
        Route::post('/kriteria/import', [KriteriaController::class, 'import'])->name('kriteria.import');
        Route::post('/subkriteria/import', [SubKriteriaController::class, 'import'])->name('subkriteria.import');
        Route::post('/alternatif/import', [AlternatifController::class, 'import'])->name('alternatif.import');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Teacher Routes (Admin & Guru)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,guru')->group(function () {
        // Penilaian
        Route::resource('penilaian', PenilaianController::class);
        Route::get('/penilaian/kelas/{kelas}', [PenilaianController::class, 'indexByKelas'])->name('penilaian.kelas');
        Route::post('/penilaian/import', [PenilaianController::class, 'import'])->name('penilaian.import');
        
        // SMART Calculation
        Route::get('/perhitungan', [SMARTController::class, 'index'])->name('perhitungan.index');
        Route::post('/perhitungan/hitung', [SMARTController::class, 'hitung'])->name('perhitungan.hitung');
        
        // View results
        Route::get('/normalisasi-bobot', [SMARTController::class, 'normalisasiBobot'])->name('normalisasi-bobot.index');
        Route::get('/nilai-utility', [SMARTController::class, 'nilaiUtility'])->name('nilai-utility.index');
        Route::get('/nilai-akhir', [SMARTController::class, 'nilaiAkhir'])->name('nilai-akhir.index');
        Route::get('/hasil-akhir', [SMARTController::class, 'hasilAkhir'])->name('hasil-akhir.index');
        
        // Export PDF
        Route::get('/hasil-akhir/pdf', [PDFController::class, 'hasilAkhir'])->name('hasil-akhir.pdf');
    });
});

// Authentication routes
require __DIR__.'/auth.php';
