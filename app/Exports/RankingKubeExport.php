<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class RankingKubeExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithEvents,
    WithCustomStartCell
{
    protected $data;
    protected $filterAktif;
    protected $adaFilter;
    protected $hideNominal;

    public function __construct($data, $filterAktif = [], $hideNominal = false)
    {
        $this->data        = $data;
        $this->filterAktif = $filterAktif;
        $this->adaFilter   = count($filterAktif) > 0;
        $this->hideNominal = $hideNominal;
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
        $heads = ['No', 'Nama KUBE', 'Cluster', 'Kecamatan'];

        if (!$this->hideNominal) {
            $heads = array_merge($heads, ['Total Omset', 'Total Pengeluaran', 'Total Laba Bersih']);
        }

        $heads[] = 'Status';
        $heads[] = 'Peringkat (Keseluruhan)';

        if ($this->adaFilter) {
            $heads[] = 'Peringkat (Filter)';
        }

        return $heads;
    }

    public function map($row): array
    {
        static $no = 0;
        $no++;

        $data = [$no, $row->nama_kube, $row->nama_cluster, $row->nama_kecamatan];

        if (!$this->hideNominal) {
            $data[] = $row->total_omset;
            $data[] = $row->total_pengeluaran;
            $data[] = $row->total_laba_bersih;
        }

        $data[] = $row->status;
        $data[] = $row->ranking_overall;

        if ($this->adaFilter) {
            $data[] = $row->ranking_filter;
        }

        return $data;
    }

    /**
     * Hitung kolom-kolom penting secara dinamis berdasarkan apakah nominal
     * dan filter ditampilkan, supaya tidak ada huruf kolom hardcoded yang
     * jadi salah posisi ketika kolom nominal disembunyikan.
     */
    protected function columnMap(): array
    {
        // Urutan kolom dasar: No, Nama, Cluster, Kecamatan
        $index = 4;

        $omsetCol = $pengeluaranCol = $labaCol = null;
        if (!$this->hideNominal) {
            $omsetCol       = Coordinate::stringFromColumnIndex(++$index);
            $pengeluaranCol = Coordinate::stringFromColumnIndex(++$index);
            $labaCol        = Coordinate::stringFromColumnIndex(++$index);
        }

        $statusCol = Coordinate::stringFromColumnIndex(++$index);
        $rankCol   = Coordinate::stringFromColumnIndex(++$index);

        $rankFilterCol = null;
        if ($this->adaFilter) {
            $rankFilterCol = Coordinate::stringFromColumnIndex(++$index);
        }

        return [
            'no'           => 'A',
            'omset'        => $omsetCol,
            'pengeluaran'  => $pengeluaranCol,
            'laba'         => $labaCol,
            'status'       => $statusCol,
            'rank'         => $rankCol,
            'rankFilter'   => $rankFilterCol,
            'last'         => Coordinate::stringFromColumnIndex($index),
        ];
    }

    public function styles(Worksheet $sheet)
    {
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
                $sheet        = $event->sheet->getDelegate();
                $cols         = $this->columnMap();
                $lastCol      = $cols['last'];
                $headingRow   = $this->adaFilter ? 4 : 3;
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

                // ── Format mata uang & SUM (hanya jika nominal ditampilkan) ──
                if (!$this->hideNominal) {
                    $sheet->getStyle("{$cols['omset']}{$firstDataRow}:{$cols['laba']}{$totalRows}")
                        ->getNumberFormat()
                        ->setFormatCode('"Rp "#,##0');
                }

                // ── Alignment kolom: No, Status, Peringkat ───────────────────
                $sheet->getStyle("A{$firstDataRow}:A{$totalRows}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("{$cols['status']}{$firstDataRow}:{$cols['status']}{$totalRows}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle("{$cols['rank']}{$firstDataRow}:{$cols['rank']}{$totalRows}")
                    ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                if ($this->adaFilter) {
                    $sheet->getStyle("{$cols['rankFilter']}{$firstDataRow}:{$cols['rankFilter']}{$totalRows}")
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

                // ── Baris TOTAL ──────────────────────────────────────────────
                $totalRow = $totalRows + 1;

                // Merge label "TOTAL" dari kolom A sampai sebelum kolom nominal
                // (atau sampai sebelum kolom Status kalau nominal disembunyikan)
                $labelEndCol = $this->hideNominal ? 'D' : 'D';
                $sheet->mergeCells("A{$totalRow}:{$labelEndCol}{$totalRow}");
                $sheet->setCellValue("A{$totalRow}", 'TOTAL');

                if (!$this->hideNominal) {
                    // SUM otomatis untuk Omset, Pengeluaran, Laba Bersih
                    $sheet->setCellValue("{$cols['omset']}{$totalRow}", "=SUM({$cols['omset']}{$firstDataRow}:{$cols['omset']}{$totalRows})");
                    $sheet->setCellValue("{$cols['pengeluaran']}{$totalRow}", "=SUM({$cols['pengeluaran']}{$firstDataRow}:{$cols['pengeluaran']}{$totalRows})");
                    $sheet->setCellValue("{$cols['laba']}{$totalRow}", "=SUM({$cols['laba']}{$firstDataRow}:{$cols['laba']}{$totalRows})");

                    // Format mata uang di baris total
                    $sheet->getStyle("{$cols['omset']}{$totalRow}:{$cols['laba']}{$totalRow}")
                        ->getNumberFormat()
                        ->setFormatCode('"Rp "#,##0');
                }

                // Style baris total: biru gelap, teks putih, bold
                $sheet->getStyle("A{$totalRow}:{$lastCol}{$totalRow}")->applyFromArray([
                    'font' => [
                        'bold'  => true,
                        'size'  => 11,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF1E40AF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension($totalRow)->setRowHeight(20);

                // ── Border seluruh tabel (heading s/d baris total) ───────────
                $sheet->getStyle("A{$headingRow}:{$lastCol}{$totalRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()->setARGB('FFDDDDDD');
            },
        ];
    }
}