<!DOCTYPE html>
<html>
<head>
    <title>Data Kategori KUBE</title>
    <style>
        body{
            font-family: sans-serif;
        }

        h2{
            text-align: center;
            margin-bottom: 20px;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td{
            border: 1px solid black;
        }

        th, td{
            padding: 8px;
            text-align: left;
        }

        th{
            background: #f2f2f2;
        }
    </style>
</head>
<body>

    <h2>Data Kategori KUBE</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
            </tr>
        </thead>

        <tbody>
            @foreach($kategori as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_kategori }}</td>
                <td>{{ $item->deskripsi }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>