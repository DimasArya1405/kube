<?php

namespace App\Http\Controllers;

use App\Models\PencairanBantuan;
use App\Models\PengajuanKube;
use Illuminate\Http\Request;

class PencairanBantuanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil data untuk Modal (Tetap sama)
        $pengajuan_bantuan = PengajuanKube::with('kube')
            ->where('status_pengajuan', 'disetujui')
            ->whereDoesntHave('pencairanBantuan')
            ->get();

        // Query dasar untuk Tabel Utama
        $query = PencairanBantuan::with([
            'pengajuan_kube.kube',
            'pengajuan_kube.jenisBantuan'
        ]);

        // Filter berdasarkan Tahun (diambil dari kolom tanggal_pengajuan di tabel pengajuan_kube)
        if ($request->has('tahun') && $request->tahun != '') {
            $query->whereHas('pengajuan_kube', function ($q) use ($request) {
                $q->whereYear('tanggal_pengajuan', $request->tahun);
            });
        }
        $total_menunggu = PencairanBantuan::where('status_pencairan', 'menunggu')->count();
        $total_cair = PencairanBantuan::where('status_pencairan', 'cair')->count();
        $total_disetujui = PencairanBantuan::where('status_pencairan', 'disetujui')->count();
        $total_ditolak = PencairanBantuan::where('status_pencairan', 'ditolak')->count();

        // Filter berdasarkan Status Pencairan
        if ($request->has('status') && $request->status != '') {
            $query->where('status_pencairan', $request->status);
        }

        $pencairan_bantuan = $query->latest()->get();

        return view('admin.alur_bantuan.pencairan_bantuan', compact('pencairan_bantuan', 'pengajuan_bantuan', 'total_menunggu', 'total_cair', 'total_disetujui', 'total_ditolak'));
    }

    public function tambah(Request $request, $id)
    {
        // 1. Validasi input tahap agar wajib diisi dan hanya boleh bernilai 1, 2, atau 3
        $request->validate([
            'tahap' => 'required|in:1,2,3'
        ], [
            'tahap.required' => 'Tahap pencairan wajib dipilih.',
            'tahap.in' => 'Tahap pencairan tidak valid (harus Tahap 1, 2, atau 3).'
        ]);

        // 2. (Opsional) Validasi double-input untuk mencegah tahap yang sama diinput dua kali
        $pencairan_ada = PencairanBantuan::where('id_pengajuan', $id)
            ->where('tahap', $request->tahap)
            ->exists();

        if ($pencairan_ada) {
            return back()->with('error', 'Pencairan untuk Tahap ' . $request->tahap . ' sudah pernah dibuat sebelumnya.');
        }

        // 3. Simpan data pencairan bantuan baru
        $pencairan_bantuan = new PencairanBantuan;
        $pencairan_bantuan->id_pengajuan = $id;
        $pencairan_bantuan->tahap = $request->tahap; // Mengambil nilai dari radio button / select yang dipilih admin

        // Jika di tabel database Anda default statusnya belum diatur, Anda bisa set manual di sini:
        // $pencairan_bantuan->status_pencairan = 'menunggu'; 

        $pencairan_bantuan->save();

        return back()->with('success', 'Pencairan bantuan Tahap ' . $request->tahap . ' berhasil ditambahkan');
    }

    public function accept($id)
    {
        $pencairan_bantuan = PencairanBantuan::findOrFail($id);
        $pencairan_bantuan->status_pencairan = 'disetujui';
        $pencairan_bantuan->save();
        return back()->with('success', 'Pencairan bantuan berhasil disetujui');
    }
    public function reject($id)
    {
        $pencairan_bantuan = PencairanBantuan::findOrFail($id);
        $pencairan_bantuan->status_pencairan = 'ditolak';
        $pencairan_bantuan->save();
        return back()->with('success', 'Pencairan bantuan berhasil ditolak');
    }
}
