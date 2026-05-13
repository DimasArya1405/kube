<?php

namespace App\Http\Controllers;

use App\Models\Kube;
use App\Models\AnggotaKube;
use App\Models\DesaKelurahan;
use App\Models\ClusterUsaha;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\KubeExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class KubeController extends Controller
{
    // public function index()
    // {
    //     $kubes = Kube::with([
    //         'desa.kecamatan',
    //         'clusterUsaha.kategori',
    //         // Rute baru: Penugasan -> Tabel Pendamping -> Penugasan Koor -> Tabel Koordinator
    //         'pembagianPendamping.pendamping.pembagianKoordinator.koordinator',
    //         'pembagianPendamping.pembagianKoordinator.koordinator'
    //     ])->get();

    //     $desas = DesaKelurahan::orderBy('nama_desa_kelurahan', 'asc')->get();
    //     $clusters = ClusterUsaha::all();

    //     // 🔥 TAMBAHAN BARU: Cari user yang role-nya ketua_kube
    //     // Pastikan kolom primary key di tabel user lu bener ya (gw asumsikan namanya 'id_user' atau 'id')
    //     $calonKetua = User::where('role', 'ketua_kube')->get();

    //     return view('admin.data_master.kube', compact('kubes', 'desas', 'clusters', 'calonKetua'));
    // }

    public function index()
    {
        // Siapkan data pendukung yang dipakai bareng-bareng
        $desas = DesaKelurahan::orderBy('nama_desa_kelurahan', 'asc')->get();
        $clusters = ClusterUsaha::all();
        $role = Auth::user()->role; // Cek siapa yang lagi login

        // ================= LOGIKA UNTUK ADMIN =================
        if ($role == 'admin') {
            $kubes = Kube::with([
                'desa.kecamatan',
                'clusterUsaha.kategori',
                'pembagianPendamping.pendamping',
                'pembagianPendamping.pembagianKoordinator.koordinator'
            ])->get();

            $calonKetua = User::where('role', 'ketua_kube')->get();

            // Arahkan ke file Blade punya Admin
            return view('admin.data_master.kube', compact('kubes', 'desas', 'clusters', 'calonKetua'));
        }

        // ================= LOGIKA UNTUK PENDAMPING =================
        elseif ($role == 'pendamping') {
            $nikLogin = Auth::user()->nik;

            // Tarik KUBE yang relasi NIK pendampingnya cocok dengan yang lagi login
            $kubes = Kube::whereHas('pembagianPendamping.pendamping', function ($q) use ($nikLogin) {
                $q->where('nik', $nikLogin);
            })->with([
                'desa.kecamatan',
                'clusterUsaha.kategori',
                'pembagianPendamping.pendamping.pembagianKoordinator.koordinator',
                'pembagianPendamping.pembagianKoordinator.koordinator'
            ])->get();

            $calonKetua = User::where('role', 'ketua_kube')->get();

            // Arahkan ke file Blade khusus Pendamping (biar tombol hapus/edit admin gak muncul)
            return view('pendamping.kube_binaan.kube', compact('kubes', 'desas', 'clusters', 'calonKetua'));
        }

        // Kalau ada role lain yang nyasar ke rute ini
        return abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }

    //fungsi simpan lewat role ketua 
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'nama_kube' => 'required|string|max:100',
    //         'id_desa_kelurahan' => 'required|integer',
    //         'id_cluster' => 'required|integer',
    //         'keterangan' => 'required|string',
    //     ]);

    //     $kubeBaru = Kube::create([
    //         'nama_kube' => $request->nama_kube,
    //         'id_desa_kelurahan' => $request->id_desa_kelurahan,
    //         'id_cluster' => $request->id_cluster,
    //         'tanggal_terbentuk' => $request->tanggal_terbentuk,
    //         'status' => $request->status ?? 'Tidak Aktif', // Biasakan default pengajuan itu Menunggu
    //         'keterangan' => $request->keterangan,
    //         'id_user' => Auth::id()
    //     ]);

    //     AnggotaKube::create([
    //         'id_kube' => $kubeBaru->id_kube,
    //         'nama_anggota' => Auth::user()->nama, // Pastikan di tabel users ada kolom 'nama'
    //         'nik' => Auth::user()->nik,           // Pastikan di tabel users ada kolom 'nik'
    //         'no_hp' => Auth::user()->no_hp,       // Pastikan di tabel users ada kolom 'no_hp'
    //         'alamat' => Auth::user()->alamat,     // Pastikan di tabel users ada kolom 'alamat'
    //         'jabatan' => 'Ketua'
    //     ]);

    //     return redirect()->back()->with('success', 'Data KUBE berhasil diajukan!');
    // }

    public function store(Request $request)
    {
        // 1. Validasi Dasar (Berlaku untuk Admin & Ketua)
        $rules = [
            'nama_kube' => 'required|string|max:100',
            'id_desa_kelurahan' => 'required|integer',
            'id_cluster' => 'required|integer',
            'keterangan' => 'required|string',
        ];

        // Jika yang submit adalah Admin, maka input 'id_user' dari dropdown WAJIB ada
        if (Auth::user()->role == 'admin') {
            $rules['id_user'] = 'required|integer';
        }

        $request->validate($rules);

        // 🔥 2. LOGIKA PINTAR: Tentukan Siapa Ketuanya 🔥
        if (Auth::user()->role == 'admin') {
            // Kalau Admin, ketuanya diambil dari pilihan dropdown form
            $ketuaTerpilih = User::findOrFail($request->id_user);
            $idPemilikKube = $request->id_user;
            $statusKube = $request->status ?? 'Aktif'; // Admin bisa langsung bikin aktif
        } else {
            // Kalau Ketua KUBE, ketuanya ya dirinya sendiri yang lagi login
            $ketuaTerpilih = Auth::user();
            $idPemilikKube = Auth::id();
            $statusKube = 'Tidak Aktif'; // Kalau ketua yang ngajuin, defaultnya menunggu
        }

        // 3. Simpan KUBE
        $kubeBaru = Kube::create([
            'nama_kube' => $request->nama_kube,
            'id_desa_kelurahan' => $request->id_desa_kelurahan,
            'id_cluster' => $request->id_cluster,
            'tanggal_terbentuk' => $request->tanggal_terbentuk, // Bisa null kalau belum ada
            'status' => $statusKube,
            'keterangan' => $request->keterangan,
            'id_user' => $idPemilikKube // 🔥 Masuk ke jembatan relasi
        ]);

        // 4. Otomatis Daftarkan Sebagai Anggota (Jabatan: Ketua)
        AnggotaKube::create([
            'id_kube' => $kubeBaru->id_kube,
            'nama_anggota' => $ketuaTerpilih->nama,
            'nik' => $ketuaTerpilih->nik,
            'no_hp' => $ketuaTerpilih->no_hp,
            'alamat' => $ketuaTerpilih->alamat,
            'jabatan' => 'Ketua'
        ]);

        // Arahkan kembali dengan pesan sukses
        return redirect()->back()->with('success', 'Data KUBE berhasil disimpan!');
    }

    public function destroy($id)
    {
        $kube = Kube::where('id_kube', $id)->firstOrFail();
        $kube->delete();

        return redirect()->back()->with('success', 'Data KUBE berhasil dihapus!');
    }

    public function show($id)
    {
        // Ambil data KUBE beserta relasinya, plus data ANGGOTA-nya
        // Pastikan relasi 'anggota' udah lu buat di Model Kube (hasMany AnggotaKube)
        $kube = Kube::with(['desa', 'clusterUsaha', 'anggota'])->where('id_kube', $id)->firstOrFail();

        return view('admin.data_master.detail_kube', compact('kube'));
    }

    public function detail_kube()
    {
        // 1. Cek apakah akun ketua ini sudah punya data di tabel KUBE
        $kube = Kube::with(['desa', 'clusterUsaha', 'anggota'])
            ->where('id_user', Auth::id())
            ->first();

        // 2. Kalau KUBE BELUM ADA (Berarti dia baru pertama kali login)
        if (!$kube) {
            // Ambil data untuk form dropdown
            $desas = DesaKelurahan::orderBy('nama_desa_kelurahan', 'asc')->get();
            $clusters = ClusterUsaha::all();

            // Arahkan ke halaman form pengajuan (Kak Yana harus bikin view ini)
            return view('ketua_kube.manajemen_internal.pengajuan_kube_baru', compact('desas', 'clusters'));
        }

        // 3. Kalau KUBE SUDAH ADA 
        // Arahkan ke halaman dashboard utama dia, dan bawa data $myKube-nya
        return view('ketua_kube.manajemen_internal.detail_kube', compact('kube'));
    }

    public function update(Request $request, $id)
    {
        // 1. Validasi inputan dari Modal Edit
        $request->validate([
            'nama_kube' => 'required|string|max:100',
            'id_desa_kelurahan' => 'required|integer',
            'id_cluster' => 'required|integer',
            'status' => 'required|string',
            'keterangan' => 'required|string',
        ]);

        // 2. Cari data KUBE yang mau diedit
        $kube = Kube::where('id_kube', $id)->firstOrFail();

        // 3. Simpan perubahan ke database
        $kube->update([
            'nama_kube' => $request->nama_kube,
            'id_desa_kelurahan' => $request->id_desa_kelurahan,
            'id_cluster' => $request->id_cluster,
            'status' => $request->status,
            'tanggal_terbentuk' => $request->tanggal_terbentuk,
            'keterangan' => $request->keterangan,
        ]);

        // 4. Balik ke halaman semula
        return redirect()->back()->with('success', 'Data KUBE berhasil diupdate!');
    }

    public function exportExcel()
    {
        return Excel::download(new KubeExport, 'Data_KUBE.xlsx');
    }

    public function exportPdf()
    {
        // Tarik SEMUA relasi sampai ke akar-akarnya, termasuk anggota!
        $kubes = Kube::with([
            'desa.kecamatan',
            'clusterUsaha.kategori',
            'pembagianPendamping.pendamping.pembagianKoordinator.koordinator',
            'pembagianPendamping.pembagianKoordinator.koordinator',
            'anggota' // 🔥 Jangan lupa tarik data anggotanya
        ])->get();

        $pdf = Pdf::loadView('admin.data_master.pdf_kube', compact('kubes'));

        // Opsional: Bikin kertasnya jadi Landscape kalau tabelnya lebar
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('Laporan_Lengkap_KUBE.pdf');
    }
}
