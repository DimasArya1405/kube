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
            'Tenaga Kerja',
            'Perkembangan',
            'Tingkat Kemandirian',
            'Status',
            'Evaluasi',
            'Rekomendasi',
            'Tanggal Input',
        ];
    }

    public function map($item): array
    {
        $namaKube = '-';
        if ($item->laporan && $item->laporan->cluster) {
            $kube = $item->laporan->cluster->kube->first();
            if ($kube) $namaKube = $kube->nama_kube;
        }

        static $no = 0;
        $no++;

        return [
            $no,
            $namaKube,
            ($item->laporan->periode_bulan ?? '-') . '/' . ($item->laporan->periode_tahun ?? '-'),
            'Rp ' . number_format($item->omset_pendapatan ?? 0, 0, ',', '.'),
            'Rp ' . number_format($item->total_pengeluaran ?? 0, 0, ',', '.'),
            'Rp ' . number_format($item->laba_bersih ?? 0, 0, ',', '.'),
            $item->jumlah_tenaga_kerja ?? '-',
            $item->perkembangan_usaha ?? '-',
            $item->tingkat_kemandirian ?? '-',
            $item->status_hasil ?? '-',
            $item->hasil_evaluasi ?? '-',
            $item->rekomendasi ?? '-',
            $item->created_at->format('d M Y'),
        ];
    }
}