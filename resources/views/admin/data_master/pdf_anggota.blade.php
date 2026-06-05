<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Anggota KUBE</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; text-align: center; }
        h2 { text-align: center; margin-bottom: 5px; }
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
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $a->nik }}</td>
                <td>{{ $a->nama_anggota }}</td>
                <td>{{ $a->kube->nama_kube ?? '-' }}</td>
                <td style="text-align: center;">{{ $a->jabatan }}</td>
                <td>{{ $a->no_hp }}</td>
                <td>{{ $a->alamat }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>