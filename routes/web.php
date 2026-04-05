<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\KubeController;
use App\Http\Controllers\AnggotaKubeController;

use App\Http\Controllers\PembagianPendampingController;

use App\Http\Controllers\ClusterUsahaController;
use App\Http\Controllers\JenisBantuanController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\PencairanBantuanController;


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

    // PENCAIRAN BANTUAN
    Route::get('/admin/pencairan_bantuan', [PencairanBantuanController::class,'index'])->name('admin.pencairan_bantuan.index');
    Route::get('/admin/jenis_bantuan', [JenisBantuanController::class,'index'])->name('admin.alur_bantuan.jenis_bantuan.index');
    Route::post('/admin/jenis_bantuan/tambah', [JenisBantuanController::class,'tambah'])->name('admin.alur_bantuan.jenis_bantuan.tambah');
    
    // KELOLA DATA KOORDINATOR
    Route::get('/admin/koordinator', [KoordinatorController::class,'index'])->name('koordinator.index');
    Route::post('/admin/koordinator/store', [KoordinatorController::class,'store'])->name('koordinator.store');
    Route::delete('/admin/koordinator/{id}', [KoordinatorController::class,'destroy'])->name('koordinator.delete');
});
