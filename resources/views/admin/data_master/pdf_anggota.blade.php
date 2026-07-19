<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Anggota KUBE</title>
    <style>
        /* Pengaturan Dasar */
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 12px; 
            color: #374151; /* text-gray-700 */
            line-height: 1.5;
        }

        /* Pengaturan Judul */
        h2 { 
            text-align: center; 
            margin-bottom: 20px; 
            color: #111827; /* text-gray-900 */
            font-size: 18px;
            text-transform: uppercase;
            letter-spacing: 1px;
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
            border: 1px solid #1d4ed8; /* bg-blue-700 untuk border header */
        }

        /* Pengaturan Sel Tabel */
        td { 
            border: 1px solid #d1d5db; /* border-gray-300 */
            padding: 8px; 
            text-align: left; 
            vertical-align: middle;
        }

        /* Baris Selang-seling (Zebra) untuk kemudahan membaca */
        tbody tr:nth-child(even) {
            background-color: #f9fafb; /* bg-gray-50 */
        }

        /* Kelas Bantuan (Helper) */
        .text-center { 
            text-align: center; 
        }
    </style>
</head>
<body>
    <h2>LAPORAN DATA ANGGOTA KUBE</h2>
    
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">NIK</th>
                <th width="20%">Nama Anggota</th>
                <th width="15%">Asal KUBE</th>
                <th width="10%">Jabatan</th>
                <th width="15%">No. HP</th>
                <th width="20%">Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach($anggotas as $index => $a)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">{{ $a->nik }}</td>
                <td>{{ $a->nama_anggota }}</td>
                <td>{{ $a->kube->nama_kube ?? '-' }}</td>
                <td class="text-center">{{ $a->jabatan }}</td>
                <td class="text-center">{{ $a->no_hp }}</td>
                <td>{{ $a->alamat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>