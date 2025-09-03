<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PublicApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public API endpoints (no auth required)
Route::prefix('public')->group(function () {
    Route::get('/statistik', [PublicApiController::class, 'getStatistik']);
    Route::post('/simulate', [PublicApiController::class, 'simulate']);
});

// Protected API endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
