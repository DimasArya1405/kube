<!DOCTYPE html>
<html>
<head>
    <title>Laporan Lengkap KUBE</title>
    <style>
        /* Pengaturan Dasar */
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 12px; 
            color: #374151; /* text-gray-700 */
            line-height: 1.5;
        }

        /* Pengaturan Judul Halaman */
        .header-title { 
            text-align: center; 
            margin-bottom: 20px; 
            color: #111827; /* text-gray-900 */
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Pengaturan Judul Sub-Seksi */
        .section-title {
            font-weight: bold;
            background-color: #e5e7eb; /* bg-gray-200 */
            color: #1f2937; /* text-gray-800 */
            padding: 6px 10px;
            margin-top: 15px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-left: 4px solid #2563eb; /* bg-blue-600 sebagai aksen seksi */
        }

        /* Pengaturan Tabel */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 25px; 
        }

        /* Pengaturan Header Tabel */
        th { 
            background-color: #2563eb; /* bg-blue-600 */
            color: #ffffff; 
            text-align: left; 
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
            vertical-align: middle;
        }

        /* Baris Selang-seling (Zebra) untuk Tabel Data Anggota */
        tbody tr:nth-child(even) {
            background-color: #f9fafb; /* bg-gray-50 */
        }

        /* Pengaturan Pemisah Halaman Cetak */
        .page-break {
            page-break-after: always;
        }

        /* Kelas Bantuan (Helper) */
        .text-center { 
            text-align: center; 
        }
        .text-gray {
            color: #9ca3af; /* text-gray-400 */
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
                    <th class="text-center" width="5%">No</th>
                    <th>Nama Anggota</th>
                    <th class="text-center" width="15%">NIK</th>
                    <th class="text-center" width="15%">Jabatan</th>
                    <th class="text-center" width="15%">No. HP</th>
                    <th width="35%">Alamat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($k->anggota as $index => $anggota)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $anggota->nama_anggota }}</td>
                    <td class="text-center">{{ $anggota->nik }}</td>
                    <td class="text-center">{{ $anggota->jabatan }}</td>
                    <td class="text-center">{{ $anggota->no_hp }}</td>
                    <td>{{ $anggota->alamat }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-gray">Belum ada data anggota.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endforeach

</body>
</html>