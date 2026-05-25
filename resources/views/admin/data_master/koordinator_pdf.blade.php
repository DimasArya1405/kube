<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        h2 { text-align: center; margin-bottom: 4px; }
        p.sub { text-align: center; color: #666; margin-bottom: 16px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #2563eb; color: white; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; }
        th { text-align: center; }
        td.center { text-align: center; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .aktif    { color: #15803d; font-weight: bold; }
        .nonaktif { color: #dc2626; font-weight: bold; }
        .summary  { margin-top: 14px; font-size: 11px; font-weight: bold; color: #333; }
        .summary span.a { color: #15803d; }
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
                <th style="width:30px">No</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Jenis Kelamin</th>
                <th>Tanggal Lahir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($koordinator as $i => $item)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>{{ $item->user->nama ?? '-' }}</td>
                <td>{{ $item->user->nik ?? '-' }}</td>
                <td>{{ $item->user->no_hp ?? '-' }}</td>
                <td>{{ $item->user->alamat ?? '-' }}</td>
                <td class="center">
                    @if($item->jenis_kelamin === 'L') Laki-laki
                    @elseif($item->jenis_kelamin === 'P') Perempuan
                    @else -
                    @endif
                </td>
                <td class="center">
                    {{ $item->tanggal_lahir ? \Carbon\Carbon::parse($item->tanggal_lahir)->format('d-m-Y') : '-' }}
                </td>
                <td class="center {{ $item->status === 'aktif' ? 'aktif' : 'nonaktif' }}">
                    {{ ucfirst($item->status) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;padding:16px;color:#999">Belum ada data koordinator.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @php
        $aktif    = $koordinator->where('status', 'aktif')->count();
        $nonAktif = $koordinator->where('status', 'non-aktif')->count();
        $total    = $koordinator->count();
    @endphp

    <p class="summary">
        Aktif: <span class="a">{{ $aktif }}</span>
        &nbsp;&nbsp;&nbsp;
        Non-Aktif: <span class="n">{{ $nonAktif }}</span>
        &nbsp;&nbsp;&nbsp;
        Total: {{ $total }}
    </p>
</body>
</html>