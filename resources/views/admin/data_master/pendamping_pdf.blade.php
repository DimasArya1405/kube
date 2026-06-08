<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Pendamping</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #1f2937;
            background: #fff;
        }

        /* ===== HEADER ===== */
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #2563eb;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .header p {
            font-size: 10px;
            color: #6b7280;
            margin-top: 3px;
        }

        /* ===== SUMMARY BADGES ===== */
        .summary {
            display: table;
            margin-bottom: 16px;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            width: 50%;
            padding: 8px 12px;
            border-radius: 6px;
            text-align: center;
        }

        .summary-aktif {
            background-color: #f97316;
            color: #fff;
            margin-right: 6px;
        }

        .summary-nonaktif {
            background-color: #86efac;
            color: #fff;
        }

        .summary-item .label {
            font-size: 9px;
            font-weight: bold;
        }

        .summary-item .value {
            font-size: 22px;
            font-weight: bold;
            line-height: 1.2;
        }

        /* ===== TABLE ===== */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background-color: #2563eb;
            color: #ffffff;
        }

        thead th {
            padding: 8px 10px;
            text-align: left;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        thead th.center {
            text-align: center;
        }

        tbody tr:nth-child(even) {
            background-color: #f0f4ff;
        }

        tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }

        tbody td {
            padding: 7px 10px;
            font-size: 10px;
            vertical-align: middle;
            border-bottom: 1px solid #e5e7eb;
        }

        tbody td.center {
            text-align: center;
        }

        /* ===== FOTO ===== */
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

        /* ===== STATUS BADGE ===== */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 99px;
            font-size: 9px;
            font-weight: bold;
        }

        .badge-aktif {
            background-color: #dcfce7;
            color: #166534;
        }

        .badge-nonaktif {
            background-color: #fee2e2;
            color: #991b1b;
        }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 20px;
            font-size: 9px;
            color: #9ca3af;
            text-align: right;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>Data Pendamping KUBE</h1>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    {{-- SUMMARY --}}
    <div class="summary">
        <div class="summary-item summary-aktif">
            <div class="label">Pendamping Aktif</div>
            <div class="value">{{ $pendamping->where('status','Aktif')->count() }}</div>
        </div>
        <div class="summary-item summary-nonaktif">
            <div class="label">Pendamping Non-Aktif</div>
            <div class="value">{{ $pendamping->where('status','Tidak Aktif')->count() }}</div>
        </div>
    </div>

    {{-- TABLE --}}
    <table>
        <thead>
            <tr>
                <th style="width:30px;" class="center">No</th>
                <th style="width:50px;" class="center">Foto</th>
                <th>Nama Pendamping</th>
                <th>NIK</th>
                <th>Kecamatan</th>
                <th>No HP</th>
                <th class="center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendamping as $index => $item)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td class="center">
                    @if($item->foto)
                        <img src="{{ public_path('storage/foto_pendamping/' . $item->foto) }}"
                             class="foto-img">
                    @else
                        <span class="foto-placeholder"></span>
                    @endif
                </td>
                <td>{{ $item->nama_pendamping }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
                <td>{{ $item->no_hp }}</td>
                <td class="center">
                    <span class="badge {{ $item->status == 'Aktif' ? 'badge-aktif' : 'badge-nonaktif' }}">
                        {{ $item->status }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="center" style="padding: 20px; color: #9ca3af;">
                    Tidak ada data pendamping.
                </td>
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