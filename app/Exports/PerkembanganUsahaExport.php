<?php

namespace App\Exports;

use App\Models\DataPerkembanganUsaha;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PerkembanganUsahaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return DataPerkembanganUsaha::with('laporan.cluster.kube')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama KUBE',
            'Periode',
            'Omset',
            'Total Pengeluaran',
            'Laba Bersih',
            'Selisih Laba',
            'Total Omset',
            'Perkembangan',
            'Status',
            'Hasil Evaluasi',
            'Rekomendasi',
            'Tanggal Input',
        ];
    }

    public function map($item): array
    {
        static $no = 0;
        $no++;

        $namaKube = '-';

        if ($item->laporan && $item->laporan->cluster) {
            $kube = $item->laporan->cluster->kube->first();

            if ($kube) {
                $namaKube = $kube->nama_kube;
            }
        }

        return [
            $no,
            $namaKube,
            ($item->periode_bulan ?? '-') . '/' . ($item->periode_tahun ?? '-'),

            'Rp ' . number_format($item->omset_pendapatan ?? 0, 0, ',', '.'),
            'Rp ' . number_format($item->total_pengeluaran ?? 0, 0, ',', '.'),
            'Rp ' . number_format($item->laba_bersih ?? 0, 0, ',', '.'),
            'Rp ' . number_format($item->selisih_laba ?? 0, 0, ',', '.'),
            'Rp ' . number_format($item->total_omset ?? 0, 0, ',', '.'),

            $item->perkembangan_usaha ?? '-',
            $item->status_hasil ?? '-',
            $item->hasil_evaluasi ?? '-',
            $item->rekomendasi ?? '-',
            $item->created_at
                ? $item->created_at->format('d M Y')
                : '-',
        ];
    }
}