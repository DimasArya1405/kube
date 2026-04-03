<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/', function () {
    return redirect('/login');
});

// LOGOUT
Route::post('/logout', [AuthController::class, 'logout']);

// DASHBOARD
Route::middleware('auth')->group(function () {

    Route::get('/dashboard/admin', [DashboardController::class, 'admin']);
    Route::get('/dashboard/ketua', [DashboardController::class, 'ketua']);
    Route::get('/dashboard/pendamping', [DashboardController::class, 'pendamping']);
    Route::get('/dashboard/koordinator', [DashboardController::class, 'koordinator']);
    Route::get('/dashboard/tim', [DashboardController::class, 'tim']);
    Route::get('/dashboard/dinas', [DashboardController::class, 'dinas']);
});