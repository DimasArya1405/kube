<?php

namespace App\Http\Controllers;

use App\Models\Kube;
use App\Models\PengajuanKube;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersetujuanPengajuanKubeController extends Controller
{
    public function index()
    {
        $pengajuan_kube = PengajuanKube::with(['kube', 'pengaju', 'penyetuju', 'jenisBantuan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.alur_bantuan.persetujuan_bantuan_kube', compact('pengajuan_kube'));
    }

    public function setujui($id)
    {
        $pengajuan = PengajuanKube::findOrFail($id);

        if (in_array($pengajuan->status_pengajuan, ['disetujui', 'ditolak', 'cair'])) {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $pengajuan->update([
            'status_pengajuan' => 'disetujui',
            'status_penerima' => 'diterima',
            'disetujui_oleh' => Auth::id(),
            'tanggal_disetujui' => now()->toDateString(),
            'keterangan' => 'Pengajuan disetujui',
        ]);

        return redirect()->back()->with('success', 'Pengajuan KUBE berhasil disetujui.');
    }

    public function tolak(Request $request, $id)
    {
        $pengajuan = PengajuanKube::findOrFail($id);

        if (in_array($pengajuan->status_pengajuan, ['disetujui', 'ditolak', 'cair'])) {
            return redirect()->back()->with('error', 'Pengajuan ini sudah diproses.');
        }

        $pengajuan->update([
            'status_pengajuan' => 'ditolak',
            'status_penerima' => 'ditolak',
            'disetujui_oleh' => Auth::id(),
            'tanggal_disetujui' => now()->toDateString(),
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->back()->with('success', 'Pengajuan KUBE berhasil ditolak.');
    }
}
