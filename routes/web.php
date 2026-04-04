<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\KubeController;
use App\Http\Controllers\AnggotaKubeController;

use App\Http\Controllers\PembagianPendampingController;

use App\Http\Controllers\ClusterUsahaController;
use App\Http\Controllers\KoordinatorController;


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

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/dashboard/ketua', [DashboardController::class, 'ketua']);
    Route::get('/dashboard/pendamping', [DashboardController::class, 'pendamping']);
    Route::get('/dashboard/koordinator', [DashboardController::class, 'koordinator']);
    Route::get('/dashboard/tim', [DashboardController::class, 'tim']);
    Route::get('/dashboard/dinas', [DashboardController::class, 'dinas']);

    //KELOLA DATA KUBE & ANGGOTA
    Route::resource('kube', KubeController::class);
    Route::resource('anggota_kube', AnggotaKubeController::class);

    //PEMBAGIAN PENDAMPING
    Route::resource('pembagian_pendamping', PembagianPendampingController::class);

    // Cluster usaha
    Route::resource('cluster_usaha', ClusterUsahaController::class);

    // KELOLA DATA KOORDINATOR
    Route::get('/admin/koordinator', [KoordinatorController::class,'index'])->name('koordinator.index');
    Route::post('/admin/koordinator/store', [KoordinatorController::class,'store'])->name('koordinator.store');
    Route::delete('/admin/koordinator/{id}', [KoordinatorController::class,'destroy'])->name('koordinator.delete');
});
