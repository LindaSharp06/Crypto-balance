<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CryptoController;

// Route::middleware('auth:sanctum')->group(function () {
    Route::post('/deposit', [CryptoController::class, 'deposit']);
    Route::post('/withdraw', [CryptoController::class, 'withdraw']);
// });