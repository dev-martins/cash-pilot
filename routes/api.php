<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login/step/one', [AuthController::class, 'login']);
    Route::post('/login/step/two', [AuthController::class, 'verifyOTP']);
});

Route::middleware(['auth:api', 'security'])->group(function () {
    Route::prefix('account')->group(function () {
        Route::get('/balance', [AccountController::class, 'balance']);
        Route::post('/transfer', [AccountController::class, 'transfer']);
    });
});
