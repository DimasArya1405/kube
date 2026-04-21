<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kube;
use App\Models\Kecamatan;
use App\Models\ClusterUsaha;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanKecamatanController extends Controller
{
    
    // =======================
    // DETAIL DATA KUBE (OPTIONAL)
    // =======================
    public function detail($id)
    {
        $data = $this->getDetailData($id);

        return view('admin.laporan.detail_kecamatan', compact('data'));
    }

    // =======================
    // EXPORT PDF 🔥
    // =======================
    public function exportPdf($id)
    {
        $data = $this->getDetailData($id);

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('data'));

        return $pdf->download('laporan_kube_'.$id.'.pdf');
    }

    // =======================
    // FUNCTION AMBIL DATA DETAIL (BIAR GA NGULANG)
    // =======================
    private function getDetailData($id)
    {
        return DB::table('kube')
            ->join('desa_kelurahan','kube.id_desa_kelurahan','=','desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan','desa_kelurahan.id_kecamatan','=','kecamatan.id_kecamatan')
            ->join('cluster_usaha','kube.id_cluster','=','cluster_usaha.id_cluster')

            // RELASI FIX (INI YANG PENTING)
            ->leftJoin('pengajuan_kube','kube.id_kube','=','pengajuan_kube.id_kube')
            ->leftJoin('laporan_keuangan','pengajuan_kube.id_pengajuan_kube','=','laporan_keuangan.id_persetujuan')

            ->where('kube.id_kube', $id)

            ->select(
                'kube.id_kube',
                'kube.nama_kube',
                'kecamatan.nama_kecamatan',
                'cluster_usaha.nama_cluster',
                DB::raw('COALESCE(SUM(laporan_keuangan.total_omset),0) as total_omset'),
                DB::raw('COALESCE(SUM(laporan_keuangan.laba_bersih),0) as laba_bersih'),
                'kube.status'
            )

            ->groupBy(
                'kube.id_kube',
                'kube.nama_kube',
                'kecamatan.nama_kecamatan',
                'cluster_usaha.nama_cluster',
                'kube.status'
            )

            ->first();
    }


    // =======================
    // HALAMAN UTAMA
    // =======================
    public function index(Request $request)
    {
        $tahun = Kube::selectRaw('YEAR(COALESCE(tanggal_terbentuk, created_at)) as tahun')
            ->distinct()
            ->orderBy('tahun','desc')
            ->get();

        $kecamatan = Kecamatan::all();
        $cluster = ClusterUsaha::all();

        $query = DB::table('kube')
            ->join('desa_kelurahan','kube.id_desa_kelurahan','=','desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan','desa_kelurahan.id_kecamatan','=','kecamatan.id_kecamatan')
            ->join('cluster_usaha','kube.id_cluster','=','cluster_usaha.id_cluster')

            // RELASI FIX
            ->leftJoin('pengajuan_kube','kube.id_kube','=','pengajuan_kube.id_kube')
            ->leftJoin('laporan_keuangan','pengajuan_kube.id_pengajuan_kube','=','laporan_keuangan.id_persetujuan');

        // =======================
        // FILTER
        // =======================
        if($request->tahun && $request->tahun != 'all'){
            $query->whereYear(
                DB::raw('COALESCE(kube.tanggal_terbentuk, kube.created_at)'),
                $request->tahun
            );
        }

        if($request->kecamatan && $request->kecamatan != 'all'){
            $query->where('kecamatan.id_kecamatan', $request->kecamatan);
        }

        if($request->cluster && $request->cluster != 'all'){
            $query->where('cluster_usaha.id_cluster', $request->cluster);
        }

        // 🔥 WAJIB: hanya yang disetujui
        $query->where('pengajuan_kube.status_pengajuan', 'disetujui');

        // =======================
        // AMBIL DATA
        // =======================
        $data = $query->select(
                'kube.id_kube',
                'kube.nama_kube',
                'kecamatan.nama_kecamatan',
                'cluster_usaha.nama_cluster',
                DB::raw('COALESCE(SUM(laporan_keuangan.total_omset),0) as total_omset'),
                DB::raw('COALESCE(SUM(laporan_keuangan.laba_bersih),0) as laba_bersih'),
                'kube.status'
            )
            ->groupBy(
                'kube.id_kube',
                'kube.nama_kube',
                'kecamatan.nama_kecamatan',
                'cluster_usaha.nama_cluster',
                'kube.status'
            )
            ->get();

        // =======================
        // STATISTIK
        // =======================
        $totalKube = $data->count();
        $kubeAktif = $data->where('status','aktif')->count();
        $kubeNonaktif = $data->where('status','!=','aktif')->count();
        $totalOmset = $data->sum('total_omset');
        $totalLaba = $data->sum('laba_bersih');

        return view('admin.laporan.kecamatan', compact(
            'tahun',
            'kecamatan',
            'cluster',
            'data',
            'totalKube',
            'kubeAktif',
            'kubeNonaktif',
            'totalOmset',
            'totalLaba'
        ));
    }
}