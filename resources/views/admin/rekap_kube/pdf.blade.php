<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap KUBE</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 4px; }
        p.subtitle { text-align: center; margin-top: 0; color: #555; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background-color: #d1d5db; padding: 6px 8px; text-align: left; border: 1px solid #9ca3af; }
        td { padding: 6px 8px; border: 1px solid #d1d5db; }
        tr:nth-child(even) { background-color: #f9fafb; }
        tfoot td { font-weight: bold; background-color: #d1d5db; }
        .text-center { text-align: center; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
    </style>
</head>
<body>

    <h2>Rekap KUBE</h2>
    <p class="subtitle">
        {{ $filterKecamatan ? 'Kecamatan: ' . $filterKecamatan : 'Semua Kecamatan' }}
        {{ $filterKategori ? ' | Kategori: ' . $filterKategori : '' }}
    </p>

    <table>
        <thead>
            <tr>
                <th class="text-center">No</th>
                <th>Kecamatan</th>
                <th>Kategori</th>
                <th class="text-center">Jumlah</th>
                <th class="text-center">Aktif</th>
                <th class="text-center">Tidak Aktif</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rekap as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->nama_kecamatan }}</td>
                    <td>{{ $item->nama_kategori }}</td>
                    <td class="text-center">{{ $item->jumlah_kube }}</td>
                    <td class="text-center text-green">{{ $item->kube_aktif }}</td>
                    <td class="text-center text-red">{{ $item->kube_tidak_aktif }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total Keseluruhan</td>
                <td class="text-center">{{ $rekap->sum('jumlah_kube') }}</td>
                <td class="text-center text-green">{{ $rekap->sum('kube_aktif') }}</td>
                <td class="text-center text-red">{{ $rekap->sum('kube_tidak_aktif') }}</td>
            </tr>
        </tfoot>
    </table>

</body>
</html>