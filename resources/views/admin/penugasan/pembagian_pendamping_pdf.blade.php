<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembagian Pendamping</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
            text-align: center;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Data Pembagian Pendamping</h2>
        <p>Laporan Penugasan KUBE</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Nama KUBE</th>
                <th>Nama Pendamping</th>
                <th>Tgl Mulai</th>
                <th>Tgl Selesai</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembagians as $index => $p)
            <tr>
                <td class="text-center">{{ $index + 1 }}.</td>
                <td>{{ $p->kube->nama_kube ?? 'KUBE Dihapus' }}</td>
                <td>{{ $p->pendamping->nama_pendamping ?? 'Pendamping Dihapus' }}</td>
                <td class="text-center">{{ $p->tgl_pembagian ? \Carbon\Carbon::parse($p->tgl_pembagian)->format('d M Y') : '-' }}</td>
                <td class="text-center">{{ $p->tgl_selesai ? \Carbon\Carbon::parse($p->tgl_selesai)->format('d M Y') : '-' }}</td>
                <td class="text-center">{{ $p->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Tidak ada data pembagian pendamping.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>