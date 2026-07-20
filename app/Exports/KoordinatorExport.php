<?php

namespace App\Exports;

use App\Models\Koordinator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class KoordinatorExport implements
    FromCollection,
    WithHeadings,
    WithStyles,
    WithEvents,
    WithCustomStartCell
{
    protected $data;
    protected $filterStatus;

    public function __construct($filterStatus = null)
    {
        $this->filterStatus = $filterStatus;

        $query = Koordinator::with(['user', 'kecamatan', 'desa']);
        if ($filterStatus) {
            $query->where('status', $filterStatus);
        }
        $this->data = $query->get();
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                'Nama'           => $item->nama_koordinator ?? '-',
                'NIK'            => "\t" . ($item->nik ?? '-'),
                'Jenis Kelamin'  => $item->jenis_kelamin === 'L' ? 'Laki-laki' : ($item->jenis_kelamin === 'P' ? 'Perempuan' : '-'),
                'Tempat Lahir'   => $item->tempat_lahir ?? '-',
                'Tanggal Lahir'  => $item->tanggal_lahir ? date('d-m-Y', strtotime($item->tanggal_lahir)) : '-',
                'No HP'          => "\t" . ($item->no_hp ?? '-'),
                'Email'          => $item->email ?? '-',
                'Pendidikan'     => $item->pendidikan_terakhir ?? '-',
                'Kecamatan'      => $item->kecamatan->nama_kecamatan ?? '-',
                'Desa/Kelurahan' => $item->desa->nama_desa_kelurahan ?? '-',
                'Wilayah'        => $item->wilayah ?? '-',
                'Alamat'         => $item->alamat ?? '-',
                'Status'         => $item->status,
            ];
        });
    }

    public function startCell(): string { return 'A3'; }

    public function headings(): array
    {
        return [
            'Nama', 'NIK', 'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir',
            'No HP', 'Email', 'Pendidikan', 'Kecamatan', 'Desa/Kelurahan',
            'Wilayah', 'Alamat', 'Status',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            3 => [
                'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF2563EB']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet        = $event->sheet->getDelegate();
                $lastCol      = 'M'; // 13 kolom: A-M
                $statusCol    = 'M'; // kolom Status sekarang di M
                $headingRow   = 3;
                $firstDataRow = 4;
                $totalRows    = $this->data->count() + $headingRow;

                // ── Baris 1: Judul ──────────────────────────────────────────
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->setCellValue('A1', 'Data Koordinator KUBE');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(22);

                // ── Baris 2: Subtitle ───────────────────────────────────────
                $filterLabel = $this->filterStatus ? ' | Filter Status: ' . ucfirst($this->filterStatus) : '';
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->setCellValue('A2', 'Daftar koordinator KUBE' . $filterLabel . ' — Dicetak ' . now()->format('d/m/Y H:i'));
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 10, 'color' => ['argb' => 'FF666666']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Auto-width ───────────────────────────────────────────────
                foreach (range('A', $lastCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // ── Alignment status ─────────────────────────────────────────
                $sheet->getStyle("{$statusCol}{$firstDataRow}:{$statusCol}{$totalRows}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // ── Zebra striping ───────────────────────────────────────────
                for ($row = $firstDataRow; $row <= $totalRows; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$lastCol}{$row}")
                            ->getFill()->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFF9FAFB');
                    }
                }

                // ── Border tabel ─────────────────────────────────────────────
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$totalRows}")
                    ->getBorders()->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()->setARGB('FFDDDDDD');

                // ── Warna status ─────────────────────────────────────────────
                for ($row = $firstDataRow; $row <= $totalRows; $row++) {
                    $status = $sheet->getCell("{$statusCol}{$row}")->getValue();
                    $color  = $status === 'Aktif' ? 'FF1D4ED8' : 'FFDC2626';
                    $sheet->getStyle("{$statusCol}{$row}")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['argb' => $color]],
                    ]);
                }

                // ── Ringkasan teks di bawah tabel ────────────────────────────
                $aktif    = $this->data->where('status', 'Aktif')->count();
                $nonAktif = $this->data->where('status', 'Tidak Aktif')->count();
                $total    = $this->data->count();

                $summaryRow = $totalRows + 2;
                $sheet->mergeCells("A{$summaryRow}:{$lastCol}{$summaryRow}");
                $sheet->setCellValue("A{$summaryRow}",
                    "Aktif: {$aktif}     Tidak Aktif: {$nonAktif}     Total: {$total}"
                );
                $sheet->getStyle("A{$summaryRow}")->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 11],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                ]);
            },
        ];
    }
}