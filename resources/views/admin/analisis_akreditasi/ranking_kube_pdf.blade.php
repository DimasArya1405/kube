<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 11px; color: #333; }
        h2 { text-align: center; margin-bottom: 4px; }
        p.sub { text-align: center; color: #666; margin-bottom: 8px; font-size: 10px; }

        /* Keterangan filter */
        .filter-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 4px;
            padding: 6px 10px;
            margin-bottom: 12px;
            font-size: 10px;
            color: #1e40af;
        }
        .filter-info strong { color: #1d4ed8; }

        table { width: 100%; border-collapse: collapse; }
        thead tr { background-color: #2563eb; color: white; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; }
        th { text-align: center; }
        td.center { text-align: center; }
        td.right { text-align: right; }
        tr:nth-child(even) { background-color: #f9fafb; }
        .aktif    { color: #1d4ed8; font-weight: bold; }
        .nonaktif { color: #dc2626; font-weight: bold; }

        /* Baris total */
        tfoot tr {
            background-color: #1e40af;
            color: white;
            font-weight: bold;
        }
        tfoot td { border-color: #3b5fcf; }
        tfoot td.right { text-align: right; }
    </style>
</head>
<body>
    <h2>Ranking KUBE</h2>
    <p class="sub">Ranking KUBE terbaik berdasarkan laba bersih &mdash; Dicetak {{ now()->format('d/m/Y H:i') }}</p>

    {{-- Keterangan filter --}}
    @php
        $filterAktif = collect([
            'Kecamatan' => request('kecamatan') ? $filtered->first()?->nama_kecamatan : null,
            'Tahun'     => request('tahun'),
            'Kategori'  => request('kategori') ? $filtered->first()?->nama_kategori : null,
            'Status'    => request('status'),
        ])->filter()->toArray();

        $hideNominal = $hideNominal ?? false;

        // Hitung total langsung di blade (hanya jika nominal ditampilkan)
        $totalOmset       = $hideNominal ? 0 : $filtered->sum('total_omset');
        $totalPengeluaran = $hideNominal ? 0 : $filtered->sum('total_pengeluaran');
        $totalLabaBersih  = $hideNominal ? 0 : $filtered->sum('total_laba_bersih');

        // Jumlah kolom dasar: No, Nama, Cluster, Kecamatan, (3 kolom nominal jika tampil), Status, Peringkat
        $colSpan = ($hideNominal ? 6 : 9) + (count($filterAktif) ? 1 : 0);
    @endphp

    @if(count($filterAktif))
    <div class="filter-info">
        <strong>Filter aktif:</strong>
        {{ collect($filterAktif)->map(fn($v, $k) => "$k: $v")->implode(' &nbsp;|&nbsp; ') }}
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th style="width:30px">No</th>
                <th>Nama KUBE</th>
                <th>Cluster</th>
                <th>Kecamatan</th>
                @unless($hideNominal)
                <th>Total Omset</th>
                <th>Total Pengeluaran</th>
                <th>Total Laba Bersih</th>
                @endunless
                <th>Status</th>
                <th>Peringkat<br><span style="font-weight:normal;font-size:9px">(Keseluruhan)</span></th>
                @if(count($filterAktif))
                <th>Peringkat<br><span style="font-weight:normal;font-size:9px">(Filter)</span></th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($filtered as $i => $item)
            <tr>
                <td class="center">{{ $i + 1 }}</td>
                <td>{{ $item->nama_kube }}</td>
                <td>{{ $item->nama_cluster }}</td>
                <td>{{ $item->nama_kecamatan }}</td>
                @unless($hideNominal)
                <td class="right">Rp {{ number_format($item->total_omset, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($item->total_pengeluaran, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($item->total_laba_bersih, 0, ',', '.') }}</td>
                @endunless
                <td class="center {{ $item->status === 'Aktif' ? 'aktif' : 'nonaktif' }}">{{ $item->status }}</td>
                <td class="center">{{ $item->ranking_overall }}</td>
                @if(count($filterAktif))
                <td class="center">{{ $item->ranking_filter }}</td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ $colSpan }}" style="text-align:center;padding:16px;color:#999">
                    Tidak ada data.
                </td>
            </tr>
            @endforelse
        </tbody>

        {{-- Baris total (hanya tampil jika nominal ditampilkan) --}}
        @if($filtered->isNotEmpty() && !$hideNominal)
        <tfoot>
            <tr>
                <td colspan="4" class="center">TOTAL</td>
                <td class="right">Rp {{ number_format($totalOmset, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($totalLabaBersih, 0, ',', '.') }}</td>
                {{-- Kolom Status, Peringkat, (opsional Peringkat Filter) dikosongkan --}}
                <td colspan="{{ count($filterAktif) ? 3 : 2 }}"></td>
            </tr>
        </tfoot>
        @endif
    </table>
</body>
</html>