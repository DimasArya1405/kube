<?php

namespace App\Http\Controllers;

use App\Models\PencairanBantuan;
use App\Models\PengajuanKube;
use Illuminate\Http\Request;

class PencairanBantuanController extends Controller
{
    public function index()
    {
        $pengajuan_bantuan = PengajuanKube::with('kube')
            ->where('status_pengajuan', 'disetujui')
            ->whereDoesntHave('pencairanBantuan') // Pastikan 'pencairan' adalah nama method relasi di model PengajuanKube
            ->get();
        $pencairan_bantuan = PencairanBantuan::with([
            'pengajuan_kube.kube',
            'pengajuan_kube.jenisBantuan'
        ])->get();
        return view('admin.alur_bantuan.pencairan_bantuan', compact('pencairan_bantuan', 'pengajuan_bantuan'));
    }

    public function tambah($id)
    {
        $pencairan_terakhir = PencairanBantuan::where('id_pengajuan', $id)->latest()->first();
        if ($pencairan_terakhir) {
            $tahap = $pencairan_terakhir->tahap + 1;
        } else {
            $tahap = 1;
        }
        $pencairan_bantuan = new PencairanBantuan;
        $pencairan_bantuan->id_pengajuan = $id;
        $pencairan_bantuan->tahap = $tahap;
        $pencairan_bantuan->save();
        return back()->with('success', 'Pencairan bantuan berhasil ditambahkan');
    }

    public function accept($id){
        $pencairan_bantuan = PencairanBantuan::findOrFail($id);
        $pencairan_bantuan->status_pencairan = 'disetujui';
        $pencairan_bantuan->save();
        return back()->with('success', 'Pencairan bantuan berhasil disetujui');
    }
    public function reject($id){
        $pencairan_bantuan = PencairanBantuan::findOrFail($id);
        $pencairan_bantuan->status_pencairan = 'ditolak';
        $pencairan_bantuan->save();
        return back()->with('success', 'Pencairan bantuan berhasil ditolak');
    }
}
