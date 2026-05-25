<?php

namespace App\Exports;

use App\Models\PembagianPendamping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PembagianPendampingExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        // Tarik data sekalian bawa relasi ke tabel KUBE dan Pendamping
        // (Catatan: Kalau nama relasi lu 'pendampingAsli', ganti aja ya)
        return PembagianPendamping::with(['kube', 'pendamping'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama KUBE',
            'Nama Pendamping',
            'Tanggal Pembagian',
            'Status'
        ];
    }

    public function map($pembagian): array
    {
        static $no = 1;
        return [
            $no++,
            $pembagian->kube->nama_kube ?? '-',
            $pembagian->pendamping->nama_pendamping ?? '-', // Sesuaikan kalau pakenya pendampingAsli
            $pembagian->tgl_pembagian ? \Carbon\Carbon::parse($pembagian->tgl_pembagian)->format('d M Y') : '-',
            $pembagian->status
        ];
    }
}