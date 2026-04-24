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
use App\Http\Controllers\Kadis\PencairanBantuanController as KadisPencairanBantuanController;
use App\Http\Controllers\KubeController;
use App\Http\Controllers\LaporanKecamatanController;
use App\Http\Controllers\PembagianKoordinatorController;
use App\Http\Controllers\PengajuanKubeController;
use App\Http\Controllers\PembagianPendampingController;
use App\Http\Controllers\KolaborasiBantuanController;
use App\Http\Controllers\PenyaluranKolaborasiController;

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

// REGISTER
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
 
// LOGOUT
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/get-desa/{id_kecamatan}', function($id_kecamatan) {
    $desa = \App\Models\DesaKelurahan::where('id_kecamatan', $id_kecamatan)->get(['id_desa_kelurahan', 'nama_desa_kelurahan']);
    return response()->json($desa);
});

// DASHBOARD & MASTER DATA (Wajib Login)
Route::middleware('auth')->group(function () {

    // KEPALA DINAS
    Route::get('/kadis/pencairan_bantuan/index', [KadisPencairanBantuanController::class, 'index'])->name('kadis.pencairan_bantuan.index');

    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/dashboard/ketua', [DashboardController::class, 'ketua'])->name('ketua_kube.dashboard');
    Route::get('/dashboard/pendamping', [DashboardController::class, 'pendamping'])->name('pendamping.dashboard');
    Route::get('/dashboard/koordinator', [DashboardController::class, 'koordinator'])->name('koordinator.dashboard');
    Route::get('/dashboard/tim', [DashboardController::class, 'tim'])->name('tim.dashboard');;
    Route::get('/dashboard/dinas', [DashboardController::class, 'dinas'])->name('dinas.dashboard');;

    Route::get('/ketua_kube/dashboard', [DashboardController::class, 'ketua'])->name('ketua_kube.dashboard');
    Route::get('/pendamping/dashboard', [DashboardController::class, 'pendamping'])->name('pendamping.dashboard');
    Route::get('/koordinator/dashboard', [DashboardController::class, 'koordinator'])->name('dashboard.koordinator');
    Route::get('/dashboard/tim', [DashboardController::class, 'tim'])->name('dashboard.tim');
    Route::get('/kepala_dinas/dashboard', [DashboardController::class, 'dinas'])->name('dashboard.dinas');

    // KELOLA DATA USER
    Route::get('/admin/users', [UsersController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/store', [UsersController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/edit/{id}', [UsersController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/update/{id}', [UsersController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/delete/{id}', [UsersController::class, 'destroy'])->name('admin.users.delete');

    // KELOLA DATA KUBE & ANGGOTA
    Route::resource('kube', KubeController::class);
    Route::resource('anggota_kube', AnggotaKubeController::class);
    Route::get('detail_kube', [KubeController::class, 'detail_kube']);
    Route::get('/kube/export/excel', [App\Http\Controllers\KubeController::class, 'exportExcel'])->name('kube.export.excel');
    Route::get('/kube/export/pdf', [App\Http\Controllers\KubeController::class, 'exportPdf'])->name('kube.export.pdf');
    Route::get('/anggota_kube/export/excel', [App\Http\Controllers\AnggotaKubeController::class, 'exportExcel'])->name('anggota.export.excel');
    Route::get('/anggota_kube/export/pdf', [App\Http\Controllers\AnggotaKubeController::class, 'exportPdf'])->name('anggota.export.pdf');

    // PEMBAGIAN PENDAMPING
    Route::resource('pembagian_pendamping', PembagianPendampingController::class);
    Route::patch('/pembagian_pendamping/{id}/selesai', [App\Http\Controllers\PembagianPendampingController::class, 'tandaiSelesai'])->name('pembagian_pendamping.selesai');
    Route::get('/pembagian_pendamping/export/excel', [App\Http\Controllers\PembagianPendampingController::class, 'exportExcel'])->name('pembagian_pendamping.export.excel');

    // BIMBINGAN KUBE OLEH PENDAMPING (Tambahan Baru)
    // Ini akan otomatis menghandle route bimbingan.index, bimbingan.create, bimbingan.store, dll.
    Route::resource('bimbingan', BimbinganKubeController::class);

    // BIMBINGAN KUBE OLEH PENDAMPING (Tambahan Baru)
    // Ini akan otomatis menghandle route bimbingan.index, bimbingan.create, bimbingan.store, dll.
    Route::resource('bimbingan', BimbinganKubeController::class);

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
    Route::get('/get-pendamping/{id_kecamatan}/{selected?}',
    [PembagianKoordinatorController::class, 'getPendamping']);
    // Ekspor
    Route::get('/pembagian-koordinator/pdf', [PembagianKoordinatorController::class, 'exportPDF'])
    ->name('pembagian_koordinator.exportPDF');
    Route::get('/pembagian-koordinator/excel', [PembagianKoordinatorController::class, 'exportExcel'])
    ->name('pembagian_koordinator.exportExcel');

    // PENCAIRAN BANTUAN
    Route::get('/admin/pencairan_bantuan', [PencairanBantuanController::class, 'index'])->name('admin.pencairan_bantuan.index');
    Route::post('/admin/pencairan_bantuan/tambah/{id}', [PencairanBantuanController::class, 'tambah'])->name('admin.pencairan_bantuan.tambah');
    Route::get('/admin/pencairan_bantuan/accept/{id}', [PencairanBantuanController::class, 'accept'])->name('admin.pencairan_bantuan.accept');
    Route::get('/admin/pencairan_bantuan/reject/{id}', [PencairanBantuanController::class, 'reject'])->name('admin.pencairan_bantuan.reject');
    Route::get('/admin/jenis_bantuan', [JenisBantuanController::class, 'index'])->name('admin.alur_bantuan.jenis_bantuan.index');
    Route::post('/admin/jenis_bantuan/tambah', [JenisBantuanController::class, 'tambah'])->name('admin.alur_bantuan.jenis_bantuan.tambah');
    Route::get('/admin/jenis_bantuan/hapus/{id}', [JenisBantuanController::class, 'hapus'])->name('admin.alur_bantuan.jenis_bantuan.hapus');

    // KELOLA DATA KOORDINATOR
    Route::get('/admin/koordinator', [KoordinatorController::class, 'index'])->name('koordinator.index');
    Route::get('/admin/koordinator/export/pdf', [KoordinatorController::class, 'exportPdf'])->name('koordinator.export.pdf');
    Route::get('/admin/koordinator/export/excel', [KoordinatorController::class, 'exportExcel'])->name('koordinator.export.excel');
    Route::get('/admin/koordinator/{id}', [KoordinatorController::class, 'show'])->name('koordinator.show');
    Route::post('/admin/koordinator/store', [KoordinatorController::class, 'store'])->name('koordinator.store');
    Route::put('/admin/koordinator/{id}', [KoordinatorController::class, 'update'])->name('koordinator.update');
    Route::delete('/admin/koordinator/{id}', [KoordinatorController::class, 'destroy'])->name('koordinator.delete');

    //PERKEMBANGAN USAHA

    Route::get('/admin/perkembangan-usaha', [DataPerkembanganUsahaController::class, 'index'])->name('perkembangan.index');
    Route::get('/admin/perkembangan-usaha/periode/{id_cluster}', [DataPerkembanganUsahaController::class, 'getPeriodeByKube'])->name('perkembangan.periode');
    Route::post('/admin/perkembangan-usaha/store', [DataPerkembanganUsahaController::class, 'store'])->name('perkembangan.store');
    Route::delete('/admin/perkembangan-usaha/{id}', [DataPerkembanganUsahaController::class, 'destroy'])->name('perkembangan.delete');

    // HALAMAN
    Route::get('/pendamping/prediksi', [PrediksiController::class, 'index'])
        ->name('prediksi.index');

    // SIMPAN
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

    // AJAX SUDAH DIPINDAH KE SINI
    Route::get('/get-kube/{id}', [PrediksiController::class, 'getKube']);
    Route::get('/get-kube-detail/{id}', [PrediksiController::class, 'getDetail']);

    // Route::get('/admin/koordinator', [KoordinatorController::class, 'index'])->name('koordinator.index');
    // Route::post('/admin/koordinator/store', [KoordinatorController::class, 'store'])->name('koordinator.store');
    // Route::delete('/admin/koordinator/{id}', [KoordinatorController::class, 'destroy'])->name('koordinator.delete');

    // PELATIHAN
    Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihan.index');
    Route::post('/pelatihan', [PelatihanController::class, 'store'])->name('pelatihan.store');
    Route::delete('/pelatihan/{id}', [PelatihanController::class, 'destroy'])->name('pelatihan.destroy');
    Route::put('/pelatihan/{id}', [PelatihanController::class, 'update'])->name('mitra.update');
    Route::get('/pelatihan/export-excel', [PelatihanController::class, 'exportExcel'])->name('pelatihan.excel');
    Route::get('/pelatihan/export-pdf', [PelatihanController::class, 'exportPdf'])->name('pelatihan.pdf');





    // KELOLA MITRA & KOLABORASI
    Route::get('/admin/mitra', [MitraController::class, 'index'])->name('mitra.index');
    Route::get('/admin/mitra/create', [MitraController::class, 'create'])->name('mitra.create');
    Route::post('/admin/mitra/store', [MitraController::class, 'store'])->name('mitra.store');
    Route::get('/admin/mitra/{id}/edit', [MitraController::class, 'edit'])->name('mitra.edit');
    Route::put('/admin/mitra/{id}', [MitraController::class, 'update'])->name('mitra.update');
    Route::delete('/admin/mitra/{id}', [MitraController::class, 'destroy'])->name('mitra.delete');
    Route::get('/admin/mitra/view-pdf/{id}', [MitraController::class, 'viewPdf'])->name('mitra.viewPdf');
    Route::get('/admin/bantuan', [KolaborasiBantuanController::class, 'index'])->name('bantuan.index');
    Route::post('/admin/bantuan/store', [KolaborasiBantuanController::class, 'store'])->name('bantuan.store');
    Route::get('/admin/bantuan/{id}/edit', [KolaborasiBantuanController::class, 'edit'])->name('bantuan.edit');
    Route::put('/admin/bantuan/update/{id}', [KolaborasiBantuanController::class, 'update'])->name('bantuan.update');
    Route::delete('/admin/bantuan/delete/{id}', [KolaborasiBantuanController::class, 'destroy'])->name('bantuan.delete');
    Route::get('/admin/bantuan/lihat-foto/{filename}', [KolaborasiBantuanController::class, 'lihatFoto'])->name('bantuan.lihat_foto');
    Route::post('/penyaluran-kolaborasi/store/{id_kolaborasi}', [PenyaluranKolaborasiController::class, 'store'])->name('penyaluran.kolaborasi.store');
    Route::delete('/penyaluran-kolaborasi/destroy/{id_kolaborasi}', [PenyaluranKolaborasiController::class, 'destroy'])->name('penyaluran.destroy');
    Route::get('/mitra/export-excel', [MitraController::class, 'exportExcel'])->name('mitra.excel');
    Route::get('/mitra/export-pdf', [MitraController::class, 'exportPdf'])->name('mitra.pdf');
    Route::get('/kolaborasi/export-pdf', [KolaborasiBantuanController::class, 'exportPdf'])->name('kolaborasi.pdf');
    Route::get('/kolaborasi/export-excel', [KolaborasiBantuanController::class, 'exportExcel'])->name('kolaborasi.excel');
    Route::get('/admin/bantuan-kolaborasi', [KolaborasiBantuanController::class, 'index'])->name('kolaborasi.index');

    // PENDAMPING
    Route::get('/admin/pendamping', [PendampingController::class, 'index'])->name('pendamping.index');
    Route::post('/admin/pendamping/store', [PendampingController::class, 'store'])->name('pendamping.store');
    Route::delete('/admin/pendamping/{id}', [PendampingController::class, 'destroy'])->name('pendamping.delete');
    Route::get('/admin/pendamping/export/pdf', [PendampingController::class, 'exportPdf'])->name('pendamping.export.pdf');
    Route::get('/admin/pendamping/export/excel', [PendampingController::class, 'exportExcel'])->name('pendamping.export.excel');
    Route::put('/admin/pendamping/{id}', [PendampingController::class, 'update'])->name('pendamping.update');
    Route::get('/admin/pendamping/{id}', [PendampingController::class,'show'])->name('pendamping.show');

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

    // RANKING KUBE
    Route::get('/ranking-kube', [RankingKubeController::class, 'index'])->name('ranking.kube');
    Route::get('/ranking-kube/export/pdf', [RankingKubeController::class, 'exportPdf'])->name('ranking.kube.export.pdf');
    Route::get('/ranking-kube/export/excel', [RankingKubeController::class, 'exportExcel'])->name('ranking.kube.export.excel');

    //Kelola Data Kunjungan Pendamping
    Route::get('/kategori-kube', function () {
        return view('coming-soon');
    })->name('kategorikube.index');

    // KUNJUNGAN PENDAMPING

    Route::prefix('pendamping')
    ->middleware(['role:pendamping'])
    ->group(function () {

        Route::get('/kunjungan_pendamping', [KunjunganPendampingController::class, 'index'])->name('kunjungan.index');
        Route::post('/kunjungan_pendamping', [KunjunganPendampingController::class, 'store'])->name('kunjungan.store');
        Route::get('/kunjungan_pendamping/{id}/edit', [KunjunganPendampingController::class, 'edit'])->name('kunjungan.edit');
        Route::put('/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'update'])->name('kunjungan.update');
        Route::get('/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'show'])->name('kunjungan.show');
        Route::delete('/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'destroy'])->name('kunjungan.delete');
        Route::patch('/pendamping/kunjungan_pendamping/{id}/selesai', [KunjunganPendampingController::class, 'selesai'])->name('kunjungan.selesai');
    });

    // Route::get('/pendamping/kunjungan_pendamping', [KunjunganPendampingController::class, 'index'])->name('kunjungan.index');
    // Route::post('pendamping/kunjungan_pendamping', [KunjunganPendampingController::class, 'store'])->name('kunjungan.store');
    // Route::get('/pendamping/kunjungan_pendamping/{id}/edit', [KunjunganPendampingController::class, 'edit'])->name('kunjungan.edit');
    // Route::put('/pendamping/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'update'])->name('kunjungan.update');
    // Route::get('/pendamping/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'show'])->name('kunjungan.show');
    // Route::delete('pendamping/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'destroy'])->name('kunjungan.delete');

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


