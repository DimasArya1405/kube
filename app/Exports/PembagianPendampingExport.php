<?php

namespace App\Exports;

use App\Models\PembagianPendamping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class PembagianPendampingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        // Mengambil data beserta relasinya agar tidak lambat (Eager Loading)
        return PembagianPendamping::with(['kube', 'pendamping'])->get();
    }

    public function headings(): array
    {
        // Menambahkan header sesuai dengan kolom di migration
        return [
            'No',
            'Nama KUBE',
            'Nama Pendamping',
            'Tanggal Pembagian',
            'Tanggal Selesai',
            'Status'
        ];
    }

    public function map($pembagian): array
    {
        static $no = 1;

        return [
            $no++,
            $pembagian->kube->nama_kube ?? '-',
            $pembagian->pendamping->nama_pendamping ?? '-',
            // Format tanggal pembagian (D-M-Y)
            $pembagian->tgl_pembagian ? Carbon::parse($pembagian->tgl_pembagian)->format('d-m-Y') : '-',
            // Format tanggal selesai (D-M-Y)
            $pembagian->tgl_selesai ? Carbon::parse($pembagian->tgl_selesai)->format('d-m-Y') : '-',
            $pembagian->status
        ];
    }
}