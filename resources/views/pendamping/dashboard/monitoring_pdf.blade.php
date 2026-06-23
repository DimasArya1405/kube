<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monitoring Bantuan</title>
    <style>
        body { 
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; 
            font-size: 11px; 
            color: #333;
            margin: 10px;
        }
        
        /* 📋 HEADER STYLE */
        .header-container {
            text-align: center;
            margin-bottom: 25px;
        }
        .header-container h2 {
            font-size: 20px;
            font-weight: 500;
            margin: 0 0 6px 0;
            color: #000;
        }
        .header-container p {
            font-size: 11px;
            color: #555;
            margin: 0;
        }

        /* 📊 TABLE STYLE */
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 15px;
            margin-bottom: 15px;
        }
        
        /* Header Tabel Biru Sesuai Gambar */
        th { 
            background-color: #2563eb; /* Warna Biru Banner */
            color: #ffffff; 
            font-size: 11px;
            font-weight: bold;
            padding: 8px 6px;
            border: 1px solid #93c5fd; /* Border Biru Muda */
            text-align: center;
        }
        
        /* Isi Baris Data */
        td { 
            border: 1px solid #d1d5db; /* Border Abu-abu Tipis Rapi */
            padding: 8px 7px;
            vertical-align: middle;
            color: #111827;
        }
        
        /* Zebra Striping (Belang-belang tipis agar mudah dibaca) */
        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        /* Alignments */
        .text-center { text-align: center; }
        .text-left { text-align: left; }
        
        /* 🟢🔴 STATUS BADGE STYLE (Sama persis seperti gambar) */
        .status-sesuai {
            color: #16a34a; /* Hijau Tua */
            font-weight: bold;
        }
        .status-tidak {
            color: #dc2626; /* Merah Tua */
            font-weight: bold;
        }

        /* 📊 SUMMARY FOOTER */
        .summary-box {
            margin-top: 15px;
            font-size: 11px;
            font-weight: bold;
        }
        .text-success { color: #16a34a; }
        .text-danger { color: #dc2626; }
        .spacer { margin-right: 15px; }
    </style>
</head>
<body>

    {{-- JUDUL ATAS --}}
    <div class="header-container">
        <h2>Data Monitoring Bantuan KUBE</h2>
        <p>Daftar monitoring KUBE — Dicetak {{ date('d/m/Y H:i') }}</p>
    </div>

    {{-- TABEL UTAMA --}}
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 15%;">Jenis Bantuan</th>
                <th style="width: 18%;">Nama KUBE</th>
                <th style="width: 15%;">Pendamping</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 13%;">Kesesuaian</th>
                <th style="width: 23%;">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monitoring as $i => $item)
            <tr>
                <td class="text-center">{{ $i+1 }}</td>
                
                <td class="text-left">{{ $item->jenisBantuan->jenis_bantuan ?? '-' }}</td>
                <td class="text-left">{{ $item->kube->nama_kube ?? '-' }}</td>
                <td class="text-left">{{ $item->pendamping->nama_pendamping ?? '-' }}</td>
                
                <td class="text-center">{{ date('d-m-Y', strtotime($item->tanggal_monitoring)) }}</td>
                
                <td class="text-center">
                    @if(strtolower($item->kesesuaian) == 'sesuai')
                        <span class="status-sesuai">Sesuai</span>
                    @else
                        <span class="status-tidak">Tidak Sesuai</span>
                    @endif
                </td>
                
                <td class="text-left">{{ $item->catatan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- 🔥 SUMMARY TOTAL DATA (Paling Bawah Kiri Sesuai Gambar) --}}
    <div class="summary-box">
        <span class="text-success">Sesuai: {{ $monitoring->where('kesesuaian', 'sesuai')->count() }}</span>
        <span class="spacer"></span>
        <span class="text-danger">Tidak Sesuai: {{ $monitoring->where('kesesuaian', 'tidak sesuai')->count() }}</span>
        <span class="spacer"></span>
        <span>Total: {{ $monitoring->count() }}</span>
    </div>

</body>
</html>