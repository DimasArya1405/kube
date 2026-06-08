<!DOCTYPE html>
<html>

<head>
    <title>Laporan Lengkap KUBE</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .page-break {
            page-break-after: always;
        }

        /* Bikin halaman baru tiap ganti KUBE */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section-title {
            font-weight: bold;
            background-color: #e2e8f0;
            padding: 5px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    @foreach($kubes as $k)
    <div class="{{ !$loop->last ? 'page-break' : '' }}">
        <div class="header-title">PROFIL KELOMPOK USAHA BERSAMA (KUBE)</div>

        <div class="section-title">A. INFORMASI DASAR & PENGELOLA</div>
        <table>
            <tr>
                <th width="20%">Nama KUBE</th>
                <td width="30%">{{ $k->nama_kube }}</td>
                <th width="20%">Koordinator</th>
                <td width="30%">{{ $k->pembagianPendamping->pembagianKoordinator->koordinator->nama_koor ?? 'Belum Ada' }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $k->clusterUsaha->kategori->nama_kategori ?? '-' }}</td>
                <th>Pendamping</th>
                <td>{{ $k->pembagianPendamping->pendamping->nama_pendamping ?? 'Belum Ada' }}</td>
            </tr>
            <tr>
                <th>Cluster</th>
                <td>{{ $k->clusterUsaha->nama_cluster ?? '-' }}</td>
                <th>Status</th>
                <td>{{ $k->status }}</td>
            </tr>
            <tr>
                <th>Kecamatan</th>
                <td>{{ $k->desa->kecamatan->nama_kecamatan ?? '-' }}</td>
                <th>Tanggal Dibentuk</th>
                <td>{{ $k->tanggal_terbentuk }}</td>
            </tr>
            <tr>
                <th>Desa/Kelurahan</th>
                <td>{{ $k->desa->nama_desa_kelurahan ?? '-' }}</td>
                <th>Keterangan</th>
                <td>{{ $k->keterangan ?? '-' }}</td>
            </tr>
        </table>

        <div class="section-title">B. DAFTAR ANGGOTA KUBE</div>
        <table>
            <thead>
                <tr>
                    <th style="text-align:center; width:5%;">No</th>
                    <th>Nama Anggota</th>
                    <th style="text-align:center;">NIK</th>
                    <th style="text-align:center;">Jabatan</th>
                    <th style="text-align:center;">No. HP</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($k->anggota as $index => $anggota)
                <tr>
                    <td style="text-align:center;">{{ $index + 1 }}</td>
                    <td>{{ $anggota->nama_anggota }}</td>
                    <td style="text-align:center;">{{ $anggota->nik }}</td>
                    <td style="text-align:center;">{{ $anggota->jabatan }}</td>
                    <td style="text-align:center;">{{ $anggota->no_hp }}</td>
                    <td>{{ $anggota->alamat }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:gray;">Belum ada data anggota.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endforeach

</body>

</html>