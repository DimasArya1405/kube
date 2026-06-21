<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembagian Koordinator</title>

    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
        }

        th {
            background: #eee;
        }

        .header {
            background: #dbeafe;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>Laporan Pembagian Koordinator</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Pendamping</th>
            <th>KUBE</th>
            <th>Tanggal Mulai</th>
            <th>Tanggal Selesai</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

    @foreach($data as $group)

        @php
            $koorHeader = $group->first()?->koordinator;
        @endphp

        {{-- HEADER KOORDINATOR --}}
        <tr class="header">
            <td colspan="6">
                {{ $koorHeader->nama_koor ?? '-' }}
            </td>
        </tr>

        @foreach($group as $row)

            @php
                $pp = $row->pembagianPendamping;
                $status = ($row->tgl_selesai && \Carbon\Carbon::parse($row->tgl_selesai)->isPast())
                    ? 'Selesai' : 'Aktif';
            @endphp

            <tr>
                <td>{{ $loop->iteration }}</td>

                <td>{{ $pp->pendamping->nama_pendamping ?? '-' }}</td>

                <td>{{ $pp->kube->nama_kube ?? '-' }}</td>

                <td>
                    {{ $row->tgl_mulai ? \Carbon\Carbon::parse($row->tgl_mulai)->format('d-m-Y') : '-' }}
                </td>

                <td>
                    {{ $row->tgl_selesai ? \Carbon\Carbon::parse($row->tgl_selesai)->format('d-m-Y') : '-' }}
                </td>

                <td>{{ $status }}</td>
            </tr>

        @endforeach

    @endforeach

    </tbody>
</table>

</body>
</html>