<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Bimbingan KUBE</title>

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
            text-align: center;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>Laporan Bimbingan KUBE</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>KUBE</th>
                <th>Pendamping</th>
                <th>Jenis</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach($datas as $index => $d)
            <tr>
                <td>{{ $index + 1 }}</td>

                <td>
                    {{ \Carbon\Carbon::parse($d->tanggal_bimbingan)->format('d/m/Y') }}
                </td>

                <td>
                    {{ optional($d->kube)->nama_kube }}
                </td>

                <td>
                    {{ $d->id_pendamping == 1 ? 'Siti Aryani' : '-' }}
                </td>

                <td>
                    {{ $d->jenis_bimbingan }}
                </td>

                <td>
                    {{ $d->status_bimbingan }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>