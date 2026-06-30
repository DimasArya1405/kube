<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanKube;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PersetujuanBantuanKubeKadisController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->tahun;

        $query = PengajuanKube::with([
            'kube',
            'jenisBantuan',
            'penyetuju'
        ]);

        if ($tahun) {
            $query->whereYear('tanggal_pengajuan', $tahun);
        }
        $pengajuan_kube = $query->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('id_kube')
            ->map(function ($items) {
                $first = $items->sortByDesc('created_at')->first();

                $first->jumlah_pengajuan = $items->count();
                $first->total_jumlah_bantuan = $items->sum('jumlah_bantuan');
                $first->status_ringkasan = $this->getStatusRingkasan($items);
                $first->tanggal_pengajuan_terakhir = $items->max('tanggal_pengajuan');

                return $first;
            })
            ->sortByDesc('created_at')
            ->values();

        $total_pengajuan = PengajuanKube::when($tahun, function ($q) use ($tahun) {
            $q->whereYear('tanggal_pengajuan', $tahun);
        })->count();

        $total_menunggu = PengajuanKube::when($tahun, function ($q) use ($tahun) {
            $q->whereYear('tanggal_pengajuan', $tahun);
        })->whereIn('status_pengajuan', ['diajukan', 'menunggu'])->count();

        $total_disetujui = PengajuanKube::when($tahun, function ($q) use ($tahun) {
            $q->whereYear('tanggal_pengajuan', $tahun);
        })->where('status_pengajuan', 'disetujui')->count();

        $total_ditolak = PengajuanKube::when($tahun, function ($q) use ($tahun) {
            $q->whereYear('tanggal_pengajuan', $tahun);
        })->where('status_pengajuan', 'ditolak')->count();

        $list_tahun = PengajuanKube::selectRaw('YEAR(tanggal_pengajuan) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('kepala_dinas.persetujuan_kube.persetujuan_bantuan_kube', compact('pengajuan_kube', 'total_pengajuan', 'total_menunggu', 'total_disetujui', 'total_ditolak', 'list_tahun', 'tahun'));
    }

    private function getStatusRingkasan($items)
    {
        if ($items->contains(fn ($item) => in_array($item->status_pengajuan, ['diajukan', 'menunggu']))) {
            return 'menunggu';
        }

        if ($items->every(fn ($item) => $item->status_pengajuan === 'disetujui')) {
            return 'disetujui';
        }

        if ($items->every(fn ($item) => $item->status_pengajuan === 'ditolak')) {
            return 'ditolak';
        }

        if ($items->every(fn ($item) => $item->status_pengajuan === 'cair')) {
            return 'cair';
        }

        return 'diproses';
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

    public function detail($id)
    {
        $pengajuan_kube = PengajuanKube::with([
            'kube.desa',
            'kube.clusterUsaha',
            'jenisBantuan',
            'penyetuju',
            'users'
        ])->where('id_kube', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        abort_if($pengajuan_kube->isEmpty(), 404);

        $pengajuan = $pengajuan_kube->first();

        return view('kepala_dinas.persetujuan_kube.persetujuan_bantuan_detail', compact('pengajuan', 'pengajuan_kube'));
    }

    public function unduhBeritaAcara($id)
    {
        $pengajuan = PengajuanKube::with([
            'kube',
            'jenisBantuan',
            'penyetuju',
        ])->findOrFail($id);

        if (!in_array($pengajuan->status_pengajuan, ['disetujui', 'cair'])) {
            return redirect()->back()->with('error', 'Berita acara hanya bisa diunduh untuk pengajuan yang sudah disetujui atau cair.');
        }

        $tanggalCetak = Carbon::now()->locale('id');
        $tanggalPengajuan = $pengajuan->tanggal_pengajuan
            ? Carbon::parse($pengajuan->tanggal_pengajuan)->locale('id')
            : null;

        $data = [
            'pengajuan' => $pengajuan,
            'tanggalCetak' => $tanggalCetak,
            'tanggalPengajuan' => $tanggalPengajuan,
            'namaPenandatangan' => $pengajuan->penyetuju->nama ?? '................................',
            'jabatanPenandatangan' => 'Kepala Dinas Sosial Kabupaten Cilacap',
        ];

        $pdf = Pdf::loadView('admin.alur_bantuan.persetujuan_bantuan_ba', $data)
            ->setPaper('a4', 'portrait');

        $namaFile = 'berita_acara_kube_' .
            str_replace(' ', '_', strtolower($pengajuan->kube->nama_kube ?? 'kube')) .
            '_' . $pengajuan->id_pengajuan_kube . '.pdf';

        return $pdf->download($namaFile);
    }

    public function unduhBeritaAcaraSemua($id_kube)
    {
        $pengajuan_kube = PengajuanKube::with([
            'kube',
            'jenisBantuan',
            'penyetuju',
        ])->where('id_kube', $id_kube)
            ->whereIn('status_pengajuan', ['disetujui', 'cair'])
            ->orderBy('tanggal_pengajuan')
            ->get();

        if ($pengajuan_kube->isEmpty()) {
            return redirect()->back()->with('error', 'Belum ada jenis bantuan yang disetujui untuk KUBE ini.');
        }

        $kube = $pengajuan_kube->first()->kube;
        $penyetuju = $pengajuan_kube->firstWhere('penyetuju', '!=', null)?->penyetuju;

        $data = [
            'kube' => $kube,
            'pengajuan_kube' => $pengajuan_kube,
            'tanggalCetak' => Carbon::now()->locale('id'),
            'namaPenandatangan' => $penyetuju->nama ?? '................................',
            'jabatanPenandatangan' => 'Kepala Dinas Sosial Kabupaten Cilacap',
        ];

        $pdf = Pdf::loadView('admin.alur_bantuan.persetujuan_bantuan_ba_semua', $data)
            ->setPaper('a4', 'portrait');

        $namaFile = 'berita_acara_semua_bantuan_kube_' .
            str_replace(' ', '_', strtolower($kube->nama_kube ?? 'kube')) .
            '.pdf';

        return $pdf->download($namaFile);
    }
}
