<!DOCTYPE html>
<html>

<head>
    <title>Laporan Data Pelatihan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #9091e2;
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .status {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>DAFTAR DATA PELATIHAN KUBE</h2>
        <p>Tanggal Cetak: {{ date('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelatihan</th>
                <th>KUBE</th>
                <th>Pendamping</th>
                <th>Tanggal</th>
                <th>Lokasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pelatihans as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->nama_pelatihan }}</td>
                <td>
                    @if($p->kubes && $p->kubes->count() > 0)
                    {{ $p->kubes->pluck('nama_kube')->join(', ') }}
                    @else
                    -
                    @endif
                </td>
                <td>{{ $p->pendamping->nama_pendamping ?? '-' }}</td>
                <td>{{ $p->tanggal_mulai ? \Carbon\Carbon::parse($p->tanggal_mulai)->format('d/m/Y') : '-' }}</td>
                <td>{{ $p->lokasi }}</td>
                <td>{{ $p->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>