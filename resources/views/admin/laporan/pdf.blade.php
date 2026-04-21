<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan KUBE</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
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
            font-size: 12px;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            background: #f9fafb;
        }

        .row {
            margin-bottom: 12px;
        }

        .label {
            font-weight: bold;
            color: #555;
        }

        .value {
            margin-top: 3px;
        }

        .grid {
            display: table;
            width: 100%;
        }

        .col {
            display: table-cell;
            width: 50%;
            padding-right: 10px;
            vertical-align: top;
        }

        .footer {
            margin-top: 40px;
            text-align: right;
            font-size: 12px;
        }

        .status {
            padding: 4px 8px;
            border-radius: 5px;
            display: inline-block;
            font-size: 12px;
            font-weight: bold;
        }

        .aktif {
            background: #d1fae5;
            color: #065f46;
        }

        .nonaktif {
            background: #fee2e2;
            color: #7f1d1d;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>SISTEM INFORMASI KUBE</h1>
        <p>Laporan Detail Kelompok Usaha Bersama</p>
        <p>{{ date('d F Y') }}</p>
    </div>

    <div class="title">
        DETAIL DATA KUBE
    </div>

    {{-- CARD --}}
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
                    <div class="label">Cluster</div>
                    <div class="value">{{ $data->nama_cluster }}</div>
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
                    <div class="label">Total Omset</div>
                    <div class="value">
                        Rp {{ number_format($data->total_omset,0,',','.') }}
                    </div>
                </div>

                <div class="row">
                    <div class="label">Laba Bersih</div>
                    <div class="value">
                        Rp {{ number_format($data->laba_bersih,0,',','.') }}
                    </div>
                </div>
            </div>

        </div>

    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Dicetak oleh sistem pada {{ date('d-m-Y H:i') }}
    </div>

</body>
</html>