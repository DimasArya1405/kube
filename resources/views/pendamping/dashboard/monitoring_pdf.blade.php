<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monitoring Bantuan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px;}
        th, td { border: 1px solid #000; padding: 5px; text-align: center;}
        th { background: #eee; }
    </style>
</head>
<body>

<h2 style="text-align:center;">Laporan Monitoring Bantuan</h2>

<table>
<thead>
<tr>
    <th>No</th>
    <th>Jenis</th>
    <th>KUBE</th>
    <th>Pendamping</th>
    <th>Tanggal</th>
    <th>Kesesuaian</th>
    <th>Catatan</th>
</tr>
</thead>

<tbody>
@foreach($monitoring as $i => $item)
<tr>
    <td>{{ $i+1 }}</td>
    <td>{{ $item->jenisBantuan->jenis_bantuan ?? '-' }}</td>
    <td>{{ $item->kube->nama_kube ?? '-' }}</td>
    <td>{{ $item->pendamping->nama_pendamping ?? '-' }}</td>
    <td>{{ date('d-m-Y', strtotime($item->tanggal_monitoring)) }}</td>
    <td>{{ $item->kesesuaian }}</td>
    <td>{{ $item->catatan }}</td>
</tr>
@endforeach
</tbody>

</table>

</body>
</html>