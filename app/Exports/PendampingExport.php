<?php

namespace App\Exports;

use App\Models\Pendamping;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PendampingExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Pendamping::with('kecamatan')
            ->get()
            ->map(function ($item) {
                return [
                    'Nama'       => $item->nama_pendamping,
                    'NIK'        => $item->nik,
                    'Kecamatan'  => $item->kecamatan->nama_kecamatan ?? '-',
                    'No HP'      => $item->no_hp,
                    'Status'     => $item->status,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Pendamping',
            'NIK',
            'Kecamatan',
            'No HP',
            'Status'
        ];
    }
}
