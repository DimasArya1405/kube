<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanKecamatanExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = DB::table('kube')
            ->join('desa_kelurahan', 'kube.id_desa_kelurahan', '=', 'desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan', 'desa_kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->join('cluster_usaha', 'kube.id_cluster', '=', 'cluster_usaha.id_cluster')
            ->join('kategori', 'cluster_usaha.id_kategori', '=', 'kategori.id_kategori')
            ->leftJoin('pengajuan_kube', 'kube.id_kube', '=', 'pengajuan_kube.id_kube')
            ->leftJoin('laporan_keuangan', 'kube.id_kube', '=', 'laporan_keuangan.id_kube')
            ->leftJoin('data_perkembangan_usaha', 'laporan_keuangan.id_laporan', '=', 'data_perkembangan_usaha.id_laporan')
            ->where('pengajuan_kube.status_pengajuan', 'disetujui');

        if ($this->request->tahun) {
            $query->where('laporan_keuangan.periode_tahun', $this->request->tahun);
        }

        if ($this->request->kecamatan) {
            $query->where('kecamatan.id_kecamatan', $this->request->kecamatan);
        }

        if ($this->request->cluster) {
            $query->where('cluster_usaha.id_cluster', $this->request->cluster);
        }

        return $query->select(
            'kube.nama_kube',
            'kecamatan.nama_kecamatan',
            'cluster_usaha.nama_cluster',
            'kategori.nama_kategori',
            'desa_kelurahan.nama_desa_kelurahan',
            'kube.tanggal_terbentuk',
            DB::raw('COALESCE(SUM(laporan_keuangan.total_omset), 0) as total_omset'),
            DB::raw('COALESCE(SUM(laporan_keuangan.laba_bersih), 0) as laba_bersih'),
            DB::raw('MAX(data_perkembangan_usaha.perkembangan_usaha) as perkembangan_usaha'),
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

    public function headings(): array
    {
        return [
            'Nama KUBE',
            'Kecamatan',
            'Cluster',
            'Kategori',
            'Desa',
            'Tanggal Terbentuk',
            'Omset',
            'Laba Bersih',
            'Perkembangan',
            'Status',
        ];
    }
}