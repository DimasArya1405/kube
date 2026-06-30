<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan KUBE</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        /* HEADER */
        .header {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header-table {
            width: 100%;
        }

        .logo {
            width: 70px;
        }

        .title-header {
            text-align: center;
        }

        .title-header h1 {
            margin: 0;
            font-size: 18px;
            color: #000;
        }

        .title-header p {
            margin: 2px;
            font-size: 11px;
        }

        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0 15px 0;
        }

        /* FILTER */
        .filter-box {
            border: 1px solid #000;
            border-left: 4px solid #000;
            padding: 10px;
            margin-bottom: 15px;
            background: #f9fafb;
        }

        /* TABLE */
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table.data th {
            background: #000;
            color: white;
            font-size: 11px;
            padding: 7px;
        }

        table.data td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }

        table.data tr:nth-child(even) {
            background: #f9fafb;
        }

        /* BADGE */
        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }

        .aktif    { background: #d1fae5; color: #065f46; }
        .nonaktif { background: #fee2e2; color: #7f1d1d; }
        .naik     { background: #d1fae5; color: #065f46; }
        .turun    { background: #fee2e2; color: #7f1d1d; }
        .tetap    { background: #e5e7eb; color: #374151; }

        /* FOOTER */
        .footer {
            margin-top: 25px;
            text-align: right;
            font-size: 10px;
        }
    </style>
</head>

<body>

<!-- HEADER -->


<div class="title">
    DATA KUBE BERDASARKAN PENGAJUAN YANG DISETUJUI
</div>

<!-- FILTER -->
<div class="filter-box">
    Kecamatan : {{ $filterKecamatan }} <br>
    Tahun : {{ $filterTahun }} <br>
    Cluster : {{ $filterCluster }}
</div>

<!-- TABLE -->
<table class="data">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama KUBE</th>
            <th>Kecamatan</th>
            <th>Kategori</th>
            <th>Cluster</th>
            <th>Perkembangan</th>
            <th>Omset</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $d)
        <tr>
            <td align="center">{{ $loop->iteration }}</td>
            <td>{{ $d->nama_kube }}</td>
            <td>{{ $d->nama_kecamatan }}</td>
            <td>{{ $d->nama_kategori }}</td>
            <td>{{ $d->nama_cluster }}</td>

            <td align="center">
                @if($d->perkembangan_usaha == 'Meningkat')
                    <span class="badge naik">Meningkat</span>
                @elseif($d->perkembangan_usaha == 'Menurun')
                    <span class="badge turun">Menurun</span>
                @else
                    <span class="badge tetap">Tetap</span>
                @endif
            </td>

            <td align="right">
                Rp {{ number_format($d->total_omset, 0, ',', '.') }}
            </td>

            <td align="center">
                @if($d->status == 'Aktif')
                    <span class="badge aktif">Aktif</span>
                @else
                    <span class="badge nonaktif">Tidak Aktif</span>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<!-- FOOTER -->
<div class="footer">
    Dicetak otomatis oleh sistem <br>
    {{ date('d-m-Y H:i') }}
</div>

</body>
</html>