<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Mitra</title>
    <style>
        /* Menggunakan font Arial, ukuran 9px agar muat banyak kolom */
        body { font-family: Arial, sans-serif; font-size: 9px; margin: 0; padding: 10px; }
        .header { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 4px; word-wrap: break-word; vertical-align: middle; }
        th { background-color: #f2f2f2; text-transform: uppercase; font-size: 8px; text-align: center; }
        
        .footer-total { background-color: #f9f9f9; font-weight: bold; font-size: 10px; }
        .status-aktif { color: green; font-weight: bold; }
        .status-non { color: red; font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <div class="header">
        <h2>LAPORAN DATA MITRA SISTEM KUBE</h2>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d/m/Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="20px">No</th>
                <th>Nama Mitra</th>
                <th width="80px">Alamat</th>
                <th width="70px">Email Perusahaan</th>
                <th width="60px">Telp Perusahaan</th>
                <th width="60px">Nama PIC</th>
                <th width="60px">Telp PIC</th>
                <th width="50px">Tgl MOU</th>
                <th width="30px">Masa</th>
                <th width="50px">Status</th>
                <th width="40px">Jml Kolab</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $totalKolab = 0; 
                $aktif = 0;
                $nonAktif = 0;
            @endphp

            @foreach($mitra as $key => $item)
            @php
                $totalKolab += $item->bantuan_kolaborasi->count();
                if($item->status == 'Aktif') { $aktif++; } else { $nonAktif++; }
            @endphp
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td><strong>{{ $item->nama_mitra }}</strong></td>
                <td>{{ $item->alamat }}</td>
                <td>{{ $item->email }}</td>
                <td class="text-center">{{ $item->no_telp }}</td>
                <td>{{ $item->nama_pic }}</td>
                <td class="text-center">{{ $item->telp_pic }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($item->tgl_mou)->format('d/m/y') }}</td>
                <td class="text-center">{{ $item->masa_berlaku }} Th</td>
                <td class="text-center">
                    @if($item->status == 'Aktif')
                        <span class="status-aktif">AKTIF</span>
                    @else
                        <span class="status-non">NON</span>
                    @endif
                </td>
                <td class="text-center">{{ $item->bantuan_kolaborasi->count() }}</td>
            </tr>
            @endforeach
        </tbody>
        
        <tfoot>
            <tr class="footer-total">
                <td colspan="9" class="text-right">RINGKASAN STATUS:</td>
                <td colspan="2" class="text-center">
                    Aktif: {{ $aktif }} | Non: {{ $nonAktif }}
                </td>
            </tr>
            <tr class="footer-total">
                <td colspan="9" class="text-right">TOTAL KESELURUHAN (MITRA & KOLABORASI):</td>
                <td class="text-center">{{ $mitra->count() }} Mitra</td>
                <td class="text-center">{{ $totalKolab }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 15px; font-size: 8px; font-style: italic;">
        * Laporan ini dihasilkan otomatis oleh Sistem KUBE.
    </div>

</body>
</html>