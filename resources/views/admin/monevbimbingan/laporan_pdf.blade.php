<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan KUBE - {{ $namaKube }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 15mm 20mm 15mm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background-color: #fff;
            line-height: 1.4;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }

        .kop-surat {
            border-bottom: 5px double #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
            text-align: center;
            position: relative;
        }

        .kop-logo {
            position: absolute;
            left: 0;
            top: 0;
            width: 70px;
            height: 85px;
        }

        .kop-header {
            margin: 0;
            padding: 0;
            text-transform: uppercase;
        }

        .kop-header .instansi-atas {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .kop-header .instansi-utama {
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 2px;
        }

        .kop-header .nama-kube {
            font-size: 15pt;
            font-weight: bold;
            color: #0b4d8c; 
            margin-top: 2px;
        }

        .kop-header .alamat {
            font-size: 10pt;
            font-style: italic;
            text-transform: none;
            margin-top: 5px;
            color: #333;
        }

        .judul-dokumen {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 13pt;
            margin-bottom: 20px;
            text-decoration: underline;
        }

        .meta-info {
            width: 100%;
            margin-bottom: 15px;
            font-size: 11pt;
        }
        .meta-info td {
            padding: 3px 0;
            vertical-align: top;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 11pt;
        }

        .table-data th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
            border: 1px solid #000;
            font-weight: bold;
            text-transform: uppercase;
            padding: 10px 8px;
            text-align: center;
            font-size: 10pt;
        }

        .table-data td {
            border: 1px solid #000;
            padding: 8px 8px;
            vertical-align: middle;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }

        .keterangan-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 40px;
            font-size: 11pt;
            background-color: #fafafa;
        }
        .keterangan-title {
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }

        .ttd-container {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .ttd-table {
            width: 100%;
            border: none;
        }

        .ttd-table td {
            border: none;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding-top: 10px;
        }

        .ttd-space {
            height: 75px; 
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }
        
        .ttd-jabatan {
            font-size: 10pt;
            color: #444;
        }
    </style>
</head>
<body>

    <div class="kop-surat">
        <div class="kop-header">
            <div class="instansi-atas">Kelompok Usaha Bersama (KUBE)</div>
            <div class="instansi-main">Binaan Dinas Sosial Kabupaten Cilacap</div>
            <div class="nama-kube">KUBE "{{ $namaKube }}"</div>
            <div class="alamat">Alamat Sekretariat: Jl. Jenderal Sudirman No. 12, Kabupaten Cilacap, Jawa Tengah</div>
        </div>
    </div>

    <div class="judul-dokumen">
        Laporan Pertanggungjawaban Keuangan Bulanan
    </div>

    <table class="meta-info">
        @if($laporans->count() === 1)
            @php $first = $laporans->first(); @endphp
            <tr>
                <td style="width: 18%;">Nama Kelompok</td>
                <td style="width: 2%;">:</td>
                <td style="font-weight: bold;">{{ $namaKube }}</td>
                <td style="width: 15%;">Tanggal Cetak</td>
                <td style="width: 2%;">:</td>
                <td>{{ date('d/m/Y') }}</td>
            </tr>
         <tr>
                <td>Periode Laporan</td>
                <td>:</td>
                <td style="text-transform: uppercase; font-weight: bold;">
                    @php
                        $namaBulanIndo = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                       
                        $bulanRaw = $first->periode_bulan;
                        $bulanIndo = is_numeric($bulanRaw) ? ($namaBulanIndo[(int)$bulanRaw] ?? $bulanRaw) : $bulanRaw;
                    @endphp
                    
                    {{ $bulanIndo }} {{ $first->periode_tahun }}
                </td>
            </tr>
        @else
            <tr>
                <td style="width: 18%;">Nama Kelompok</td>
                <td style="width: 2%;">:</td>
                <td style="font-weight: bold;">{{ $namaKube }}</td>
                <td style="width: 15%;">Tanggal Cetak</td>
                <td style="width: 2%;">:</td>
                <td>{{ date('d/m/Y') }}</td>
            </tr>
        @endif
    </table>

    <table class="table-data">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Tanggal</th>
                <th style="width: 25%;">Omset Pendapatan</th>
                <th style="width: 25%;">Total Pengeluaran</th>
                <th style="width: 25%;">Laba Bersih</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $index => $row)
                @php
                    $pengeluaran = $row->total_pengeluaran ?? ($row->omset_pendapatan - $row->laba_bersih);
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($row->tanggal_laporan ?? now())) }}</td>
                    <td class="text-right">Rp {{ number_format($row->omset_pendapatan, 0, ',', '.') }}</td>
                    <td class="text-right" style="color: #bc2828;">Rp {{ number_format($pengeluaran, 0, ',', '.') }}</td>
                    <td class="text-right" style="font-weight: bold; color: #15803d;">Rp {{ number_format($row->laba_bersih, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="font-style: italic; padding: 20px;">Data laporan keuangan tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
        
        @if($laporans->count() > 1)
        <tfoot>
            <tr style="background-color: #f9f9f9; font-weight: bold;">
                <td colspan="2" class="text-center">TOTAL REKAPITULASI</td>
                <td class="text-right">Rp {{ number_format($laporans->sum('omset_pendapatan'), 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($laporans->sum('omset_pendapatan') - $laporans->sum('laba_bersih'), 0, ',', '.') }}</td>
                <td class="text-right" style="color: #15803d;">Rp {{ number_format($laporans->sum('laba_bersih'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>

    @if($laporans->count() === 1 && !empty($first->keterangan))
        <div class="keterangan-box">
            <div class="keterangan-title">Catatan Perkembangan Usaha KUBE:</div>
            <div>"{{ $first->keterangan }}"</div>
        </div>
    @endif

    <div class="ttd-container">
        <table class="ttd-table">
            <tr>
                <td>
                    <br>
                    Mengetahui,<br>
                    <span class="font-bold">Pendamping KUBE Kab. Cilacap</span>
                    <div class="ttd-space"></div>
                    <div class="ttd-nama">......................................................</div>
                    <div class="ttd-jabatan">Tim Dinas Sosial</div>
                </td>
                <td>
                    Cilacap, {{ date('d F Y') }}<br>
                    Melaporkan,<br>
                    <span class="font-bold">Ketua KUBE "{{ $namaKube }}"</span>
                    <div class="ttd-space"></div>
                    <div class="ttd-nama">{{ auth()->user()->name ?? 'Rani' }}</div>
                    <div class="ttd-jabatan">ID Ketua: {{ auth()->id() }}</div>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>