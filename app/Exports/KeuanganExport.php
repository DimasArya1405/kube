<?php

namespace App\Exports;

use App\Models\Keuangan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class KeuanganExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected $id_kube;

    public function __construct($id_kube = null)
    {
        $this->id_kube = $id_kube;
    }

    public function collection()
    {
        $query = \App\Models\Keuangan::query()->with(['kube', 'cluster']);
        if ($this->id_kube) {
            $query->where('id_kube', $this->id_kube);
        }
        return $query->orderBy('tanggal_laporan', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama KUBE',
            'Cluster Usaha',
            'Periode',
            'Tanggal Laporan',
            'Omset Pendapatan (Rp)',
            'Total Pengeluaran (Rp)',
            'Laba Bersih (Rp)',
            'Keterangan'
        ];
    }
    
    private $rowNumber = 0;
    public function map($row): array
    {
        $this->rowNumber++;
        $bulanIndo = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        $periode = ($bulanIndo[$row->periode_bulan] ?? $row->periode_bulan) . ' ' . $row->periode_tahun;
        $labaBersih = $row->omset_pendapatan - $row->total_pengeluaran;

        return [
            $this->rowNumber,
            $row->kube->nama_kube ?? '-',
            $row->cluster->nama_cluster ?? '-',
            $periode,
            \Carbon\Carbon::parse($row->tanggal_laporan)->translatedFormat('d F Y'),
            $row->omset_pendapatan,
            $row->total_pengeluaran,
            $labaBersih,
            $row->keterangan ?? '-'
        ];
    }
}