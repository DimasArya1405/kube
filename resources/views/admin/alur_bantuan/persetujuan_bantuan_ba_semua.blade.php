<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Berita Acara Semua Bantuan KUBE</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            margin: 30px;
        }

        .text-center {
            text-align: center;
        }

        .mb-3 {
            margin-bottom: 18px;
        }

        .title {
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 13px;
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 7px;
            vertical-align: top;
        }

        .table th {
            background: #f2f2f2;
        }

        .no-border td {
            border: none;
            padding: 3px 0;
        }

        .signature {
            width: 100%;
            margin-top: 30px;
        }

        .signature td {
            width: 50%;
            vertical-align: top;
        }
    </style>
</head>

<body>
    <div class="text-center mb-3">
        <div class="title">Berita Acara Persetujuan Bantuan KUBE</div>
        <div class="subtitle">Dinas Sosial Kabupaten Cilacap</div>
    </div>

    <div class="mb-3">
        Pada hari ini, <strong>{{ $tanggalCetak->translatedFormat('l') }}</strong>,
        tanggal <strong>{{ $tanggalCetak->translatedFormat('d F Y') }}</strong>,
        telah dilakukan proses persetujuan pengajuan bantuan
        <strong>Kelompok Usaha Bersama (KUBE)</strong> dengan rincian sebagai berikut:
    </div>

    <table class="table">
        <tr>
            <th width="30%">Nama KUBE</th>
            <td>{{ $kube->nama_kube ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jumlah Jenis Bantuan Disetujui</th>
            <td>{{ $pengajuan_kube->count() }} jenis bantuan</td>
        </tr>
        <tr>
            <th>Total Bantuan</th>
            <td>{{ number_format($pengajuan_kube->sum('jumlah_bantuan'), 0, ',', '.') }}</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th width="6%">No</th>
                <th>Jenis Bantuan</th>
                <th width="20%">Jumlah Bantuan</th>
                <th>Tujuan Pengajuan</th>
                <th width="18%">Tanggal Pengajuan</th>
                <th width="14%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengajuan_kube as $i => $row)
            <tr>
                <td class="text-center">{{ $i + 1 }}</td>
                <td>{{ $row->jenisBantuan->jenis_bantuan ?? '-' }}</td>
                <td>{{ number_format($row->jumlah_bantuan ?? 0, 0, ',', '.') }}</td>
                <td>{{ $row->tujuan_pengajuan ?? '-' }}</td>
                <td>
                    {{ $row->tanggal_pengajuan
                        ? \Carbon\Carbon::parse($row->tanggal_pengajuan)->locale('id')->translatedFormat('d F Y')
                        : '-' }}
                </td>
                <td>{{ ucfirst($row->status_pengajuan ?? '-') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mb-3" style="text-align: justify;">
        Berdasarkan hasil verifikasi dan pemeriksaan administrasi terhadap pengajuan bantuan KUBE tersebut,
        maka seluruh jenis bantuan yang tercantum dalam tabel di atas dinyatakan
        <strong>DISETUJUI</strong> untuk ditindaklanjuti sesuai dengan ketentuan yang berlaku pada
        Dinas Sosial Kabupaten Cilacap.
    </div>

    <div class="mb-3" style="text-align: justify;">
        Demikian berita acara ini dibuat dengan sebenarnya untuk digunakan sebagaimana mestinya.
    </div>

    <table class="signature no-border">
        <tr>
            <td></td>
            <td class="text-center">
                Cilacap, {{ $tanggalCetak->translatedFormat('d F Y') }}<br>
                Menyetujui,<br>
                {{ $jabatanPenandatangan }}

                <div style="margin-top: 95px;">
                    <strong><u>......................................</u></strong>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
