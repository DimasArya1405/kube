<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Perkembangan Usaha</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 0;
        }

        p {
            text-align: center;
            margin-top: 3px;
            margin-bottom: 15px;
            font-size: 10px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th {
            background: #2563eb;
            color: white;
            text-align: center;
            padding: 6px;
            font-size: 10px;
        }

        td {
            padding: 5px;
            vertical-align: top;
            font-size: 9px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .green {
            color: green;
            font-weight: bold;
        }

        .red {
            color: red;
            font-weight: bold;
        }

        .orange {
            color: darkorange;
            font-weight: bold;
        }

        .gray {
            color: gray;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <h2>DATA PERKEMBANGAN USAHA KUBE</h2>

    <p>
        Dicetak pada :
        {{ now()->format('d-m-Y H:i') }}
    </p>

    <table>

        <thead>

            <tr>

                <th>No</th>

                <th>Nama KUBE</th>

                <th>Periode</th>

                <th>Omset</th>

                <th>Pengeluaran</th>

                <th>Laba Bersih</th>

                <th>Selisih Laba</th>

                <th>Total Omset</th>

                <th>Perkembangan</th>

                <th>Status</th>

                <th>Evaluasi</th>

                <th>Rekomendasi</th>

            </tr>

        </thead>

        <tbody>

            @forelse($data as $index => $item)

                @php

                    $namaKube = '-';

                    if ($item->laporan && $item->laporan->cluster) {

                        $kube = $item->laporan->cluster->kube->first();

                        if($kube){

                            $namaKube = $kube->nama_kube;

                        }

                    }

                @endphp

                <tr>

                    <td class="center">
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $namaKube }}
                    </td>

                    <td class="center">
                        {{ $item->periode_bulan }}/{{ $item->periode_tahun }}
                    </td>

                    <td class="right">
                        Rp {{ number_format($item->omset_pendapatan,0,',','.') }}
                    </td>

                    <td class="right">
                        Rp {{ number_format($item->total_pengeluaran,0,',','.') }}
                    </td>

                    <td class="right">
                        Rp {{ number_format($item->laba_bersih,0,',','.') }}
                    </td>

                    <td class="right">
                        Rp {{ number_format($item->selisih_laba,0,',','.') }}
                    </td>

                    <td class="right">
                        Rp {{ number_format($item->total_omset,0,',','.') }}
                    </td>

                    <td class="center">

                        @if($item->perkembangan_usaha=='Meningkat')

                            <span class="green">
                                {{ $item->perkembangan_usaha }}
                            </span>

                        @elseif($item->perkembangan_usaha=='Menurun')

                            <span class="red">
                                {{ $item->perkembangan_usaha }}
                            </span>

                        @else

                            <span class="gray">
                                {{ $item->perkembangan_usaha }}
                            </span>

                        @endif

                    </td>

                    <td class="center">

                        @if($item->status_hasil=='Tercapai')

                            <span class="green">
                                {{ $item->status_hasil }}
                            </span>

                        @else

                            <span class="orange">
                                {{ $item->status_hasil }}
                            </span>

                        @endif

                    </td>

                    <td>

                        {{ $item->hasil_evaluasi ?? '-' }}

                    </td>

                    <td>

                        {{ $item->rekomendasi ?? '-' }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="12" class="center">

                        Tidak ada data.

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</body>

</html>