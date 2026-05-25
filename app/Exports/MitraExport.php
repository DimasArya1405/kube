<?php

namespace App\Exports;

use App\Models\Mitra;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MitraExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
    * Ambil data mitra beserta relasi kolaborasinya
    */
    public function collection()
    {
        return Mitra::with('bantuan_kolaborasi')->get();
    }

    /**
    * Header Tabel Excel
    */
    public function headings(): array
    {
        return [
            ['LAPORAN DATA MITRA SISTEM KUBE'], // Judul besar di baris 1
            ['Dicetak pada: ' . now()->timezone('Asia/Jakarta')->format('d/m/Y H:i')], // Waktu di baris 2
            [], // Baris kosong
            [
                'No',
                'Nama Mitra',
                'Alamat',
                'Email Perusahaan',
                'Telp Perusahaan',
                'Nama PIC',
                'Telp PIC',
                'Tanggal MOU',
                'Masa Berlaku (Thn)',
                'Status',
                'Jumlah Kolaborasi'
            ]
        ];
    }

    /**
    * Mapping data per kolom
    */
    public function map($mitra): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $mitra->nama_mitra,
            $mitra->alamat,
            $mitra->email,
            $mitra->no_telp,
            $mitra->nama_pic,
            $mitra->telp_pic,
            \Carbon\Carbon::parse($mitra->tgl_mou)->format('d/m/Y'),
            $mitra->masa_berlaku,
            $mitra->status, // Menggunakan accessor 'Aktif/Tidak Aktif'
            $mitra->bantuan_kolaborasi->count(),
        ];
    }

    /**
    * Styling agar Excel terlihat profesional
    */
    public function styles(Worksheet $sheet)
    {
        // Membuat judul jadi Bold
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        // Membuat header tabel jadi Bold dan berwarna abu-abu
        $sheet->getStyle('A4:K4')->getFont()->setBold(true);
        $sheet->getStyle('A4:K4')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('F2F2F2');

        // Menambah baris total di paling bawah secara manual bisa dilakukan di Controller 
        // atau dengan menambah AfterSheet events, tapi untuk konten data, ini sudah lengkap.
        
        return [];
    }
}