<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Kube;
use App\Models\Kecamatan;
use App\Models\ClusterUsaha;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanKecamatanExport;

class LaporanKecamatanController extends Controller
{

    // =======================
    // EXPORT PDF (FILTER)
    // =======================
    public function exportPdfKecamatan(Request $request)
    {
        $data = $this->getFilteredData($request);

        // 🔥 TAMBAHAN STATISTIK (WAJIB BIAR CARD MUNCUL)
        $totalKube = $data->count();
        $kubeAktif = $data->where('status','aktif')->count();
        $kubeNonaktif = $data->where('status','!=','aktif')->count();
        $totalOmset = $data->sum('total_omset');
        $totalLaba = $data->sum('laba_bersih');

        $pdf = Pdf::loadView('admin.laporan.pdf_kecamatan', [
            'data' => $data,
            'totalKube' => $totalKube,
            'kubeAktif' => $kubeAktif,
            'kubeNonaktif' => $kubeNonaktif,
            'totalOmset' => $totalOmset,
            'totalLaba' => $totalLaba,
            // 🔥 TAMBAHAN INI
    'filterKecamatan' => $request->kecamatan 
        ? DB::table('kecamatan')->where('id_kecamatan',$request->kecamatan)->value('nama_kecamatan') 
        : 'Semua Kecamatan',

    'filterTahun' => $request->tahun ?? 'Semua Tahun',
    'filterCluster' => $request->cluster 
        ? DB::table('cluster_usaha')->where('id_cluster',$request->cluster)->value('nama_cluster') 
        : 'Semua Cluster',
]);

        return $pdf->download('laporan_kecamatan.pdf');
    }

    // =======================
    // EXPORT EXCEL
    // =======================
    public function exportExcel(Request $request)
    {
        return Excel::download(
            new LaporanKecamatanExport($request),
            'laporan_kecamatan.xlsx'
        );
    }

    // =======================
    // DETAIL
    // =======================
    public function detail($id)
    {
        $data = $this->getDetailData($id);

        if (!$data) {
            abort(404);
        }

        return view('admin.laporan.detail_kecamatan', compact('data'));
    }

    // =======================
    // EXPORT PDF DETAIL
    // =======================
    public function exportPdf($id)
    {
        $data = $this->getDetailData($id);

        if (!$data) {
            abort(404);
        }

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('data'));

        return $pdf->download('laporan_kube_'.$id.'.pdf');
    }

    // =======================
    // 🔥 FILTER DATA (CORE)
    // =======================
    private function getFilteredData($request)
    {
        $query = DB::table('kube')
            ->join('desa_kelurahan','kube.id_desa_kelurahan','=','desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan','desa_kelurahan.id_kecamatan','=','kecamatan.id_kecamatan')
            ->join('cluster_usaha','kube.id_cluster','=','cluster_usaha.id_cluster')
            ->join('kategori','cluster_usaha.id_kategori','=','kategori.id_kategori')

            ->leftJoin('pengajuan_kube','kube.id_kube','=','pengajuan_kube.id_kube')
            ->leftJoin('laporan_keuangan','kube.id_kube','=','laporan_keuangan.id_kube')
            ->leftJoin('data_perkembangan_usaha','laporan_keuangan.id_laporan','=','data_perkembangan_usaha.id_laporan')

            ->leftJoin('pembagian_pendamping','kube.id_kube','=','pembagian_pendamping.id_kube')
            ->leftJoin('pendamping','pembagian_pendamping.id_pendamping','=','pendamping.id_pendamping')

            ->where('pengajuan_kube.status_pengajuan','disetujui');

        // FILTER
        if(!empty($request->tahun)){
           $query->where('laporan_keuangan.periode_tahun', $request->tahun);
        }

        if(!empty($request->kecamatan)){
            $query->where('kecamatan.id_kecamatan',$request->kecamatan);
        }

        if(!empty($request->cluster)){
            $query->where('cluster_usaha.id_cluster',$request->cluster);
        }

        return $query->select(
                'kube.id_kube',
                'kube.nama_kube',
                'kecamatan.nama_kecamatan',
                'cluster_usaha.nama_cluster',
                'kategori.nama_kategori',

                // 🔥 FIX DUPLIKAT
                DB::raw('MAX(pendamping.nama_pendamping) as nama_pendamping'),

                'desa_kelurahan.nama_desa_kelurahan',
                'kube.tanggal_terbentuk',

                DB::raw('COALESCE(SUM(laporan_keuangan.total_omset),0) as total_omset'),
                DB::raw('COALESCE(SUM(laporan_keuangan.laba_bersih),0) as laba_bersih'),

                DB::raw("
                    (
                        SELECT dpu.perkembangan_usaha
                        FROM data_perkembangan_usaha dpu
                        JOIN laporan_keuangan lk
                            ON lk.id_laporan = dpu.id_laporan
                        WHERE lk.id_kube = kube.id_kube
                        ORDER BY dpu.periode_tahun DESC,
                                dpu.periode_bulan DESC
                        LIMIT 1
                    ) as perkembangan_usaha
                    "),

                'kube.status'
            )
            ->groupBy(
                'kube.id_kube',
                'kube.nama_kube',
                'kecamatan.nama_kecamatan',
                'cluster_usaha.nama_cluster',
                'kategori.nama_kategori',
                'desa_kelurahan.nama_desa_kelurahan',
                'kube.tanggal_terbentuk',
                'kube.status'
            )
            ->get();
    }

    // =======================
    // DETAIL DATA (LEBIH EFISIEN)
    // =======================
    private function getDetailData($id)
    {
        $query = $this->getFilteredData((object)[]);
        return $query->firstWhere('id_kube', $id);
    }

    // =======================
    // HALAMAN UTAMA
    // =======================
    public function index(Request $request)
    {
        $tahun = DB::table('laporan_keuangan')
    ->select('periode_tahun as tahun')
    ->distinct()
    ->orderBy('tahun', 'desc')
    ->get();
        $kecamatan = Kecamatan::all();
        $cluster = ClusterUsaha::all();

        $data = $this->getFilteredData($request);

        $totalKube = $data->count();
       $kubeAktif = $data->where('status','Aktif')->count();
        $kubeNonaktif = $data->where('status','!=','Aktif')->count();
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