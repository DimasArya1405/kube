<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RekapKubeExport implements FromCollection, WithHeadings, WithStyles, WithTitle, ShouldAutoSize
{
    protected $id_kecamatan;
    protected $id_kategori;

    public function __construct($id_kecamatan = null, $id_kategori = null)
    {
        $this->id_kecamatan = $id_kecamatan;
        $this->id_kategori  = $id_kategori;
    }

    public function collection()
    {
        $query = DB::table('kube')
            ->join('desa_kelurahan', 'kube.id_desa_kelurahan', '=', 'desa_kelurahan.id_desa_kelurahan')
            ->join('kecamatan', 'desa_kelurahan.id_kecamatan', '=', 'kecamatan.id_kecamatan')
            ->join('cluster_usaha', 'kube.id_cluster', '=', 'cluster_usaha.id_cluster')
            ->join('kategori', 'cluster_usaha.id_kategori', '=', 'kategori.id_kategori')
            ->select(
                'kecamatan.nama_kecamatan',
                'kategori.nama_kategori',
                DB::raw('COUNT(kube.id_kube) as jumlah_kube'),
                DB::raw('SUM(CASE WHEN kube.status = "Aktif" THEN 1 ELSE 0 END) as kube_aktif'),
                DB::raw('SUM(CASE WHEN kube.status = "Tidak Aktif" THEN 1 ELSE 0 END) as kube_tidak_aktif')
            )
            ->groupBy('kecamatan.id_kecamatan', 'kecamatan.nama_kecamatan', 'kategori.id_kategori', 'kategori.nama_kategori')
            ->orderBy('kecamatan.nama_kecamatan');

        if (!empty($this->id_kecamatan)) {
            $query->where('kecamatan.id_kecamatan', $this->id_kecamatan);
        }

        if (!empty($this->id_kategori)) {
            $query->where('kategori.id_kategori', $this->id_kategori);
        }

        return $query->get()->map(function ($item, $index) {
            return [
                'No'            => $index + 1,
                'Kecamatan'     => $item->nama_kecamatan,
                'Kategori'      => $item->nama_kategori,
                'Jumlah KUBE'   => $item->jumlah_kube,
                'Aktif'         => $item->kube_aktif,
                'Tidak Aktif'   => $item->kube_tidak_aktif,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Kecamatan', 'Kategori', 'Jumlah KUBE', 'Aktif', 'Tidak Aktif'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D1D5DB'],
                ],
            ],
        ];
    }

    public function title(): string
    {
        return 'Rekap KUBE';
    }
}