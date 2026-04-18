<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell; // ← tambah ini
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RankingKubeExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithEvents,
    WithCustomStartCell // ← tambah ini
{
    protected $data;
    protected $filterAktif;
    protected $adaFilter;

    public function __construct($data, $filterAktif = [])
    {
        $this->data        = $data;
        $this->filterAktif = $filterAktif;
        $this->adaFilter   = count($filterAktif) > 0;
    }

    public function collection()
    {
        return $this->data;
    }

    /**
     * Data mulai di baris 3 (tanpa filter) atau baris 4 (ada filter).
     * Baris sebelumnya dipakai untuk judul, subtitle, dan info filter.
     */
    public function startCell(): string
    {
        return $this->adaFilter ? 'A4' : 'A3';
    }

    public function headings(): array
    {
        $heads = [
            'No', 'Nama KUBE', 'Cluster', 'Kecamatan',
            'Total Omset', 'Total Pengeluaran', 'Total Laba Bersih',
            'Status', 'Peringkat (Keseluruhan)',
        ];

        if ($this->adaFilter) {
            $heads[] = 'Peringkat (Filter)';
        }

        return $heads;
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $data = [
            $no,
            $row->nama_kube,
            $row->nama_cluster,
            $row->nama_kecamatan,
            $row->total_omset,
            $row->total_pengeluaran,
            $row->total_laba_bersih,
            $row->status,
            $row->ranking_overall,
        ];

        if ($this->adaFilter) {
            $data[] = $row->ranking_filter;
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        // Heading row sudah otomatis di baris yang benar karena startCell()
        $headingRow = $this->adaFilter ? 4 : 3;

        return [
            $headingRow => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF2563EB'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical'   => Alignment::VERTICAL_CENTER,
                    'wrapText'   => true,
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet      = $event->sheet->getDelegate();
                $lastCol    = $this->adaFilter ? 'J' : 'I';
                $headingRow = $this->adaFilter ? 4 : 3;
                $firstDataRow = $headingRow + 1;
                $totalRows    = $this->data->count() + $headingRow;

                // ── Baris 1: Judul ──────────────────────────────────────────
                $sheet->mergeCells("A1:{$lastCol}1");
                $sheet->setCellValue('A1', 'Ranking KUBE');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(22);

                // ── Baris 2: Subtitle / tanggal cetak ───────────────────────
                $sheet->mergeCells("A2:{$lastCol}2");
                $sheet->setCellValue('A2', 'Ranking KUBE terbaik berdasarkan laba bersih — Dicetak ' . now()->format('d/m/Y H:i'));
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 10, 'color' => ['argb' => 'FF666666']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ]);

                // ── Baris 3: Info filter (kalau ada) ────────────────────────
                if ($this->adaFilter) {
                    $filterText = 'Filter aktif:  ' . collect($this->filterAktif)
                        ->map(fn($v, $k) => "$k: $v")
                        ->implode('   |   ');

                    $sheet->mergeCells("A3:{$lastCol}3");
                    $sheet->setCellValue('A3', $filterText);
                    $sheet->getStyle('A3')->applyFromArray([
                        'font'      => ['size' => 10, 'color' => ['argb' => 'FF1E40AF']],
                        'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEFF6FF']],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT, 'indent' => 1],
                        'borders'   => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FFBFDBFE']],
                        ],
                    ]);
                    $sheet->getRowDimension(3)->setRowHeight(18);
                }

                // ── Auto-width semua kolom ───────────────────────────────────
                foreach (range('A', $lastCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // ── Format mata uang kolom E, F, G ──────────────────────────
                $sheet->getStyle("E{$firstDataRow}:G{$totalRows}")
                    ->getNumberFormat()
                    ->setFormatCode('"Rp "#,##0');

                // ── Alignment kolom: No, Status, Peringkat ───────────────────
                $sheet->getStyle("A{$firstDataRow}:A{$totalRows}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("H{$firstDataRow}:H{$totalRows}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("I{$firstDataRow}:I{$totalRows}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                if ($this->adaFilter) {
                    $sheet->getStyle("J{$firstDataRow}:J{$totalRows}")
                        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // ── Warna zebra striping pada baris data ─────────────────────
                for ($row = $firstDataRow; $row <= $totalRows; $row++) {
                    if ($row % 2 === 0) {
                        $sheet->getStyle("A{$row}:{$lastCol}{$row}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setARGB('FFF9FAFB');
                    }
                }

                // ── Border seluruh tabel ─────────────────────────────────────
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$totalRows}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()->setARGB('FFDDDDDD');
            },
        ];
    }
}