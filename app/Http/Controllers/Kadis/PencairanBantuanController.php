<?php

namespace App\Http\Controllers\Kadis;

use App\Http\Controllers\Controller;
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
        return view('kepala_dinas.pencairan_bantuan.index', compact('pencairan_bantuan', 'pengajuan_bantuan'));
    }
}
