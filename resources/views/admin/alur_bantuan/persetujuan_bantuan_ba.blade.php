<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Berita Acara KUBE</title>
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

        .text-right {
            text-align: right;
        }

        .mb-1 {
            margin-bottom: 8px;
        }

        .mb-2 {
            margin-bottom: 12px;
        }

        .mb-3 {
            margin-bottom: 18px;
        }

        .mb-4 {
            margin-bottom: 24px;
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
            padding: 8px;
            vertical-align: top;
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

        .ttd-space {
            height: 80px;
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
            <th width="35%">Nama KUBE</th>
            <td>{{ $pengajuan->kube->nama_kube ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jenis Bantuan</th>
            <td>{{ $pengajuan->jenisBantuan->jenis_bantuan ?? '-' }}</td>
        </tr>
        <tr>
            <th>Jumlah Bantuan</th>
            <td>Rp {{ number_format($pengajuan->jumlah_bantuan ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Tanggal Pengajuan</th>
            <td>{{ $tanggalPengajuan ? $tanggalPengajuan->translatedFormat('d F Y') : '-' }}</td>
        </tr>
        <tr>
            <th>Status Pengajuan</th>
            <td>{{ ucfirst($pengajuan->status_pengajuan ?? '-') }}</td>
        </tr>
        <tr>
            <th>Disetujui Oleh</th>
            <td>{{ $namaPenandatangan }}</td>
        </tr>
        <tr>
            <th>Jabatan</th>
            <td>{{ $jabatanPenandatangan }}</td>
        </tr>
    </table>

    <div class="mb-3" style="text-align: justify;">
        Berdasarkan hasil verifikasi dan pemeriksaan administrasi terhadap pengajuan bantuan KUBE tersebut,
        maka pengajuan ini dinyatakan <strong>{{ strtoupper($pengajuan->status_pengajuan) }}</strong>
        untuk ditindaklanjuti sesuai dengan ketentuan yang berlaku pada Dinas Sosial Kabupaten Cilacap.
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

                <div style="margin-top: 15px;">
                    
                </div>

                <div style="margin-top: 80px;">
                    <strong><u>......................................</u></strong>
                </div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td class="ttd-space"></td>
        </tr>
    </table>

</body>

</html>