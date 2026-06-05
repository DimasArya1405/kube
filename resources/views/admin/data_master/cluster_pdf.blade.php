<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<title>Laporan Cluster Usaha</title>

<style>

body{
    font-family:sans-serif;
    font-size:12px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th,td{
    border:1px solid #000;
    padding:6px;
}

th{
    background:#eee;
}

h2{
    text-align:center;
    margin-bottom:20px;
}

</style>

</head>

<body>

<h2>Laporan Data Cluster Usaha</h2>

<table>

<thead>
<tr>
    <th>No</th>
    <th>Nama Cluster</th>
    <th>Kategori</th>
    <th>Deskripsi</th>
    <th>Status</th>
</tr>
</thead>

<tbody>

@foreach($data as $row)

<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $row->nama_cluster }}</td>
    <td>{{ $row->nama_kategori }}</td>
    <td>{{ $row->deskripsi }}</td>
    <td>{{ $row->status }}</td>
</tr>

@endforeach

</tbody>

</table>

</body>
</html>