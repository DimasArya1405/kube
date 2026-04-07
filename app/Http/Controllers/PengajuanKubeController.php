<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanKube;
use App\Models\JenisBantuan;
use App\Models\Kube;

class PengajuanKubeController extends Controller
{
    // Tampilkan form
    public function create()
    {
        $jenisBantuan = JenisBantuan::all();
        $kube = Kube::all();

        return view('admin.pengajuan_kube.create', compact('jenisBantuan', 'kube'));
    }

    // Simpan data
    public function store(Request $request)
    {
        $request->validate([
            'id_kube' => 'required',
            'id_jenis_bantuan' => 'required',
            'jumlah_bantuan' => 'nullable|numeric',
            'tujuan_pengajuan' => 'required',
            'tanggal_pengajuan' => 'required|date'
        ]);

        PengajuanKube::create([
            'id_kube' => $request->id_kube,
            'id_user' => auth()->user()->id_user,
            'disetujui_oleh' => auth()->user()->id_user, // sementara
            'id_jenis_bantuan' => $request->id_jenis_bantuan,
            'jumlah_bantuan' => $request->jumlah_bantuan,
            'tujuan_pengajuan' => $request->tujuan_pengajuan,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'status_pengajuan' => 'diajukan',
            'status_penerima' => 'menunggu'
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil ditambahkan!');
    }
}