<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembagian Pendamping</title>
    <style>
        /* Pengaturan Dasar */
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 12px; 
            color: #374151; /* text-gray-700 */
            line-height: 1.5;
        }

        /* Pengaturan Header Dokumen */
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .header h2 {
            margin: 0;
            padding: 0;
            color: #111827; /* text-gray-900 */
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .header p {
            margin: 5px 0 0 0;
            color: #4b5563; /* text-gray-600 */
            font-size: 13px;
        }

        /* Pengaturan Tabel */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }

        /* Pengaturan Header Tabel */
        th { 
            background-color: #2563eb; /* bg-blue-600 */
            color: #ffffff; 
            text-align: center; 
            padding: 10px 8px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #1d4ed8; /* bg-blue-700 */
        }

        /* Pengaturan Sel Tabel */
        td { 
            border: 1px solid #d1d5db; /* border-gray-300 */
            padding: 8px; 
            text-align: left; 
            vertical-align: middle;
        }

        /* Baris Selang-seling (Zebra) */
        tbody tr:nth-child(even) {
            background-color: #f9fafb; /* bg-gray-50 */
        }

        /* Kelas Bantuan (Helper) */
        .text-center { 
            text-align: center !important; 
        }
        .text-gray {
            color: #9ca3af; /* text-gray-400 */
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
                <th width="5%">No.</th>
                <th width="25%">Nama KUBE</th>
                <th width="25%">Nama Pendamping</th>
                <th width="15%">Tgl Mulai</th>
                <th width="15%">Tgl Selesai</th>
                <th width="15%">Status</th>
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
                <td colspan="6" class="text-center text-gray">Tidak ada data pembagian pendamping.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>