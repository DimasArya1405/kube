<?php

use App\Http\Controllers\AnggotaKubeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClusterUsahaController;
use App\Http\Controllers\JenisBantuanController;
use App\Http\Controllers\KoordinatorController;
use App\Http\Controllers\PrediksiController;
use App\Http\Controllers\PencairanBantuanController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\MitraController;
use App\Http\Controllers\KategoriKubeController;
use App\Http\Controllers\PendampingController;
use App\Http\Controllers\PersetujuanPengajuanKubeController;
use App\Http\Controllers\RekapKubeController;
use App\Http\Controllers\BimbinganKubeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\KeuanganController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\RankingKubeController;
use App\Http\Controllers\KunjunganPendampingController;
use App\Http\Controllers\DataPerkembanganUsahaController;
use App\Http\Controllers\KubeController;
use App\Http\Controllers\LaporanKecamatanController;
use App\Http\Controllers\PembagianKoordinatorController;
use App\Http\Controllers\PembagianPendampingController;
use App\Http\Controllers\PengajuanKubeController; // ✅ PUNYAMU

use Dflydev\DotAccessData\Data;

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/', function () {
    return view('/welcome');
});

// DATA USER
Route::get('/admin/users', [DashboardController::class, 'users'])->name('admin.users');
Route::post('/admin/users/store', [DashboardController::class, 'store'])->name('admin.users.store');
Route::get('/admin/users/edit/{id}', [DashboardController::class, 'edit'])->name('admin.users.edit');
Route::put('/admin/users/update/{id}', [DashboardController::class, 'update'])->name('admin.users.update');
Route::delete('/admin/users/delete/{id}', [DashboardController::class, 'destroy'])->name('admin.users.delete');

// LOGOUT
Route::post('/logout', [AuthController::class, 'logout']);

// DASHBOARD & MASTER DATA (Wajib Login)
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
    Route::get('/kube/export/excel', [App\Http\Controllers\KubeController::class, 'exportExcel'])->name('kube.export.excel');
    Route::get('/kube/export/pdf', [App\Http\Controllers\KubeController::class, 'exportPdf'])->name('kube.export.pdf');

    // PEMBAGIAN PENDAMPING
    Route::resource('pembagian_pendamping', PembagianPendampingController::class);

    // BIMBINGAN KUBE OLEH PENDAMPING (Tambahan Baru)
    // Ini akan otomatis menghandle route bimbingan.index, bimbingan.create, bimbingan.store, dll.
    Route::resource('bimbingan', BimbinganKubeController::class);

    // CLUSTER USAHA
    Route::resource('cluster_usaha', ClusterUsahaController::class);

    // KATEGORI KUBE
    Route::get('/admin/kategorikube', [KategoriKubeController::class, 'index'])->name('kategorikube.index');
    Route::get('/admin/kategorikube/create', [KategoriKubeController::class, 'create'])->name('kategorikube.create');
    Route::post('/admin/kategorikube', [KategoriKubeController::class, 'store'])->name('kategorikube.store');
    Route::get('/admin/kategorikube/{id}', [KategoriKubeController::class, 'show'])->name('kategorikube.show');
    Route::put('/admin/kategorikube/{id}/edit', [KategoriKubeController::class, 'edit'])->name('kategorikube.edit');
    Route::post('/admin/kategorikube/{id}', [KategoriKubeController::class, 'update'])->name('kategorikube.update');
    Route::get('/admin/kategorikube/delete/{id}', [KategoriKubeController::class, 'destroy'])->name('kategorikube.destroy');

    // Pembagian Koordinator
    Route::resource('pembagian_koordinator', PembagianKoordinatorController::class);

    // KEPALA DINAS PENCAIRAN BANTUAN 
    Route::get('/kadis/pencairan_bantuan', [PencairanBantuanController::class, 'index'])->name('kadis.pencairan_bantuan.index');
    Route::post('/kadis/pencairan_bantuan/tambah/{id}', [PencairanBantuanController::class, 'tambah'])->name('kadis.pencairan_bantuan.tambah');
    Route::get('/kadis/pencairan_bantuan/accept/{id}', [PencairanBantuanController::class, 'accept'])->name('kadis.pencairan_bantuan.accept');
    Route::get('/kadis/pencairan_bantuan/reject/{id}', [PencairanBantuanController::class, 'reject'])->name('kadis.pencairan_bantuan.reject');
    Route::get('/kadis/jenis_bantuan', [JenisBantuanController::class, 'index'])->name('kadis.alur_bantuan.jenis_bantuan.index');
    Route::post('/kadis/jenis_bantuan/tambah', [JenisBantuanController::class, 'tambah'])->name('kadis.alur_bantuan.jenis_bantuan.tambah');
    Route::get('/kadis/jenis_bantuan/hapus/{id}', [JenisBantuanController::class, 'hapus'])->name('kadis.alur_bantuan.jenis_bantuan.hapus');

    // KELOLA DATA KOORDINATOR
    Route::get('/admin/koordinator', [KoordinatorController::class, 'index'])->name('koordinator.index');
    Route::post('/admin/koordinator/store', [KoordinatorController::class, 'store'])->name('koordinator.store');
    Route::delete('/admin/koordinator/{id}', [KoordinatorController::class, 'destroy'])->name('koordinator.delete');

    //PERKEMBANGAN USAHA

    Route::get('/admin/perkembangan-usaha', [DataPerkembanganUsahaController::class, 'index'])->name('perkembangan.index');
    Route::get('/admin/perkembangan-usaha/periode/{id_cluster}', [DataPerkembanganUsahaController::class, 'getPeriodeByKube'])->name('perkembangan.periode');
    Route::post('/admin/perkembangan-usaha/store', [DataPerkembanganUsahaController::class, 'store'])->name('perkembangan.store');
    Route::delete('/admin/perkembangan-usaha/{id}', [DataPerkembanganUsahaController::class, 'destroy'])->name('perkembangan.delete');

    // HALAMAN FORM PREDIKSI PENDAMPING & ADMIN
    Route::get('/pendamping/prediksi/form', [PrediksiController::class, 'index'])
        ->name('prediksi.index');
    Route::post('/pendamping/prediksi', [PrediksiController::class, 'store'])
        ->name('prediksi.store');
    Route::get('/pendamping/prediksi/daftar', [PrediksiController::class, 'daftarPrediksi'])
        ->name('prediksi.daftar');
    Route::get('/pendamping/prediksi/detail/{id_prediksi}', [PrediksiController::class, 'detailPrediksi'])
        ->name('prediksi.detail');
    Route::get('/pendamping/prediksi/edit/{id_prediksi}', [PrediksiController::class, 'editPrediksi'])
        ->name('prediksi.edit');
    Route::put('/pendamping/prediksi/update/{id_prediksi}', [PrediksiController::class, 'updatePrediksi'])
        ->name('prediksi.update');
    Route::get('/pendamping/prediksi/track/{id_kube}/{tahun}', [PrediksiController::class, 'trackRecord'])
        ->name('prediksi.track');
    Route::get('/pendamping/prediksi/bulan-tersedia', [PrediksiController::class, 'getBulanTersedia'])
        ->name('prediksi.bulanTersedia');
    // AJAX 
    Route::get('/get-kube', [PrediksiController::class, 'getKube']);
    Route::get('/get-kube-detail/{id}', [PrediksiController::class, 'getDetail']);
    //PREDIKSI UNTUK ADMIN
    Route::prefix('admin/prediksi-kube')->name('admin.prediksi-kube.')->group(function () {
        Route::get('/daftar', [PrediksiController::class, 'daftarPrediksiAdmin'])->name('daftar');
        Route::get('/detail/{id_prediksi}', [PrediksiController::class, 'detailPrediksiAdmin'])->name('detail');
        Route::get('/track/{id_kube}/{tahun}', [PrediksiController::class, 'trackRecordAdmin'])->name('track');
    });


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
    Route::get('/admin/mitra/view-pdf/{id}', [MitraController::class, 'viewPdf'])->name('mitra.viewPdf');

    // PENDAMPING
    Route::get('/admin/pendamping', [PendampingController::class, 'index'])->name('pendamping.index');
    Route::post('/admin/pendamping/store', [PendampingController::class, 'store'])->name('pendamping.store');
    Route::delete('/admin/pendamping/{id}', [PendampingController::class, 'destroy'])->name('pendamping.delete');
    Route::get('/admin/pendamping/export/pdf', [PendampingController::class, 'exportPdf'])->name('pendamping.export.pdf');
    Route::get('/admin/pendamping/export/excel', [PendampingController::class, 'exportExcel'])->name('pendamping.export.excel');

    // REKAP KUBE
    Route::get('/rekap_kube', [RekapKubeController::class, 'index'])->name('rekap_kube.index');

    // LAPORAN KEUANGAN
    Route::get('/laporan-keuangan', [KeuanganController::class, 'index'])->name('laporan.index');
    Route::post('/laporan-keuangan/store', [KeuanganController::class, 'store'])->name('laporan.store');
    Route::put('/laporan-keuangan/{id}', [KeuanganController::class, 'update'])->name('laporan.update');
    Route::delete('/laporan-keuangan/{id}', [KeuanganController::class, 'destroy'])->name('laporan.destroy');

    // MONITORING
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::post('/monitoringbantuan/store', [MonitoringController::class, 'store'])->name('monitoring.store');
    Route::delete('/monitoringbantuan/delete/{id}', [MonitoringController::class, 'delete'])->name('monitoring.delete');


    Route::get('/pengajuan-kube/create', [PengajuanKubeController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan-kube/store', [PengajuanKubeController::class, 'store'])->name('pengajuan.store');

    // KELOLA DATA PERSETUJUAN KUBE (PROBO)
    Route::get('/admin/persetujuan-bantuan-kube', [PersetujuanPengajuanKubeController::class, 'index'])->name('admin.persetujuan_bantuan_kube.index');
    Route::put('/admin/persetujuan-bantuan-kube/setujui/{id}', [PersetujuanPengajuanKubeController::class, 'setujui'])->name('admin.persetujuan_bantuan_kube.setujui');
    Route::put('/admin/persetujuan-bantuan-kube/tolak/{id}', [PersetujuanPengajuanKubeController::class, 'tolak'])->name('admin.persetujuan_bantuan_kube.tolak');
    Route::get('/admin/persetujuan-bantuan-kube/{id}/detail', [PersetujuanPengajuanKubeController::class, 'detail'])->name('admin.persetujuan_bantuan_kube.detail');
    Route::get('/admin/persetujuan-bantuan-kube/{id}/unduh-berita-acara', [PersetujuanPengajuanKubeController::class, 'unduhBeritaAcara'])->name('admin.persetujuan_bantuan_kube.unduh_berita_acara');

    // RANKING KUBE
    Route::get('/ranking-kube', [RankingKubeController::class, 'index'])->name('ranking.kube');
    Route::get('/ranking-kube/export/pdf', [RankingKubeController::class, 'exportPdf'])->name('ranking.kube.export.pdf');
    Route::get('/ranking-kube/export/excel', [RankingKubeController::class, 'exportExcel'])->name('ranking.kube.export.excel');

    //Kelola Data Kunjungan Pendamping
    Route::get('/kategori-kube', function () {return view('coming-soon');})->name('kategorikube.index');

    // KUNJUNGAN PENDAMPING
    Route::get('/pendamping/kunjungan_pendamping', [KunjunganPendampingController::class, 'index'])->name('kunjungan.index');
    Route::post('pendamping/kunjungan_pendamping', [KunjunganPendampingController::class, 'store'])->name('kunjungan.store');
    Route::get('/pendamping/kunjungan_pendamping/{id}/edit', [KunjunganPendampingController::class, 'edit'])->name('kunjungan.edit');
    Route::put('/pendamping/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'update'])->name('kunjungan.update');
    Route::get('/pendamping/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'show'])->name('kunjungan.show');
    Route::delete('pendamping/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'destroy'])->name('kunjungan.delete');

    // LAPORAN KECAMATAN
    Route::get('/admin/laporan-kecamatan', [LaporanKecamatanController::class, 'index'])->name('laporan.kecamatan');
    Route::get('/admin/laporan-kecamatan/{id}', [LaporanKecamatanController::class, 'detail'])->name('laporan.kecamatan.detail');
    Route::get('/admin/laporan-kecamatan/pdf/{id}', [LaporanKecamatanController::class, 'exportPdf'])->name('laporan.pdf');
});
