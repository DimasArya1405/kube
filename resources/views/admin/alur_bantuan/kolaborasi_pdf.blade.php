<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kolaborasi Bantuan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 10px; margin: 0; padding: 10px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 6px; word-wrap: break-word; vertical-align: top; }
        th { background-color: #f2f2f2; text-transform: uppercase; font-size: 9px; }
        .text-center { text-align: center; }
        .footer-total { background-color: #eee; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KOLABORASI BANTUAN SISTEM KUBE</h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="25px">No</th>
                <th>Nama Mitra</th>
                <th>Kelompok Kube</th>
                <th>Nama Bantuan</th>
                <th width="60px">Jenis</th>
                <th width="65px">Tgl Pelaksanaan</th>
                <th>Deskripsi</th>
                <th width="60px">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $key => $item)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $item->mitra->nama_mitra ?? '-' }}</td>
                <td>{{ $item->kube->nama_kube ?? '-' }}</td>
                <td>{{ $item->nama_bantuan }}</td>
                <td class="text-center">{{ $item->jenis_bantuan }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_pelaksanaan)->format('d/m/Y') }}</td>
                <td>{{ $item->deskripsi }}</td>
                <td class="text-center">{{ $item->status }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="footer-total">
                <td colspan="7" style="text-align: right;">TOTAL DATA KOLABORASI:</td>
                <td class="text-center">{{ $data->count() }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>