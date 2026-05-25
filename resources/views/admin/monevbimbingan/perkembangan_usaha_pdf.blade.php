<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Perkembangan Usaha</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; }
        h2 { text-align: center; margin-bottom: 5px; }
        p { text-align: center; margin-top: 0; color: #555; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th { background-color: #3b82f6; color: white; padding: 6px; text-align: left; }
        td { padding: 5px 6px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .badge-meningkat { color: #16a34a; }
        .badge-menurun { color: #dc2626; }
        .badge-tetap { color: #6b7280; }
        .badge-tercapai { color: #16a34a; }
        .badge-belum { color: #d97706; }
    </style>
</head>
<body>
    <h2>Data Perkembangan Usaha KUBE</h2>
    <p>Dicetak pada: {{ now()->format('d M Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama KUBE</th>
                <th>Periode</th>
                <th>Omset</th>
                <th>Pengeluaran</th>
                <th>Laba Bersih</th>
                <th>Tenaga Kerja</th>
                <th>Perkembangan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $item)
            @php
                $namaKube = '-';
                if ($item->laporan && $item->laporan->cluster) {
                    $kube = $item->laporan->cluster->kube->first();
                    if ($kube) $namaKube = $kube->nama_kube;
                }
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $namaKube }}</td>
                <td>{{ $item->laporan->periode_bulan ?? '-' }}/{{ $item->laporan->periode_tahun ?? '-' }}</td>
                <td>Rp {{ number_format($item->omset_pendapatan ?? 0, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->total_pengeluaran ?? 0, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($item->laba_bersih ?? 0, 0, ',', '.') }}</td>
                <td>{{ $item->jumlah_tenaga_kerja ?? '-' }}</td>
                <td class="badge-{{ strtolower($item->perkembangan_usaha ?? 'tetap') }}">
                    {{ $item->perkembangan_usaha ?? '-' }}
                </td>
                <td class="{{ $item->status_hasil == 'Tercapai' ? 'badge-tercapai' : 'badge-belum' }}">
                    {{ $item->status_hasil ?? '-' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center">Belum ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>