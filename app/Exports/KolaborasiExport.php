<?php

namespace App\Exports;

use App\Models\KolaborasiBantuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Http\Request;

class KolaborasiExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = KolaborasiBantuan::with(['mitra', 'kube']);

        if ($this->request->filled('id_mitra')) {
            $query->where('id_mitra', $this->request->id_mitra);
        }

        if ($this->request->filled('tahun')) {
            
            $query->whereYear('tgl_pelaksanaan', $this->request->tahun);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        $fiterInfo = 'semua Data';
        if($this->request->filled('tahun')) $filterInfo = 'Tahun ' . $this->request->tahun;

        return [
            ['LAPORAN DATA KOLABORASI BANTUAN SISTEM KUBE'],
            ['Dicetak pada: ' . now()->timezone('Asia/Jakarta')->format('d/m/Y H:i') . ' WIB'],
            [], // Baris kosong
            [
                'No',
                'Nama Mitra',
                'Kelompok Kube',
                'Jenis Bantuan',
                'Nama Bantuan',
                'Tanggal Pelaksanaan',
                'Nilai Bantuan',
                'Deskripsi',
                'Status'
            ]
        ];
    }

    public function map($kolab): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $kolab->mitra->nama_mitra ?? '-',
            $kolab->kube->nama_kube ?? '-',
            $kolab->jenis_bantuan,
            $kolab->nama_bantuan,
            \Carbon\Carbon::parse($kolab->tgl_pelaksanaan)->format('d/m/Y'),
            $kolab->bantuan,
            $kolab->deskripsi,
            $kolab->status,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        // 1. Merge cell untuk Judul (A1 sampai I1)
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        
        // 2. Styling Judul
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // 3. Styling Header Tabel (Baris ke-4)
        $headerRange = 'A4:I4';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'], // Warna Biru Profesional
            ],
        ]);

        // 4. Tambah Border ke seluruh data
        $lastRow = $sheet->getHighestRow();
        $sheet->getStyle('A4:I' . $lastRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // 5. Rata Tengah untuk kolom tertentu (No, Tgl, Jenis, Status)
        $sheet->getStyle('A5:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F5:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('I5:I' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}