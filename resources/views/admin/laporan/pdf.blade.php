<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Detail KUBE</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #4f46e5;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #4f46e5;
        }

        .header p {
            margin: 2px;
            font-size: 11px;
        }

        .title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            margin: 15px 0;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background: #f9fafb;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 8px;
            color: #4f46e5;
        }

        .grid {
            display: table;
            width: 100%;
        }

        .col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 10px;
        }

        .row {
            margin-bottom: 10px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .value {
            margin-top: 2px;
        }

        .status {
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        .aktif {
            background: #d1fae5;
            color: #065f46;
        }

        .nonaktif {
            background: #fee2e2;
            color: #7f1d1d;
        }

        .badge {
            padding: 3px 8px;
            border-radius: 5px;
            font-size: 11px;
            font-weight: bold;
            display: inline-block;
        }

        .naik { background:#d1fae5; color:#065f46; }
        .turun { background:#fee2e2; color:#7f1d1d; }
        .tetap { background:#e5e7eb; color:#374151; }

        .box {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            margin-top: 15px;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>

<body>

{{-- HEADER --}}
<div class="header">
    <h1>Laporan Detail Kelompok Usaha Bersama</h1>
    <p>{{ date('d F Y') }}</p>
</div>

<div class="title">
    DETAIL DATA KUBE
</div>

{{-- DATA UTAMA --}}
<div class="card">

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
                <div class="label">Desa</div>
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
                    @if($data->status == 'aktif')
                        <span class="status aktif">AKTIF</span>
                    @else
                        <span class="status nonaktif">TIDAK AKTIF</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="label">Cluster</div>
                <div class="value">{{ $data->nama_cluster }}</div>
            </div>

            <div class="row">
                <div class="label">Tanggal Terbentuk</div>
                <div class="value">
                    {{ $data->tanggal_terbentuk ?? '-' }}
                </div>
            </div>

            <div class="row">
                <div class="label">Pendamping</div>
                <div class="value">
                    {{ $data->nama_pendamping ?? '-' }}
                </div>
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
                <div class="value">
                    Rp {{ number_format($data->total_omset,0,',','.') }}
                </div>
            </div>
        </div>

        <div class="col">
            <div class="row">
                <div class="label">Laba Bersih</div>
                <div class="value">
                    Rp {{ number_format($data->laba_bersih,0,',','.') }}
                </div>
            </div>
        </div>

    </div>
</div>

{{-- PERKEMBANGAN --}}
<div class="box">
    <div class="section-title">Perkembangan Usaha</div>

    <div class="row">
        <div class="label">Status Perkembangan</div>
        <div class="value">

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
    Dicetak otomatis oleh sistem <br>
    {{ date('d-m-Y H:i') }}
</div>

</body>
</html>