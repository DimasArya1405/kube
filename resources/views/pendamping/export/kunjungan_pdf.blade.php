<!DOCTYPE html>
<html>
<head>
    <title>Data Kunjungan Pendamping</title>

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

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>Data Kunjungan Pendamping</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pendamping</th>
                <th>KUBE</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Tujuan</th>
                <th>Kunjungan Ke</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach($kunjunganPendamping as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->pembagian->pendamping->nama_pendamping ?? '-' }}</td>
                <td>{{ $item->pembagian->kube->nama_kube ?? '-' }}</td>
                <td>{{ $item->tanggal_kunjungan }}</td>
                <td>{{ $item->waktu_kunjungan }}</td>
                <td>{{ $item->tujuan_kunjungan }}</td>
                <td>{{ $item->kunjungan_ke }}</td>
                <td>{{ ucfirst($item->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>