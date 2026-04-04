<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClusterUsahaController;
use App\Http\Controllers\KoordinatorController;

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/', function () {return redirect('/login');});
// DATA USER
Route::get('/admin/users', [DashboardController::class, 'users'])->name('admin.users');
Route::post('/admin/users/store', [DashboardController::class, 'store'])->name('admin.users.store');
// LOGOUT
Route::post('/logout', [AuthController::class, 'logout']);

// DASHBOARD
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/dashboard/ketua', [DashboardController::class, 'ketua']);
    Route::get('/dashboard/pendamping', [DashboardController::class, 'pendamping']);
    Route::get('/dashboard/koordinator', [DashboardController::class, 'koordinator']);
    Route::get('/dashboard/tim', [DashboardController::class, 'tim']);
    Route::get('/dashboard/dinas', [DashboardController::class, 'dinas']);

    // Cluster usaha
    Route::resource('cluster_usaha', ClusterUsahaController::class);

    // KELOLA DATA KOORDINATOR
    Route::get('/admin/koordinator', [KoordinatorController::class,'index'])->name('koordinator.index');
    Route::post('/admin/koordinator/store', [KoordinatorController::class,'store'])->name('koordinator.store');
    Route::delete('/admin/koordinator/{id}', [KoordinatorController::class,'destroy'])->name('koordinator.delete');
});