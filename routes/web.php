<?php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\PeriodeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\SMARTController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Routes (tanpa auth)
Route::get('/', function () {
    return view('welcome');
});

// Halaman Publik - bisa diakses tanpa login
Route::get('/hasil-peringkat', [PublicController::class, 'hasilPublik'])->name('hasil.publik');
Route::get('/informasi', [PublicController::class, 'informasi'])->name('informasi');
Route::get('/cek-nilai', [PublicController::class, 'cekNilai'])->name('cek.nilai');
Route::post('/cek-nilai', [PublicController::class, 'prosesNilai'])->name('cek.nilai.proses');

// Auth Routes
Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/hasil-akhir', [DashboardController::class, 'hasilAkhir'])->name('hasil-akhir');

    // User Management Routes (Admin Only)
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::post('/users/update', [UserController::class, 'update'])->name('users.update');
        Route::post('/users/delete', [UserController::class, 'delete'])->name('users.delete');
        Route::post('/users/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    });
        
    // Periode Routes
    Route::get('/periode', [PeriodeController::class, 'index'])->name('periode');
    Route::middleware(['admin'])->group(function () {
        Route::post('/periode/store', [PeriodeController::class, 'store'])->name('periode.store');
        Route::post('/periode/{id}/activate', [PeriodeController::class, 'setActive'])->name('periode.setActive');
        Route::post('/periode/delete', [PeriodeController::class, 'delete'])->name('periode.delete');
    });

    // PDF Route
    Route::get('/pdf-hasil-akhir', [PDFController::class, 'pdf_hasil'])->name('pdf.hasilAkhir');

    // Kriteria Routes - INDEX bisa diakses semua user
    Route::get('/kriteria', [KriteriaController::class, 'index'])->name('kriteria');
    
    // Kriteria Routes - Admin Only
    Route::middleware(['admin'])->group(function () {
        Route::post('/kriteria/store', [KriteriaController::class, 'store'])->name('kriteria.store');
        Route::get('/kriteria/edit', [KriteriaController::class, 'edit'])->name('kriteria.edit');
        Route::post('/kriteria/update', [KriteriaController::class, 'update'])->name('kriteria.update');
        Route::post('/kriteria/delete', [KriteriaController::class, 'delete'])->name('kriteria.delete');
    });

    // Sub-Kriteria Routes
    Route::get('/sub-kriteria', [SubKriteriaController::class, 'index'])->name('subkriteria');
    Route::middleware(['admin'])->group(function () {
        Route::post('/sub-kriteria/store', [SubKriteriaController::class, 'store'])->name('subkriteria.store');
        Route::get('/sub-kriteria/edit', [SubKriteriaController::class, 'edit'])->name('subkriteria.edit');
        Route::post('/sub-kriteria/update', [SubKriteriaController::class, 'update'])->name('subkriteria.update');
        Route::post('/sub-kriteria/delete', [SubKriteriaController::class, 'delete'])->name('subkriteria.delete');
    });

    // Alternatif Routes
    Route::prefix('alternatif')->group(function() {
        Route::get('/', [AlternatifController::class, 'index'])->name('alternatif');
        Route::get('/lihat', [AlternatifController::class, 'show'])->name('alternatif.show');
        Route::post('/simpan', [AlternatifController::class, 'store'])->name('alternatif.store');
        Route::get('/ubah', [AlternatifController::class, 'edit'])->name('alternatif.edit');
        Route::post('/ubah', [AlternatifController::class, 'update'])->name('alternatif.update');
        Route::post('/hapus', [AlternatifController::class, 'delete'])->name('alternatif.delete');
        Route::post('/impor', [AlternatifController::class, 'import'])->name('alternatif.import');
    });

    // Penilaian Routes
    Route::prefix('penilaian')->group(function () {
        Route::get('/', [PenilaianController::class, 'index'])->name('penilaian');
        Route::get('/{id}/ubah', [PenilaianController::class, 'edit'])->name('penilaian.edit');
        Route::post('/{id}/ubah', [PenilaianController::class, 'update'])->name('penilaian.update');
        Route::get('/kelas/edit', [PenilaianController::class, 'editKelas'])->name('penilaian.editKelas');
        Route::post('/kelas/update', [PenilaianController::class, 'updateKelas'])->name('penilaian.updateKelas');
    });

    // SMART (Metode Perhitungan) Routes
    Route::prefix('smart')->group(function() {
        Route::get('/normalisasi-bobot', [SMARTController::class, 'indexNormalisasiBobot'])->name('normalisasi-bobot');
        Route::post('/normalisasi-bobot', [SMARTController::class, 'perhitunganNormalisasiBobot'])->name('normalisasi-bobot.perhitungan');
        
        Route::get('/nilai-utility', [SMARTController::class, 'indexNilaiUtility'])->name('nilai-utility');
        Route::post('/nilai-utility', [SMARTController::class, 'perhitunganNilaiUtility'])->name('nilai-utility.perhitungan');
        
        Route::get('/nilai-akhir', [SMARTController::class, 'indexNilaiAkhir'])->name('nilai-akhir');
        Route::post('/nilai-akhir', [SMARTController::class, 'perhitunganNilaiAkhir'])->name('nilai-akhir.perhitungan');
        
        Route::get('/perhitungan', [SMARTController::class, 'indexPerhitungan'])->name('perhitungan');
        Route::post('/perhitungan', [SMARTController::class, 'perhitunganMetode'])->name('perhitungan.smart');
    });
});

require __DIR__.'/auth.php';