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
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\KategoriKubeController;
use App\Http\Controllers\PendampingController;
use App\Http\Controllers\RekapKubeController;
use App\Http\Controllers\KunjunganPendampingController;

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/', function () {
    return redirect('/login');
});

// DATA USER
Route::get('/admin/users', [DashboardController::class, 'users'])->name('admin.users');
Route::post('/admin/users/store', [DashboardController::class, 'store'])->name('admin.users.store');
Route::get('/admin/users/edit/{id}', [DashboardController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/update/{id}', [DashboardController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/delete/{id}', [DashboardController::class, 'destroy'])->name('admin.users.delete');

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

    // KELOLA DATA KUBE & ANGGOTA
    Route::resource('kube', KubeController::class);
    Route::resource('anggota_kube', AnggotaKubeController::class);

    // PEMBAGIAN PENDAMPING
    Route::resource('pembagian_pendamping', PembagianPendampingController::class);

    // CLUSTER USAHA
    Route::resource('cluster_usaha', ClusterUsahaController::class);

    // PENCAIRAN BANTUAN
    Route::get('/admin/pencairan_bantuan', [PencairanBantuanController::class, 'index'])->name('admin.pencairan_bantuan.index');
    Route::get('/admin/jenis_bantuan', [JenisBantuanController::class, 'index'])->name('admin.alur_bantuan.jenis_bantuan.index');
    Route::post('/admin/jenis_bantuan/tambah', [JenisBantuanController::class, 'tambah'])->name('admin.alur_bantuan.jenis_bantuan.tambah');

    // KATEGORI KUBE
    Route::resource('/admin/kategorikube', KategoriKubeController::class);

    // KELOLA DATA KOORDINATOR
    Route::get('/admin/koordinator', [KoordinatorController::class, 'index'])->name('koordinator.index');
    Route::post('/admin/koordinator/store', [KoordinatorController::class, 'store'])->name('koordinator.store');
    Route::delete('/admin/koordinator/{id}', [KoordinatorController::class, 'destroy'])->name('koordinator.delete');

    // PELATIHAN
    Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihan.index');
    Route::post('/pelatihan', [PelatihanController::class, 'store'])->name('pelatihan.store');

    // KELOLA MITRA & KOLABORASI
    Route::get('/admin/mitra', [MitraController::class, 'index'])->name('mitra.index');
    Route::get('/admin/mitra/create', [MitraController::class, 'create'])->name('mitra.create');
    Route::post('/admin/mitra/store', [MitraController::class, 'store'])->name('mitra.store');
    Route::get('/admin/mitra/{id}/edit', [MitraController::class, 'edit'])->name('mitra.edit');
    Route::put('/admin/mitra/{id}', [MitraController::class, 'update'])->name('mitra.update');
    Route::delete('/admin/mitra/{id}', [MitraController::class, 'destroy'])->name('mitra.delete');

    // PENDAMPING
    Route::get('/admin/pendamping', [PendampingController::class, 'index'])->name('pendamping.index');
    Route::post('/admin/pendamping/store', [PendampingController::class, 'store'])->name('pendamping.store');
    Route::delete('/admin/pendamping/{id}', [PendampingController::class, 'destroy'])->name('pendamping.delete');
    Route::get('/admin/pendamping/export/pdf', [PendampingController::class, 'exportPdf'])->name('pendamping.export.pdf');
    Route::get('/admin/pendamping/export/excel', [PendampingController::class, 'exportExcel'])->name('pendamping.export.excel');

    // REKAP KUBE
    Route::get('/rekap_kube', [RekapKubeController::class, 'index'])->name('rekap_kube.index');

    //Kelola Data Kunjungan PPendamping
    Route::get('/pendamping/kunjungan_pendamping', [KunjunganPendampingController::class, 'index'])->name('kunjungan.index');
    Route::post('pendamping/kunjungan_pendamping', [KunjunganPendampingController::class, 'store'])->name('kunjungan.store');
    Route::delete('pendamping/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'destroy'])->name('kunjungan.delete');

});
