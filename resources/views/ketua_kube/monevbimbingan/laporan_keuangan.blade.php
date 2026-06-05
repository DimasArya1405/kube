@extends('admin.layout')

@section('content')
<div class="p-4 bg-gray-50 min-h-screen font-sans">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-5 gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Keuangan KUBE</h1>
            <p class="text-[11px] text-gray-500">
                {{ (auth()->user()->role === 'admin' && !isset($selectedKubeId)) ? 'Pilih kelompok untuk memantau keuangan.' : 'Monitoring omset dan laba bulanan KUBE.' }}
            </p>
        </div>
@if(auth()->user()->role !== 'admin' || isset($selectedKubeId))
        <div class="flex flex-wrap items-center gap-2">
            @php
                $idKubeExport = $selectedKubeId ?? request('id_kube');
            @endphp

            @if($idKubeExport)
                <a href="{{ route('laporan.export.pdf.single', $idKubeExport) }}" class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all">
                    <span>📄</span> Ekspor PDF
                </a>
                <a href="{{ route('laporan.export.excel.single', $idKubeExport) }}" class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all">
                    <span>📊</span> Ekspor Excel
                </a>
            @else
                <a href="{{ route('laporan.export.pdf.all') }}" class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all">
                    <span>📄</span> Ekspor PDF Semua
                </a>
                <a href="{{ route('laporan.export.excel.all') }}" class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all">
                    <span>📊</span> Ekspor Excel Semua
                </a>
            @endif

            @if(auth()->user()->role !== 'admin')
                <button data-modal-target="modal-tambah-lk" data-modal-toggle="modal-tambah-lk"
                    class="flex items-center gap-2 bg-sky-700 hover:bg-sky-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all">
                    + Tambah Laporan
                </button>
            @endif
        </div>
        @endif
    </div>

    @if(auth()->user()->role === 'admin' && !isset($selectedKubeId))
    <div class="max-w-4xl mx-auto py-8">
        <div class="grid grid-cols-1 gap-3">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 px-2">Daftar Kelompok KUBE</h3>
            @forelse($daftarKube as $k)
            <a href="{{ route('laporan.index', ['id_kube' => $k->id_kube]) }}" 
               class="group flex items-center justify-between p-5 bg-white border border-gray-100 rounded-[1.5rem] hover:border-sky-500 hover:shadow-xl hover:shadow-sky-500/5 transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-50 text-slate-400 group-hover:bg-sky-100 group-hover:text-sky-600 rounded-xl flex items-center justify-center font-black text-lg transition-colors">
                        {{ substr($k->nama_kube, 0, 1) }}
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-gray-700 group-hover:text-sky-600 transition-colors uppercase">{{ $k->nama_kube }}</span>
                        <span class="text-[10px] text-gray-400 font-medium tracking-tight">Klik untuk monitoring laporan keuangan</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-gray-50 group-hover:bg-sky-600 flex items-center justify-center transition-all text-gray-300 group-hover:text-white">→</div>
            </a>
            @empty
            <div class="p-10 text-center bg-white rounded-3xl border border-dashed text-gray-400 text-xs italic">Belum ada data KUBE tersedia.</div>
            @endforelse
        </div>
    </div>
    @endif

    @if(auth()->user()->role !== 'admin' || isset($selectedKubeId))
     <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        
        {{-- Card 1: Total Omset --}}
        <div class="group bg-white border border-gray-100/50 rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-sky-500/10 hover:-translate-y-2 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] mb-1">Total Omset</p>
            <div class="flex items-baseline gap-1">
                <span class="text-xs font-bold text-gray-300">Rp</span>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight">
                    {{ number_format($totalOmset, 0, ',', '.') }}
                </h3>
            </div>
        </div>

        {{-- Card 2: Laba Bersih --}}
        <div class="group bg-white border border-gray-100/50 rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-emerald-500/10 hover:-translate-y-2 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] mb-1">Laba Bersih</p>
            <div class="flex items-baseline gap-1">
                <span class="text-xs font-bold text-gray-300">Rp</span>
                <h3 class="text-2xl font-black text-emerald-600 tracking-tight">
                    {{ number_format($totalLaba, 0, ',', '.') }}
                </h3>
            </div>
        </div>

        {{-- Card 3: Perkembangan --}}
        <div class="group bg-white border border-gray-100/50 rounded-[2rem] p-6 shadow-sm hover:shadow-xl hover:shadow-sky-500/10 hover:-translate-y-2 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
            </div>
            <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.15em] mb-1">Perkembangan</p>
            <div class="flex items-center gap-2">
                <div class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                </div>
                <h3 class="text-sm font-black text-sky-700 uppercase tracking-tight">
                    {{ $perkembangan }}
                </h3>
            </div>
        </div>

    </div>

    {{-- Table --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tgl Lapor</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">KUBE & Cluster</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Periode</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Omset</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Laba</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-wider text-center">Lampiran</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($laporan as $row)
                    <tr class="hover:bg-gray-50/50 transition">
                        <td class="px-4 py-2.5 text-xs text-gray-600">
                            {{ \Carbon\Carbon::parse($row->tanggal_laporan)->translatedFormat('d/m/y') }}
                        </td>
                        <td class="px-4 py-2.5">
                            @php
                                $dataKube = \DB::table('pengajuan_kube')
                                    ->join('kube', 'pengajuan_kube.id_kube', '=', 'kube.id_kube')
                                    ->where('pengajuan_kube.id_pengajuan_kube', $row->id_persetujuan)
                                    ->first();
                            @endphp
                            <div class="font-bold text-gray-700 text-xs">{{ $dataKube->nama_kube ?? 'N/A' }}</div>
                            <div class="text-[10px] text-gray-400 font-medium uppercase">{{ $row->cluster->nama_cluster ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            <span class="px-2 py-0.5 bg-gray-100 rounded text-[10px] font-bold text-gray-500 uppercase">
                                {{ date('M y', mktime(0, 0, 0, $row->periode_bulan, 10)) }}
                            </span>
                        </td>
                        <td class="px-4 py-2.5 text-right font-medium text-gray-600 text-xs">
                            {{ number_format($row->omset_pendapatan, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2.5 text-right font-bold text-emerald-600 text-xs">
                            {{ number_format($row->laba_bersih, 0, ',', '.') }}
                        </td>
                        
                        <td class="px-4 py-2.5 text-center text-[10px]">
                            @if($row->progres_keuangan == 'Meningkat')
                                <span class="text-emerald-500 font-black">▲</span>
                            @elseif($row->progres_keuangan == 'Menurun')
                                <span class="text-red-500 font-black">▼</span>
                            @else
                                <span class="text-sky-400 font-black">●</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                    @if($row->lampiran_keuangan)
                        <a href="{{ asset('uploads/keuangan/' . $row->lampiran_keuangan) }}" target="_blank" 
                           class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-sky-50 hover:bg-sky-100 text-sky-600 rounded-lg text-[9px] font-black uppercase tracking-wider transition-all">
                            <span>📄</span> Berkas
                        </a>
                    @else
                        <span class="inline-flex items-center px-2 py-1 bg-slate-100 text-slate-400 rounded-lg text-[9px] font-bold">
                            Kosong
                        </span>
                    @endif
                </td>
                        <td class="px-4 py-2.5">
                            <div class="flex justify-center gap-1.5">
                                <button type="button" onclick="showDetail(this)"
                                data-id="{{ $row->id_laporan }}"
                                data-id="{{ $row->id_laporan ?? $row->id_laporan ?? $row->id }}"
                                    data-kube="{{ $dataKube->nama_kube ?? 'N/A' }}"
                                    data-cluster="{{ $row->cluster->nama_cluster ?? '-' }}"
                                    data-periode="{{ date('F Y', mktime(0, 0, 0, $row->periode_bulan, 10)) }}"
                                    data-tgl="{{ \Carbon\Carbon::parse($row->tanggal_laporan)->translatedFormat('d F Y') }}"
                                    data-omset="Rp {{ number_format($row->omset_pendapatan, 0, ',', '.') }}"
                                    data-pengeluaran="Rp {{ number_format($row->total_pengeluaran, 0, ',', '.') }}"
                                    data-laba="Rp {{ number_format($row->laba_bersih, 0, ',', '.') }}"
                                    data-ket="{{ $row->keterangan ?? '-' }}"
                                    class="p-1.5 text-sky-400 hover:bg-sky-50 rounded-md transition text-xs">👁️</button>

                                @if(auth()->user()->role !== 'admin')
                                <button onclick="openEditModal(this)"
                                    data-id="{{ $row->id_laporan }}"
                                    data-kube="{{ $row->id_persetujuan }}"
                                    data-cluster="{{ $row->id_cluster }}"
                                    data-omset="{{ $row->omset_pendapatan }}"
                                    data-pengeluaran="{{ $row->total_pengeluaran }}"
                                    data-bulan="{{ $row->periode_bulan }}"
                                    data-tahun="{{ $row->periode_tahun }}"
                                    data-tgl="{{ $row->tanggal_laporan }}"
                                    data-ket="{{ $row->keterangan }}"
                                    class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-md transition text-xs">✏️</button>

                                <form id="delete-form-{{ $row->id_laporan }}" action="{{ route('laporan.destroy', $row->id_laporan) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $row->id_laporan }}')" class="p-1.5 text-red-400 hover:bg-red-50 rounded-md transition text-xs">🗑️</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="p-12 text-center text-gray-400 text-xs italic">Data laporan belum tersedia.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

{{-- MODAL SECTION --}}

<div id="modal-tambah-lk" tabindex="-1" aria-hidden="true" 
     class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-screen bg-slate-900/40 backdrop-blur-sm flex items-center justify-center">
    
    <div class="relative w-full max-w-md my-auto">
        <div class="relative bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="flex items-start justify-between px-6 py-4 border-b border-slate-50 shrink-0">
                <div>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">
                        Tambah Laporan Keuangan
                    </h3>
                    <p class="text-[9px] text-slate-400 mt-0.5">Catat omset dan pengeluaran kelompok Anda</p>
                </div>
                <button type="button" data-modal-hide="modal-tambah-lk" 
                        class="text-slate-400 hover:bg-slate-50 hover:text-slate-900 rounded-xl text-xs p-1.5 ml-auto inline-flex items-center transition-all">
                    ✕
                </button>
            </div>

            <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden">
                @csrf

                <div class="p-6 space-y-3.5 overflow-y-auto max-h-[60vh] scrollbar-thin scrollbar-thumb-slate-200">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kelompok KUBE</label>
                        <div class="flex items-center justify-between w-full bg-slate-100 border border-slate-200/50 rounded-xl px-4 py-2">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-bold text-slate-700 uppercase">
                                    {{ $kubeMilikSaya->nama_kube ?? 'KUBE TIDAK DITEMUKAN' }}
                                </span>
                                <span class="text-[8px] text-slate-500">ID: {{ $kubeMilikSaya->id_kube ?? '-' }}</span>
                            </div>
                            <span class="text-[9px] bg-slate-200 text-slate-500 px-2 py-0.5 rounded-md font-black">TERKUNCI</span>
                        </div>
                        
                        @php 
                            $id_pengajuan = $kubeDisetujui->first()->id_pengajuan_kube ?? ''; 
                        @endphp
                        <input type="hidden" name="id_persetujuan" value="{{ $id_pengajuan }}">
                    </div>

                    <div class="grid grid-cols-3 gap-3">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Cluster Usaha</label>
                            <select name="id_cluster" class="w-full bg-slate-50 border-none rounded-xl px-3 py-1.5 text-[10px] font-bold focus:ring-2 focus:ring-sky-500" required>
                                <option value="">Pilih</option>
                                @foreach($clusters as $c) 
                                    <option value="{{ $c->id_cluster }}">{{ $c->nama_cluster }}</option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Bulan</label>
                            <select name="periode_bulan" class="w-full bg-slate-50 border-none rounded-xl px-3 py-1.5 text-[10px] font-bold focus:ring-2 focus:ring-sky-500" required>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ date('m') == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Tahun</label>
                            <select name="periode_tahun" class="w-full bg-slate-50 border-none rounded-xl px-3 py-1.5 text-[10px] font-bold focus:ring-2 focus:ring-sky-500" required>
                                @foreach(range(date('Y') - 5, date('Y') + 1) as $year)
                                    <option value="{{ $year }}" {{ date('Y') == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-2.5 bg-emerald-50/75 rounded-2xl border border-emerald-100 focus-within:border-emerald-400 transition-all">
                            <label class="text-[8px] font-black text-emerald-600 uppercase block text-center mb-0.5">Omset (Rp)</label>
                            <input type="number" name="omset_pendapatan" placeholder="0" class="w-full bg-transparent border-none text-xs font-black text-emerald-700 text-center p-0 focus:ring-0 placeholder:text-emerald-200" required>
                        </div>
                        <div class="p-2.5 bg-rose-50/75 rounded-2xl border border-rose-100 focus-within:border-rose-400 transition-all">
                            <label class="text-[8px] font-black text-rose-600 uppercase block text-center mb-0.5">Pengeluaran (Rp)</label>
                            <input type="number" name="total_pengeluaran" placeholder="0" class="w-full bg-transparent border-none text-xs font-black text-rose-700 text-center p-0 focus:ring-0 placeholder:text-rose-200" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Tanggal Laporan Berkas</label>
                            <input type="date" name="tanggal_laporan" value="{{ date('Y-m-d') }}" 
                                   class="w-full bg-slate-50 border-none rounded-xl px-3 py-1.5 text-[10px] font-bold focus:ring-2 focus:ring-sky-500" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Bukti Lampiran (PDF/Gambar)</label>
                            <input type="file" name="lampiran_keuangan" 
                                   class="w-full text-[9px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[9px] file:font-black file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100 cursor-pointer mt-0.5">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Keterangan Tambahan</label>
                        <textarea name="keterangan" rows="2" placeholder="Contoh: Pembelian bahan baku..." class="w-full bg-slate-50 border-none rounded-xl text-[10px] p-2.5 resize-none focus:ring-2 focus:ring-sky-500 placeholder:text-slate-300"></textarea>
                    </div>
                </div>

                <div class="p-6 border-t border-slate-50 bg-white shrink-0">
                    <button type="submit" class="w-full h-10 bg-sky-500 hover:bg-sky-600 text-white font-black rounded-2xl text-[10px] uppercase tracking-widest shadow-lg shadow-sky-100 transition-all active:scale-[0.98]">
                        Simpan Laporan Keuangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="modal-edit-lk" class="hidden fixed inset-0 z-[100] flex justify-center items-center w-full h-screen bg-slate-900/40 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-md my-auto">
        <div class="relative bg-white rounded-[2rem] shadow-2xl border border-slate-100 overflow-hidden flex flex-col max-h-[90vh]">
            <div class="px-6 py-4 border-b border-slate-50 flex justify-between items-center bg-slate-50/50 shrink-0">
                <div>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">Edit Laporan Keuangan</h3>
                    <p class="text-[9px] text-slate-400 mt-0.5">Perbarui data omset dan pengeluaran kelompok</p>
                </div>
                <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:bg-slate-50 hover:text-red-500 rounded-xl text-xs p-1.5 ml-auto inline-flex items-center transition-all">✕</button>
            </div>

            <form id="form-edit-lk" method="POST" enctype="multipart/form-data" class="flex flex-col overflow-hidden">
                @csrf 
                @method('PUT')
                
                <div class="p-6 space-y-3.5 overflow-y-auto max-h-[60vh] scrollbar-thin scrollbar-thumb-slate-200">
                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Kelompok KUBE</label>
                        
                        @if(auth()->user()->role !== 'admin')
                            <div class="flex items-center justify-between w-full bg-slate-100 border border-slate-200/50 rounded-xl px-4 py-2">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-bold text-slate-700 uppercase">
                                        {{ $kubeMilikSaya->nama_kube ?? 'KUBE Tidak Ditemukan' }}
                                    </span>
                                    <span class="text-[8px] text-slate-500">ID: {{ $kubeMilikSaya->id_kube ?? '-' }}</span>
                                </div>
                                <span class="text-[9px] bg-slate-200 text-slate-500 px-2 py-0.5 rounded-md font-black">TERKUNCI</span>
                            </div>

                            @php 
                                $id_pengajuan = $kubeDisetujui->first()->id_pengajuan_kube ?? ''; 
                            @endphp
                            <input type="hidden" name="id_persetujuan" id="edit-kube-hidden" value="{{ $id_pengajuan }}">
                        @else
                            <select name="id_persetujuan" id="edit-kube" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[10px] font-bold focus:ring-2 focus:ring-sky-500" required>
                                @foreach($kubeDisetujui as $k) 
                                    <option value="{{ $k->id_pengajuan_kube }}">{{ $k->nama_tampilan }}</option> 
                                @endforeach
                            </select>
                        @endif
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Cluster Usaha</label>
                            <select name="id_cluster" id="edit-cluster" class="w-full bg-slate-50 border-none rounded-xl px-3 py-1.5 text-[10px] font-bold focus:ring-2 focus:ring-sky-500" required>
                                @foreach($clusters as $c) 
                                    <option value="{{ $c->id_cluster }}">{{ $c->nama_cluster }}</option> 
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Bulan</label>
                            <select name="periode_bulan" id="edit-bulan" class="w-full bg-slate-50 border-none rounded-xl px-3 py-1.5 text-[10px] font-bold focus:ring-2 focus:ring-sky-500" required>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}">
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Tahun</label>
                            <select name="periode_tahun" id="edit-tahun" class="w-full bg-slate-50 border-none rounded-xl px-3 py-1.5 text-[10px] font-bold focus:ring-2 focus:ring-sky-500" required>
                                @foreach(range(date('Y') - 5, date('Y') + 1) as $year)
                                    <option value="{{ $year }}">
                                        {{ $year }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-2.5 bg-emerald-50/75 rounded-2xl border border-emerald-100 focus-within:border-emerald-400 transition-all">
                            <label class="text-[8px] font-black text-emerald-600 uppercase block text-center mb-0.5">Omset (Rp)</label>
                            <input type="number" name="omset_pendapatan" id="edit-omset" placeholder="0" class="w-full bg-transparent border-none text-xs font-black text-emerald-700 text-center p-0 focus:ring-0 placeholder:text-emerald-200" required>
                        </div>
                        <div class="p-2.5 bg-rose-50/75 rounded-2xl border border-rose-100 focus-within:border-rose-400 transition-all">
                            <label class="text-[8px] font-black text-rose-600 uppercase block text-center mb-0.5">Pengeluaran (Rp)</label>
                            <input type="number" name="total_pengeluaran" id="edit-pengeluaran" placeholder="0" class="w-full bg-transparent border-none text-xs font-black text-rose-700 text-center p-0 focus:ring-0 placeholder:text-rose-200" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Tanggal Laporan Berkas</label>
                            <input type="date" name="tanggal_laporan" id="edit-tgl" 
                                   class="w-full bg-slate-50 border-none rounded-xl px-3 py-1.5 text-[10px] font-bold focus:ring-2 focus:ring-sky-500" required>
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Bukti Lampiran (PDF/Gambar)</label>
                            <input type="file" name="lampiran_keuangan" 
                                   class="w-full text-[9px] text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-[9px] file:font-black file:bg-sky-50 file:text-sky-600 hover:file:bg-sky-100 cursor-pointer mt-0.5">
                            <p id="edit-file-info" class="text-[8px] text-slate-400 italic mt-0.5"></p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-wider">Keterangan Tambahan</label>
                        <textarea name="keterangan" id="edit-ket" rows="2" placeholder="Catatan perubahan..." class="w-full bg-slate-50 border-none rounded-xl text-[10px] p-2.5 resize-none focus:ring-2 focus:ring-sky-500 placeholder:text-slate-300"></textarea>
                    </div>
                </div>
                <div class="p-6 border-t border-slate-50 bg-white shrink-0">
                    <button type="submit" class="w-full h-10 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-2xl text-[10px] uppercase tracking-widest shadow-lg shadow-amber-100 transition-all active:scale-[0.98]">
                        Perbarui Laporan Keuangan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="modal-detail-lk" class="hidden fixed inset-0 z-[150] flex justify-center items-center w-full h-full bg-black/50 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-sm bg-white rounded-[2rem] shadow-2xl p-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p id="det-cluster" class="text-[9px] font-bold text-sky-500 uppercase tracking-widest"></p>
                <h4 id="det-kube" class="text-base font-black text-gray-900 tracking-tight"></h4>
                <p id="det-periode" class="text-[10px] text-gray-400 font-bold uppercase"></p>
                <p id="det-tgl" class="hidden"></p>
            </div>
            <button onclick="closeDetail()" class="text-gray-300 hover:text-gray-600 transition text-xl">✕</button>
        </div>
        <div class="space-y-3 py-4 border-y border-gray-50 mb-4">
            <div class="flex justify-between"><span class="text-[10px] font-bold text-gray-400">Total Omset</span><span id="det-omset" class="text-xs font-bold text-gray-700"></span></div>
            <div class="flex justify-between"><span class="text-[10px] font-bold text-gray-400">Pengeluaran</span><span id="det-pengeluaran" class="text-xs font-bold text-red-500"></span></div>
            <div class="flex justify-between pt-2 border-t"><span class="text-[10px] font-black text-gray-900">Laba Bersih</span><span id="det-laba" class="text-sm font-black text-emerald-500"></span></div>
        </div>
        <div class="mb-6 bg-gray-50 p-3 rounded-xl border border-gray-100">
            <p id="det-ket" class="text-[11px] text-gray-500 italic text-center leading-relaxed"></p>
        </div>
          <button id="btn-cetak-modal" type="button" onclick="printFormalReport()" class="w-full py-2.5 bg-sky-700 text-white font-bold rounded-xl text-[10px] tracking-widest hover:bg-sky-900 transition-all flex items-center justify-center gap-2 shadow-lg">
                    🖨️ CETAK PDF
                </button>
                <button type="button" onclick="closeDetail()" class="w-full py-2 bg-transparent text-gray-400 font-bold rounded-lg text-[10px] hover:text-gray-600 transition">
                    KEMBALI
                </button>
    </div>
</div>

{{-- SCRIPT SECTION --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  function showDetail(btn) {
    const idLaporan = btn.getAttribute('data-id');
    document.getElementById('det-kube').innerText = btn.getAttribute('data-kube');
    document.getElementById('det-cluster').innerText = btn.getAttribute('data-cluster');
    document.getElementById('det-periode').innerText = "Periode " + btn.getAttribute('data-periode');
    document.getElementById('det-omset').innerText = btn.getAttribute('data-omset');
    document.getElementById('det-pengeluaran').innerText = btn.getAttribute('data-pengeluaran');
    document.getElementById('det-laba').innerText = btn.getAttribute('data-laba');
    document.getElementById('det-ket').innerText = btn.getAttribute('data-ket');
    document.getElementById('det-tgl').innerText = btn.getAttribute('data-tgl');

   
    const btnCetak = document.getElementById('btn-cetak-modal');
    if (btnCetak) {
        
        const urlCetak = `/laporan-keuangan/export/pdf-detail/${idLaporan}`;
        btnCetak.setAttribute('onclick', `printLaporan('${urlCetak}')`);
    }

    const modal = document.getElementById('modal-detail-lk');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

    function closeDetail() {
        const modal = document.getElementById('modal-detail-lk');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

function openEditModal(button) {
    const id = button.getAttribute('data-id');
    const kube = button.getAttribute('data-kube');
    const cluster = button.getAttribute('data-cluster');
    const omset = button.getAttribute('data-omset');
    const pengeluaran = button.getAttribute('data-pengeluaran');
    const bulan = button.getAttribute('data-bulan');
    const tahun = button.getAttribute('data-tahun');
    const ket = button.getAttribute('data-ket');

    const form = document.getElementById('form-edit-lk');
    form.action = `/laporan-keuangan/${id}`; 

    document.getElementById('edit-omset').value = omset;
    document.getElementById('edit-pengeluaran').value = pengeluaran;
    document.getElementById('edit-ket').value = ket || '';
    document.getElementById('edit-cluster').value = cluster;
    document.getElementById('edit-bulan').value = bulan;
    document.getElementById('edit-tahun').value = tahun;

    const editKubeSelect = document.getElementById('edit-kube');
    if (editKubeSelect) {
        editKubeSelect.value = kube;
    }

    document.getElementById('modal-edit-lk').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modal-edit-lk').classList.add('hidden');
}

    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data laporan ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: { popup: 'rounded-[2rem]' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
    }

    function printFormalReport() {
        window.print();
    }
function printLaporan(url) {
    const iframe = document.getElementById('print-frame');
    if (iframe) {
        iframe.src = url;
        iframe.onload = function() {
            try {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            } catch (e) {
                window.open(url, '_blank');
            }
        };
    } else {
        console.error("Elemen iframe dengan id 'print-frame' tidak ditemukan!");
    }
}
</script>
  <iframe id="print-frame" class="hidden" style="display:none;"></iframe>
@endsection