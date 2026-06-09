<?php

namespace App\Exports;

use App\Models\KunjunganPendamping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KunjunganPendampingExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return KunjunganPendamping::with([
            'pembagian.pendamping',
            'pembagian.kube'
        ])
        ->get()
        ->map(function ($item) {
            return [
                'Pendamping' => $item->pembagian->pendamping->nama_pendamping ?? '-',
                'KUBE' => $item->pembagian->kube->nama_kube ?? '-',
                'Tanggal' => $item->tanggal_kunjungan,
                'Waktu' => $item->waktu_kunjungan,
                'Tujuan' => $item->tujuan_kunjungan,
                'Kunjungan Ke' => $item->kunjungan_ke,
                'Status' => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Pendamping',
            'Nama KUBE',
            'Tanggal',
            'Waktu',
            'Tujuan',
            'Kunjungan Ke',
            'Status',
        ];
    }
}