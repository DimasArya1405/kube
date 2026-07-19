<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 9px; color: #333; }
        h2 { text-align: center; margin-bottom: 4px; }
        p.sub { text-align: center; color: #666; margin-bottom: 14px; font-size: 9px; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #2563eb; color: white; }
        th, td { border: 1px solid #ddd; padding: 4px 5px; word-wrap: break-word; }
        th { text-align: center; }
        td.center { text-align: center; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .aktif    { color: #1d4ed8; font-weight: bold; }
        .nonaktif { color: #dc2626; font-weight: bold; }
        .summary  { margin-top: 12px; font-size: 10px; font-weight: bold; color: #333; }
        .summary span.a { color: #1d4ed8; }
        .summary span.n { color: #dc2626; }
    </style>
</head>
<body>
    <h2>Data Koordinator KUBE</h2>
    <p class="sub">
        Daftar koordinator KUBE
        @if($filterStatus) &mdash; Filter Status: <strong>{{ ucfirst($filterStatus) }}</strong> @endif
        &mdash; Dicetak {{ now()->format('d/m/Y H:i') }}
    </p>

    <table>
        <thead>
            <tr>
                <th style="width:20px">No</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Jenis Kelamin</th>
                <th>Tempat, Tanggal Lahir</th>
                <th>No HP</th>
                <th>Email</th>
                <th>Pendidikan</th>
                <th>Kecamatan</th>
                <th>Desa/Kelurahan</th>
                <th>Wilayah</th>
                <th>Alamat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        @forelse($koordinator as $i => $item)
        <tr>
            <td class="center">{{ $i + 1 }}</td>
            <td>{{ $item->nama_koordinator ?? '-' }}</td>
            <td>{{ $item->nik ?? '-' }}</td>
            <td class="center">
                @if($item->jenis_kelamin === 'L') Laki-laki
                @elseif($item->jenis_kelamin === 'P') Perempuan
                @else -
                @endif
            </td>
            <td>
                {{ $item->tempat_lahir ?? '-' }},
                {{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') : '-' }}
            </td>
            <td>{{ $item->no_hp ?? '-' }}</td>
            <td>{{ $item->email ?? '-' }}</td>
            <td>{{ $item->pendidikan_terakhir ?? '-' }}</td>
            <td>{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
            <td>{{ $item->desa->nama_desa_kelurahan ?? '-' }}</td>
            <td>{{ $item->wilayah ?? '-' }}</td>
            <td>{{ $item->alamat ?? '-' }}</td>
            <td class="center {{ $item->status === 'Aktif' ? 'aktif' : 'nonaktif' }}">
                {{ $item->status }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="13" style="text-align:center;padding:16px;color:#999">Belum ada data koordinator.</td>
        </tr>
        @endforelse
    </tbody>
    </table>

    @php
        $aktif    = $koordinator->where('status', 'Aktif')->count();
        $nonAktif = $koordinator->where('status', 'Tidak Aktif')->count();
        $total    = $koordinator->count();
    @endphp

    <p class="summary">
        Aktif: <span class="a">{{ $aktif }}</span>
        &nbsp;&nbsp;&nbsp;
        Tidak Aktif: <span class="n">{{ $nonAktif }}</span>
        &nbsp;&nbsp;&nbsp;
        Total: {{ $total }}
    </p>
</body>
</html>