<?php

namespace App\Http\Controllers;

use App\Models\Kube;
use App\Models\AnggotaKube;
use App\Models\DesaKelurahan;
use App\Models\ClusterUsaha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\KubeExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class KubeController extends Controller
{
    public function index()
    {
        $kubes = Kube::with([
            'desa.kecamatan',
            'clusterUsaha.kategori',
            // Rute baru: Penugasan -> Tabel Pendamping -> Penugasan Koor -> Tabel Koordinator
            'pembagianPendamping.pendamping.pembagianKoordinator.koordinator',
            'pembagianPendamping.pembagianKoordinator.koordinator'
        ])->get();

        $desas = DesaKelurahan::orderBy('nama_desa_kelurahan', 'asc')->get();
        $clusters = ClusterUsaha::all();

        return view('admin.data_master.kube', compact('kubes', 'desas', 'clusters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kube' => 'required|string|max:100',
            'id_desa_kelurahan' => 'required|integer',
            'id_cluster' => 'required|integer',
            'keterangan' => 'required|string',
        ]);

        $kubeBaru = Kube::create([
            'nama_kube' => $request->nama_kube,
            'id_desa_kelurahan' => $request->id_desa_kelurahan,
            'id_cluster' => $request->id_cluster,
            'tanggal_terbentuk' => $request->tanggal_terbentuk,
            'status' => $request->status ?? 'Tidak Aktif', // Biasakan default pengajuan itu Menunggu
            'keterangan' => $request->keterangan,
            'id_user' => Auth::id()
        ]);

        AnggotaKube::create([
            'id_kube' => $kubeBaru->id_kube,
            'nama_anggota' => Auth::user()->nama, // Pastikan di tabel users ada kolom 'nama'
            'nik' => Auth::user()->nik,           // Pastikan di tabel users ada kolom 'nik'
            'no_hp' => Auth::user()->no_hp,       // Pastikan di tabel users ada kolom 'no_hp'
            'alamat' => Auth::user()->alamat,     // Pastikan di tabel users ada kolom 'alamat'
            'jabatan' => 'Ketua'
        ]);

        return redirect()->back()->with('success', 'Data KUBE berhasil diajukan!');
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
