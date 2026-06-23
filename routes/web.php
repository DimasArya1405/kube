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
use App\Http\Controllers\KepalaDinasController;
use App\Http\Controllers\ketua_kube\PencairanBantuanController as Ketua_kubePencairanBantuanController;
use App\Http\Controllers\PersetujuanBantuanKubeKadisController;
use App\Http\Controllers\GaleriController;
use Dflydev\DotAccessData\Data;
use App\Models\Galeri;

// LOGIN
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/', function () { 
    $galeriData = Galeri::latest()->take(6)->get();
    return view('welcome', compact('galeriData'));
});

// REGISTER
// Route Register Ketua KUBE
Route::get('/register/ketua', [AuthController::class, 'showRegisterKetua'])->name('register.ketua');
Route::post('/register/ketua', [AuthController::class, 'registerKetua'])->name('register.ketua.store');

// Route Register Pendamping
Route::get('/register/pendamping', [AuthController::class, 'showRegisterPendamping'])->name('register.pendamping');
Route::post('/register/pendamping', [AuthController::class, 'registerPendamping'])->name('register.pendamping.store');

// Route Register Koordinator
Route::get('/register/koordinator', [AuthController::class, 'showRegisterKoordinator'])->name('register.koordinator');
Route::post('/register/koordinator', [AuthController::class, 'registerKoordinator'])->name('register.koordinator.store');

// LOGOUT
Route::post('/logout', [AuthController::class, 'logout']);

// AJAX: Dapatkan Desa/Kelurahan berdasarkan Kecamatan
Route::get('/get-desa/{id_kecamatan}', function ($id_kecamatan) {$desa = \App\Models\DesaKelurahan::where('id_kecamatan', $id_kecamatan)->get(['id_desa_kelurahan', 'nama_desa_kelurahan']);return response()->json($desa);});

// SEMUA ROUTE YANG MEMBUTUHKAN AUTENTIKASI
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
    Route::get('/kepala_dinas/dashboard', [DashboardController::class, 'dinas'])->name('dashboard.dinas');

    // --- DASHBOARD ADMIN ---
    Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->middleware('checkrole:admin')->name('admin.dashboard');
    // KELOLA DATA USER
    Route::get('/users', [UsersController::class, 'index'])->name('admin.users');
    Route::post('/users/store', [UsersController::class, 'store'])->name('admin.users.store');
    Route::get('/users/edit/{id}', [UsersController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/update/{id}', [UsersController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/delete/{id}', [UsersController::class, 'destroy'])->name('admin.users.delete');
    Route::patch('/users/{id}/aktifkan', [UsersController::class, 'aktifkan'])->name('admin.users.aktifkan');
    });

    // --- DASHBOARD KOORDINATOR ---
    Route::prefix('koordinator')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'koordinator'])->middleware('checkrole:koordinator')->name('koordinator.dashboard');
    });

    // --- DASHBOARD KEPALA DINAS ---
    Route::prefix('kepala_dinas')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'kepala_dinas'])->middleware('checkrole:kepala_dinas')->name('kepala_dinas.dashboard');
    });

    // --- DASHBOARD PENDAMPING ---
    Route::prefix('pendamping')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'pendamping'])->middleware('checkrole:pendamping')->name('pendamping.dashboard');
    });

    // --- DASHBOARD KETUA KUBE ---
    Route::prefix('ketua_kube')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'ketua'])
            ->middleware('checkrole:ketua_kube')
            ->name('ketua_kube.dashboard');
        Route::get('/ketua-kube/pencairan-bantuan', [Ketua_kubePencairanBantuanController::class, 'index'])->name('ketua_kube.pencairan_bantuan.index');
        Route::get('/ketua-kube/pencairan-bantuan/konfirmasi/{id}', [Ketua_kubePencairanBantuanController::class, 'konfirmasi'])->name('ketua_kube.pencairan_bantuan.konfirmasi');
    });

Route::get('/kepala_dinas/dashboard', [KepalaDinasController::class, 'dashboard'])->name('kadis.dashboard');

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

    Route::patch('/pembagian_pendamping/{id}/selesai', [PembagianPendampingController::class, 'tandaiSelesai'])->name('pembagian_pendamping.selesai');
    Route::get('/pembagian_pendamping/export/excel', [PembagianPendampingController::class, 'exportExcel'])->name('pembagian_pendamping.export.excel');
    Route::get('/pembagian-pendamping/export-pdf', [PembagianPendampingController::class, 'exportPdf'])->name('pembagian_pendamping.export.pdf');

    Route::get('/bimbingan-pdf', [BimbinganKubeController::class, 'pdf'])->name('bimbingan.pdf');
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

    // GALERI
    Route::get('/admin/galerikube', [GaleriController::class, 'index'])->name('galeri.index');
    Route::post('/admin/galerikube/store', [GaleriController::class, 'store'])->name('galeri.store');
    Route::put('/admin/galerikube/update/{id}', [GaleriController::class, 'update'])->name('galeri.update');
    Route::get('/admin/galerikube/delete/{id}', [GaleriController::class, 'destroy'])->name('galeri.delete');
    Route::get('/admin/galerikube/detail/{id}', [GaleriController::class, 'show'])->name('galeri.detail');

    // Pembagian Koordinator
    Route::resource('pembagian_koordinator', PembagianKoordinatorController::class);
    Route::get('/get-pendamping/{id_kecamatan}/{selected?}', [PembagianKoordinatorController::class, 'getPendamping']);

    // Ekspor
    Route::get('/pembagian-koordinator/pdf', [PembagianKoordinatorController::class, 'exportPDF'])->name('pembagian_koordinator.exportPDF');
    Route::get('/pembagian-koordinator/excel', [PembagianKoordinatorController::class, 'exportExcel'])->name('pembagian_koordinator.exportExcel');

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
    Route::post('/admin/koordinator/store', [KoordinatorController::class, 'store'])->name('koordinator.store');
    Route::get('/admin/koordinator/export/pdf', [KoordinatorController::class, 'exportPdf'])->name('koordinator.export.pdf');
    Route::get('/admin/koordinator/export/excel', [KoordinatorController::class, 'exportExcel'])->name('koordinator.export.excel');
    Route::get('/admin/koordinator/{id}', [KoordinatorController::class, 'show'])->name('koordinator.show');
    Route::put('/admin/koordinator/{id}', [KoordinatorController::class, 'update'])->name('koordinator.update');
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
    Route::get('/admin/pendamping/{id}', [PendampingController::class, 'show'])->name('pendamping.show');

    // REKAP KUBE
    Route::get('/rekap_kube', [RekapKubeController::class, 'index'])->name('rekap_kube.index');
    Route::get('/rekap_kube/detail/{id_kecamatan}', [RekapKubeController::class, 'detail'])->name('rekap_kube.detail');
    Route::get('/rekap_kube/export/pdf', [RekapKubeController::class, 'exportPdf'])->name('rekap_kube.export.pdf');
    Route::get('/rekap_kube/export/excel', [RekapKubeController::class, 'exportExcel'])->name('rekap_kube.export.excel');

    // LAPORAN KEUANGAN
    Route::get('/laporan-keuangan', [KeuanganController::class, 'index'])->name('laporan.index');
    Route::post('/laporan-keuangan/store', [KeuanganController::class, 'store'])->name('laporan.store');
    Route::put('/laporan-keuangan/{id}', [KeuanganController::class, 'update'])->name('laporan.update');
    Route::delete('/laporan-keuangan/{id}', [KeuanganController::class, 'destroy'])->name('laporan.destroy');
    // Rute untuk Ekspor SEMUA data (Admin)
    Route::get('/laporan-keuangan/export/excel-all', [KeuanganController::class, 'exportExcelAll'])->name('laporan.export.excel.all');
    Route::get('/laporan-keuangan/export/pdf-all', [KeuanganController::class, 'exportPdfAll'])->name('laporan.export.pdf.all');

    // Rute untuk Ekspor PER KUBE (Bisa dipakai Admin & Ketua)
    Route::get('/laporan-keuangan/export/excel-single/{id_kube}', [KeuanganController::class, 'exportExcelSingle'])->name('laporan.export.excel.single');
    Route::get('/laporan-keuangan/export/pdf-single/{id_kube}', [KeuanganController::class, 'exportPdfSingle'])->name('laporan.export.pdf.single');
    Route::get('/laporan-keuangan/export/pdf-detail/{id}', [KeuanganController::class, 'exportPdfDetail'])->name('laporan.export.pdf.detail');

    // MONITORING
    Route::middleware(['auth'])->group(function () {

    Route::get('/monitoring', [MonitoringController::class, 'index'])
        ->name('monitoring.index');

    Route::post('/monitoringbantuan/store', [MonitoringController::class, 'store'])
        ->name('monitoring.store');

    Route::delete('/monitoringbantuan/delete/{id}', [MonitoringController::class, 'delete'])
        ->name('monitoring.delete');

    // Route::get('/monitoring/edit/{id}', [MonitoringController::class, 'edit'])
    //     ->name('monitoring.edit');

    Route::post('/monitoring/update/{id}', [MonitoringController::class, 'update'])
        ->name('monitoring.update');

    // Route::get('/monitoring/detail/{id}', [MonitoringController::class, 'detail'])
    //     ->name('monitoring.detail');

    // Tambahkan ini
    Route::get('/monitoring/create/{id_pencairan}', [MonitoringController::class, 'create'])->name('monitoring.create');
    Route::get('/monitoring/pdf', [MonitoringController::class, 'exportPdf'])
        ->name('monitoring.pdf');
    Route::post('/monitoring/store', [MonitoringController::class, 'store'])->name('monitoring.store');
});

    Route::get('/pengajuan-kube/create', [PengajuanKubeController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan-kube/store', [PengajuanKubeController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan-kube', [PengajuanKubeController::class, 'index'])->name('pengajuan.index');

    // KELOLA DATA PERSETUJUAN KUBE (ADMIN)
    Route::get('/admin/persetujuan-bantuan-kube', [PersetujuanPengajuanKubeController::class, 'index'])->name('admin.persetujuan_bantuan_kube.index');
    Route::put('/admin/persetujuan-bantuan-kube/setujui/{id}', [PersetujuanPengajuanKubeController::class, 'setujui'])->name('admin.persetujuan_bantuan_kube.setujui');
    Route::put('/admin/persetujuan-bantuan-kube/tolak/{id}', [PersetujuanPengajuanKubeController::class, 'tolak'])->name('admin.persetujuan_bantuan_kube.tolak');
    Route::get('/admin/persetujuan-bantuan-kube/{id}/detail', [PersetujuanPengajuanKubeController::class, 'detail'])->name('admin.persetujuan_bantuan_kube.detail');
    Route::get('/admin/persetujuan-bantuan-kube/{id}/unduh-berita-acara', [PersetujuanPengajuanKubeController::class, 'unduhBeritaAcara'])->name('admin.persetujuan_bantuan_kube.unduh_berita_acara');

    // KELOLA DATA PERSETUJUAN KUBE (KADIS)
    Route::get('/kepala-dinas/persetujuan-bantuan-kube', [PersetujuanBantuanKubeKadisController::class, 'index'])->name('kadis.persetujuan_bantuan_kube.index');
    Route::put('/kepala-dinas/persetujuan-bantuan-kube/setujui/{id}', [PersetujuanBantuanKubeKadisController::class, 'setujui'])->name('kadis.persetujuan_bantuan_kube.setujui');
    Route::put('/kepala-dinas/persetujuan-bantuan-kube/tolak/{id}', [PersetujuanBantuanKubeKadisController::class, 'tolak'])->name('kadis.persetujuan_bantuan_kube.tolak');
    Route::get('/kepala-dinas/persetujuan-bantuan-kube/{id}/detail', [PersetujuanBantuanKubeKadisController::class, 'detail'])->name('kadis.persetujuan_bantuan_kube.detail');
    Route::get('/kepala-dinas/persetujuan-bantuan-kube/{id}/unduh-berita-acara', [PersetujuanBantuanKubeKadisController::class, 'unduhBeritaAcara'])->name('kadis.persetujuan_bantuan_kube.unduh_berita_acara');

    // RANKING KUBE
    Route::get('/ranking-kube', [RankingKubeController::class, 'index'])->name('ranking.kube');
    Route::get('/ranking-kube/export/pdf', [RankingKubeController::class, 'exportPdf'])->name('ranking.kube.export.pdf');
    Route::get('/ranking-kube/export/excel', [RankingKubeController::class, 'exportExcel'])->name('ranking.kube.export.excel');

    //Kelola Data Kunjungan Pendamping
    Route::get('/kategori-kube', function () {
        return view('coming-soon');
    })->name('kategorikube.index');

    // KUNJUNGAN PENDAMPING
    // KUNJUNGAN PENDAMPING - ADMIN (VIEW ONLY)
    Route::get('/admin/kunjungan-pendamping', [KunjunganPendampingController::class, 'adminIndex'])->name('admin.kunjungan.index');


    Route::prefix('pendamping')
        ->middleware(['checkrole:pendamping'])
        ->group(function () {

            Route::get('/kunjungan_pendamping', [KunjunganPendampingController::class, 'index'])->name('kunjungan.index');
            Route::post('/kunjungan_pendamping', [KunjunganPendampingController::class, 'store'])->name('kunjungan.store');
            Route::get('/kunjungan_pendamping/{id}/edit', [KunjunganPendampingController::class, 'edit'])->name('kunjungan.edit');
            Route::put('/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'update'])->name('kunjungan.update');
            Route::get('/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'show'])->name('kunjungan.show');
            Route::delete('/kunjungan_pendamping/{id}', [KunjunganPendampingController::class, 'destroy'])->name('kunjungan.delete');
            Route::patch('/pendamping/kunjungan_pendamping/{id}/selesai', [KunjunganPendampingController::class, 'selesai'])->name('kunjungan.selesai');
            Route::get('/pendamping/kunjungan/export/excel', [KunjunganPendampingController::class, 'exportExcel'])->name('kunjungan.export.excel');
            Route::get('/pendamping/kunjungan/export/pdf', [KunjunganPendampingController::class, 'exportPdf'])->name('kunjungan.export.pdf');
 
        });

    // LAPORAN KECAMATAN
    Route::get('/admin/laporan-kecamatan/excel',[LaporanKecamatanController::class,'exportExcel'])->name('laporan.kecamatan.excel');
    Route::get('/admin/laporan-kecamatan/pdf',[LaporanKecamatanController::class,'exportPdfKecamatan'])->name('laporan.kecamatan.pdf');
    Route::get('/admin/laporan-kecamatan', [LaporanKecamatanController::class, 'index'])->name('laporan.kecamatan');
    Route::get('/admin/laporan-kecamatan/{id}', [LaporanKecamatanController::class, 'detail'])->name('laporan.kecamatan.detail');
    Route::get('/admin/laporan-kecamatan/pdf/{id}', [LaporanKecamatanController::class, 'exportPdf'])->name('laporan.pdf');
    // LAPORAN KECAMATAN KEPALA DINAS
    Route::get('/kepala_dinas/laporan-kecamatan/excel',[LaporanKecamatanController::class,'exportExcel'])->name('kadis.laporan.kecamatan.excel');
    Route::get('/kepala_dinas/laporan-kecamatan/pdf',[LaporanKecamatanController::class,'exportPdfKecamatan'])->name('kadis.laporan.kecamatan.pdf');
    Route::get('/kepala_dinas/laporan-kecamatan',[LaporanKecamatanController::class,'index'])->name('kadis.laporan.kecamatan');
    Route::get('/kepala_dinas/laporan-kecamatan/{id}',[LaporanKecamatanController::class,'detail'])->name('kadis.laporan.kecamatan.detail');
    Route::get('/kepala_dinas/laporan-kecamatan/pdf/{id}',[LaporanKecamatanController::class,'exportPdf'])->name('kadis.laporan.pdf');
    
    // Route untuk Manajemen Galeri KUBE
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri.index');
Route::post('/galeri', [GaleriController::class, 'store'])->name('galeri.store');
Route::get('/galeri/{id}', [GaleriController::class, 'show'])->name('galeri.detail');
Route::post('/galeri/update/{id}', [GaleriController::class, 'update'])->name('galeri.update');

// Dibuat GET sesuai dengan tag <a> di file index.blade.php kamu
Route::get('/galeri/delete/{id}', [GaleriController::class, 'destroy'])->name('galeri.destroy');
});
