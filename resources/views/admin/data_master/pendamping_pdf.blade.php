<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pendamping</title>
    <style>
        /* Pengaturan Dasar (Selaras dengan KUBE) */
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 12px; 
            color: #374151; 
            line-height: 1.5;
            margin: 0; 
            padding: 0;
        }

        /* Pengaturan Judul Halaman */
        .header-title { 
            text-align: center; 
            margin-bottom: 5px; 
            color: #111827; 
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-subtitle {
            text-align: center;
            color: #6b7280;
            font-size: 10px;
            margin-bottom: 20px;
        }

        /* Pengaturan Judul Sub-Seksi */
        .section-title {
            font-weight: bold;
            background-color: #e5e7eb; 
            color: #1f2937; 
            padding: 6px 10px;
            margin-top: 15px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-left: 4px solid #2563eb; 
            font-size: 12px;
        }

        /* Pengaturan Tabel */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 25px; 
        }

        /* Pengaturan Header Tabel */
        th { 
            background-color: #2563eb; 
            color: #ffffff; 
            text-align: left; 
            padding: 10px 8px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: 1px solid #1d4ed8; 
        }

        /* Pengaturan Sel Tabel */
        td { 
            border: 1px solid #d1d5db; 
            padding: 8px; 
            vertical-align: middle;
        }

        /* Baris Selang-seling (Zebra) */
        tbody tr:nth-child(even) {
            background-color: #f9fafb; 
        }

        /* Kelas Bantuan (Helper) */
        .text-center { text-align: center; }
        .text-gray { color: #9ca3af; }

        /* ===== PENGATURAN FOTO ===== */
        .foto-img {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 1px solid #d1d5db;
        }

        .foto-placeholder {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #e5e7eb;
            display: inline-block;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 20px;
            font-size: 10px;
            color: #9ca3af;
            text-align: right;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header-title">LAPORAN DATA PENDAMPING KUBE</div>
    <div class="header-subtitle">Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</div>

    {{-- SUMMARY (Diubah strukturnya menjadi tabel agar selaras dengan tema KUBE) --}}
    <div class="section-title">A. RINGKASAN DATA</div>
    <table>
        <tr>
            <th width="25%">Pendamping Aktif</th>
            <td width="25%" class="text-center" style="font-weight: bold; font-size: 14px; color: #166534;">
                {{ $pendamping->where('status','Aktif')->count() }}
            </td>
            <th width="25%">Pendamping Non-Aktif</th>
            <td width="25%" class="text-center" style="font-weight: bold; font-size: 14px; color: #991b1b;">
                {{ $pendamping->where('status','Tidak Aktif')->count() }}
            </td>
        </tr>
    </table>

    {{-- TABEL UTAMA --}}
    <div class="section-title">B. DAFTAR PENDAMPING</div>
    <table>
        <thead>
            <tr>
                <th style="width:5%;" class="text-center">No</th>
                <th style="width:8%;" class="text-center">Foto</th>
                <th style="width:22%;">Nama Pendamping</th>
                <th style="width:15%;" class="text-center">NIK</th>
                <th style="width:20%;">Kecamatan</th>
                <th style="width:15%;" class="text-center">No HP</th>
                <th style="width:15%;" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendamping as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="text-center">
                    @if($item->foto)
                        <img src="{{ public_path('storage/foto_pendamping/' . $item->foto) }}" class="foto-img">
                    @else
                        <span class="foto-placeholder"></span>
                    @endif
                </td>
                <td>{{ $item->nama_pendamping }}</td>
                <td class="text-center">{{ $item->nik }}</td>
                <td>{{ $item->user?->kecamatan?->nama_kecamatan ?? '-' }}</td>
                <td class="text-center">{{ $item->no_hp }}</td>
                <td class="text-center">{{ $item->status }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-gray">Belum ada data pendamping.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER --}}
    <div class="footer">
        Total: {{ $pendamping->count() }} pendamping &nbsp;|&nbsp; KUBE &copy; {{ date('Y') }}
    </div>

</body>
</html>