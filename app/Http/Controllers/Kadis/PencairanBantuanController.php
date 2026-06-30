<?php

namespace App\Http\Controllers\Kadis;

use App\Http\Controllers\Controller;
use App\Models\Kube;
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

        $ringkasan_pencairan = $pencairan_bantuan->groupBy('pengajuan_kube.id_kube');

        $kube_pencairan = Kube::with(['desa', 'clusterUsaha'])
            ->orderBy('nama_kube')
            ->get()
            ->map(function ($kube) use ($ringkasan_pencairan) {
                $items = $ringkasan_pencairan->get($kube->id_kube, collect());

                $kube->total_pencairan = $items->count();
                $kube->total_nilai_bantuan = $items->sum(fn ($item) => $item->pengajuan_kube->jumlah_bantuan ?? 0);
                $kube->pencairan_terakhir = $items->max('created_at');

                return $kube;
            });

        return view('kepala_dinas.pencairan_bantuan.index', compact('kube_pencairan', 'pengajuan_bantuan'));
    }

    public function detail($id_kube)
    {
        $kube = Kube::with(['desa', 'clusterUsaha'])->findOrFail($id_kube);

        $pencairan_bantuan = PencairanBantuan::with([
            'pengajuan_kube.kube',
            'pengajuan_kube.jenisBantuan'
        ])->whereHas('pengajuan_kube', function ($q) use ($id_kube) {
            $q->where('id_kube', $id_kube);
        })->latest()->get();

        return view('kepala_dinas.pencairan_bantuan.detail', compact('kube', 'pencairan_bantuan'));
    }
}
