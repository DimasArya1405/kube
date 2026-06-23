<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Detail KUBE</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        /* HEADER */
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #000;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header p {
            margin: 4px 0 0 0;
            font-size: 11px;
            color: #000;
        }

        /* TITLE */
        .title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin: 0 0 20px 0;
            border: 2px solid #000;
            padding: 6px;
            letter-spacing: 1px;
        }

        /* SECTION TITLE */
        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid #000;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* BOX */
        .box {
            border: 1px solid #000;
            padding: 12px 15px;
            margin-bottom: 14px;
        }

        /* GRID */
        .grid {
            display: table;
            width: 100%;
        }

        .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }

        .col:last-child {
            padding-right: 0;
            padding-left: 15px;
            border-left: 1px solid #ccc;
        }

        /* ROW */
        .row {
            margin-bottom: 10px;
        }

        .label {
            font-size: 10px;
            color: #555;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            margin-bottom: 2px;
        }

        .value {
            font-size: 12px;
            font-weight: bold;
            color: #000;
        }

        /* BADGE */
        .badge {
            padding: 3px 10px;
            border: 1px solid #000;
            font-size: 10px;
            font-weight: bold;
            display: inline-block;
        }

        .aktif    { background: #000; color: #fff; }
        .nonaktif { background: #fff; color: #000; border: 1px solid #000; }
        .naik     { background: #000; color: #fff; }
        .turun    { background: #fff; color: #000; }
        .tetap    { background: #e5e7eb; color: #000; }

        /* DIVIDER */
        .divider {
            border: none;
            border-top: 1px solid #000;
            margin: 14px 0;
        }

        /* FOOTER */
        .footer {
            margin-top: 40px;
            border-top: 1px solid #000;
            padding-top: 8px;
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            font-size: 10px;
            color: #000;
        }

        .footer-right {
            display: table-cell;
            text-align: right;
            font-size: 10px;
            color: #000;
        }
    </style>
</head>

<body>

{{-- HEADER --}}
<div class="header">
    <h1>Laporan Detail Kelompok Usaha Bersama</h1>
    <p>{{ date('d F Y') }}</p>
</div>
{{-- DATA UTAMA --}}
<div class="box">
    <div class="section-title">Informasi Umum</div>

    <div class="grid">
        <div class="col">

            <div class="row">
                <div class="label">Nama KUBE</div>
                <div class="value">{{ $data->nama_kube }}</div>
            </div>

            <div class="row">
                <div class="label">Kecamatan</div>
                <div class="value">{{ $data->nama_kecamatan }}</div>
            </div>

            <div class="row">
                <div class="label">Desa / Kelurahan</div>
                <div class="value">{{ $data->nama_desa_kelurahan ?? '-' }}</div>
            </div>

            <div class="row">
                <div class="label">Kategori</div>
                <div class="value">{{ $data->nama_kategori ?? '-' }}</div>
            </div>

        </div>

        <div class="col">

            <div class="row">
                <div class="label">Status</div>
                <div class="value">
                    @if($data->status == 'Aktif')
                        <span class="badge aktif">AKTIF</span>
                    @else
                        <span class="badge nonaktif">TIDAK AKTIF</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="label">Cluster</div>
                <div class="value">{{ $data->nama_cluster }}</div>
            </div>

            <div class="row">
                <div class="label">Tanggal Terbentuk</div>
                <div class="value">{{ $data->tanggal_terbentuk ?? '-' }}</div>
            </div>

            <div class="row">
                <div class="label">Pendamping</div>
                <div class="value">{{ $data->nama_pendamping ?? '-' }}</div>
            </div>

        </div>
    </div>
</div>

{{-- KEUANGAN --}}
<div class="box">
    <div class="section-title">Data Keuangan</div>

    <div class="grid">
        <div class="col">
            <div class="row">
                <div class="label">Total Omset</div>
                <div class="value">Rp {{ number_format($data->total_omset, 0, ',', '.') }}</div>
            </div>
        </div>
        <div class="col">
            <div class="row">
                <div class="label">Laba Bersih</div>
                <div class="value">Rp {{ number_format($data->laba_bersih, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
</div>

{{-- PERKEMBANGAN --}}
<div class="box">
    <div class="section-title">Perkembangan Usaha</div>

    <div class="row">
        <div class="label">Status Perkembangan</div>
        <div class="value" style="margin-top: 4px;">
            @if($data->perkembangan_usaha == 'Meningkat')
                <span class="badge naik">MENINGKAT</span>
            @elseif($data->perkembangan_usaha == 'Menurun')
                <span class="badge turun">MENURUN</span>
            @else
                <span class="badge tetap">TETAP</span>
            @endif
        </div>
    </div>
</div>

{{-- FOOTER --}}
<div class="footer">
    <div class="footer-left">Sistem Informasi KUBE</div>
    <div class="footer-right">
        Dicetak otomatis oleh sistem &nbsp;|&nbsp; {{ date('d-m-Y H:i') }}
    </div>
</div>

</body>
</html>