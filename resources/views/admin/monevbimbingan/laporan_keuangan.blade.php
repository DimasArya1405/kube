@extends('admin.layout')

@section('content')
<div class="p-4 bg-gray-50 min-h-screen font-sans">

{{-- Header Section --}}
<div class="flex flex-col md:flex-row md:items-center justify-between mb-5 gap-3">
    <div class="flex items-center gap-3">
        @if(request('id_kube'))
            <a href="{{ route('laporan.index') }}" 
               class="group p-2 bg-white hover:bg-sky-50 border border-gray-200 hover:border-sky-300 rounded-xl transition-all duration-300 shadow-sm flex items-center justify-center" 
               title="Kembali ke Daftar KUBE">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-hover:text-sky-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
        @endif

        <div>
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Keuangan KUBE</h1>
            <p class="text-[11px] text-gray-500">Monitoring omset dan laba bulanan KUBE.</p>
        </div>
    </div>

    <div class="flex items-center gap-2">
       @if(!request('id_kube'))
    <a href="{{ route('laporan.export.pdf.all') }}" 
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
        </svg>
        <span>Ekspor PDF</span>
    </a>

    <a href="{{ route('laporan.export.excel.all') }}" 
        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
        </svg>
        <span>Ekspor Excel</span>
    </a>
@endif

        @if(auth()->user()->role == 'ketua_kube')
            <button data-modal-target="modal-tambah-lk" data-modal-toggle="modal-tambah-lk"
                class="flex items-center gap-2 bg-sky-700 hover:bg-sky-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all">
                + Tambah Laporan
            </button>
        @endif
    </div>
</div>
{{-- Search Section --}}
@if(auth()->user()->role === 'admin' && !request('id_kube'))
<div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-6">
    <div class="flex flex-col gap-1 w-full">
        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Cari Kelompok KUBE </label>
        <div class="relative">
            <input type="text" id="search-kube-input" onkeyup="filterKubeInstant()"
                   placeholder="Ketik nama KUBE... (Contoh: Jaya)" 
                   class="w-full bg-gray-50 border border-gray-200 text-gray-700 text-xs rounded-xl pl-3 pr-10 py-2.5 focus:ring-sky-500 focus:border-sky-500 transition-all">
            
            <button type="button" id="clear-search-btn" onclick="resetSearchKube()" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs font-bold">
                ✕
            </button>
        </div>
    </div>
</div>
@endif

{{-- Statistik Ringkasan Keuangan --}}
@if(request('id_kube'))
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm min-h-[100px] flex flex-col justify-center">
            <p class="text-sm font-medium text-gray-500 mb-1">Total Omset</p>
            <h3 class="text-2xl font-bold text-slate-800 tracking-tight">
                <span class="text-xs font-semibold text-gray-400 mr-0.5">Rp</span>{{ number_format($totalOmset, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-emerald-50/40 border border-emerald-100 rounded-xl p-5 shadow-sm min-h-[100px] flex flex-col justify-center">
            <p class="text-sm font-medium text-emerald-600 mb-1">Laba Bersih</p>
            <h3 class="text-2xl font-bold text-emerald-700 tracking-tight">
                <span class="text-xs font-semibold text-emerald-400 mr-0.5">Rp</span>{{ number_format($totalLaba, 0, ',', '.') }}
            </h3>
        </div>

        <div class="bg-blue-50/40 border border-blue-100 rounded-xl p-5 shadow-sm min-h-[100px] flex flex-col justify-center sm:col-span-2 lg:col-span-1">
            <p class="text-sm font-medium text-blue-600 mb-1">Perkembangan</p>
            <div class="flex items-center gap-2 mt-1">
                <div class="relative flex h-2 w-2 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </div>
                <h3 class="text-base font-bold text-blue-800 uppercase tracking-tight truncate">
                    {{ $perkembangan }}
                </h3>
            </div>
        </div>
    </div>
@else
    {{-- List Kelompok KUBE --}}
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-5 px-2">
            <div class="w-1 h-6 bg-sky-600 rounded-full"></div>
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pilih Kelompok KUBE</h3>
        </div>

        <div id="container-daftar-kube" class="grid grid-cols-1 gap-3">
            @foreach($daftarKube as $k)
            <a href="{{ route('laporan.index', ['id_kube' => $k->id_kube]) }}" 
               data-nama="{{ strtolower($k->nama_kube) }}"
               class="kube-item-card group flex items-center justify-between p-4 bg-white border border-gray-100 rounded-2xl hover:border-sky-500 hover:shadow-md hover:shadow-sky-500/5 transition-all duration-300">
                
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 bg-slate-50 text-slate-400 group-hover:bg-sky-100 group-hover:text-sky-600 rounded-xl flex items-center justify-center font-black text-sm transition-colors">
                        {{ substr($k->nama_kube, 0, 1) }}
                    </div>
                    <div>
                        <span class="block text-xs font-black text-slate-700 group-hover:text-sky-600 transition-colors uppercase tracking-tight">{{ $k->nama_kube }}</span>
                        <span class="block text-[9px] text-gray-400 font-medium uppercase mt-0.5">Klik untuk memantau laporan</span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-[10px] font-bold text-gray-300 group-hover:text-sky-500 transition-colors uppercase">Buka Laporan</span>
                    <div class="w-8 h-8 rounded-lg bg-gray-50 group-hover:bg-sky-600 flex items-center justify-center transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </div>
            </a>
            @endforeach

            <div id="search-empty-msg" class="hidden p-10 text-center bg-white rounded-3xl border border-dashed text-gray-400 text-xs italic">
                Kelompok KUBE dengan nama tersebut tidak ditemukan.
            </div>
        </div>
    </div>
@endif

{{-- Tombol Aksi Ekspor Laporan --}}
@if(request('id_kube'))
    <div class="flex gap-2 mb-4 bg-white p-4 rounded-2xl border border-gray-100 shadow-sm items-center justify-between">
        <div></div>
        <div class="flex gap-2">
            <a href="{{ route('laporan.export.pdf.single', request('id_kube')) }}" 
                class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                </svg>
                <span>Ekspor PDF</span>
            </a>

            <a href="{{ route('laporan.export.excel.single', request('id_kube')) }}"  
                class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414A1 1 0 0119 9.414V19a2 2 0 01-2 2z" />
                </svg>
                <span>Ekspor Excel</span>
            </a>
        </div> 
    </div> 
@endif

{{-- Table Section --}}
<div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-4">
    <div class="overflow-x-auto">
            <div class="bg-white border border-gray-200 rounded-xl p-4 mb-5 shadow-sm">
    <form action="{{ url()->current() }}" method="GET" id="filter-form" class="flex flex-col md:flex-row items-center justify-between gap-3">
        @if(request('id_kube'))
            <input type="hidden" name="id_kube" value="{{ request('id_kube') }}">
        @endif

        <div class="relative w-full md:w-72">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input type="text" name="search" id="search-input" value="{{ request('search') }}" autocomplete="off"
                class="w-full ps-9 pe-4 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-700 focus:ring-purple-500 focus:border-purple-500 transition-all placeholder:text-gray-400" 
                placeholder="{{ auth()->user()->role === 'admin' ? 'Ketik nama kelompok / bulan...' : 'Ketik omset, bulan, status...' }}">
        </div>

        <div class="flex flex-wrap items-center gap-2 w-full md:w-auto justify-end">
            
            {{-- Dropdown Bulan --}}
            <div class="w-full sm:w-32">
                <select name="bulan" onchange="this.form.submit()" 
                    class="w-full px-2.5 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-700 focus:ring-purple-500 focus:border-purple-500 transition-all cursor-pointer">
                    <option value="">Semua Bulan</option>
                    @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $bulan)
                        <option value="{{ $bulan }}" {{ request('bulan') === $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Dropdown Tahun --}}
            <div class="w-full sm:w-28">
                <select name="tahun" onchange="this.form.submit()" 
                    class="w-full px-2.5 py-1.5 bg-gray-50 border border-gray-200 rounded-lg text-xs text-gray-700 focus:ring-purple-500 focus:border-purple-500 transition-all cursor-pointer">
                    <option value="">Semua Tahun</option>
                    @for($i = 2024; $i <= 2030; $i++)
                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            @if(request('search') || request('bulan') || request('tahun'))
                <div class="flex items-center shrink-0">
                    <a href="{{ url()->current() }}{{ request('id_kube') ? '?id_kube='.request('id_kube') : '' }}" 
                        class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-medium px-3 py-1.5 rounded-lg transition-all text-center w-full sm:w-auto">
                        Reset
                    </a>
                </div>
            @endif
        </div>
    </form>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let timer;
        const searchInput = document.getElementById('search-input');
        const filterForm = document.getElementById('filter-form');

        if (searchInput && filterForm) {
            searchInput.addEventListener('input', function() {
                clearTimeout(timer);
                timer = setTimeout(() => {
                    localStorage.setItem('search_cursor_pos', searchInput.selectionStart);
                    localStorage.setItem('is_searching', 'true');
                    filterForm.submit();
                }, 200); 
            });
            const isSearching = localStorage.getItem('is_searching');
            if (isSearching === 'true' && searchInput.value !== '') {
                searchInput.focus();
                const savedPos = localStorage.getItem('search_cursor_pos');
                if (savedPos) {
                    searchInput.setSelectionRange(savedPos, savedPos);
                } else {
                    const length = searchInput.value.length;
                    searchInput.setSelectionRange(length, length);
                }
                localStorage.removeItem('is_searching');
                localStorage.removeItem('search_cursor_pos');
            }
        }
    });
</script>  
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-sm text-gray-900 bg-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">Tgl Lapor</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest">KUBE & Cluster</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Periode</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Omset</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-right">Laba</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-4 py-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center">Lampiran</th>
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
              $dataKube = \DB::table('kube')->where('id_kube', $row->id_persetujuan)->first();
   if (!$dataKube) {
                $dataKube = \DB::table('pengajuan_kube')
                    ->join('kube', 'pengajuan_kube.id_kube', '=', 'kube.id_kube')
                    ->where('pengajuan_kube.id_pengajuan_kube', $row->id_persetujuan)
                    ->select('kube.*')
                    ->first();
            }
            $namaKubeFix = $dataKube->nama_kube ?? ($row->kube->nama_kube ?? 'N/A');
        @endphp

        <div class="font-bold text-gray-700 text-xs">{{ $namaKubeFix }}</div>
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
                        <td class="px-4 py-2.5 text-center">
                            <div class="flex justify-center gap-1.5">
                                
                                <button type="button" 
    onclick="showDetail(this)"
    data-id="{{ $row->id_laporan ?? $row->id }}"
    data-kube="{{ $dataKube->nama_kube ?? 'N/A' }}"
    data-cluster="{{ $row->cluster->nama_cluster ?? '-' }}"
    data-periode="{{ date('F Y', mktime(0, 0, 0, $row->periode_bulan, 10)) }}"
    data-tgl="{{ \Carbon\Carbon::parse($row->tanggal_laporan)->translatedFormat('d F Y') }}"
    data-omset="Rp {{ number_format($row->omset_pendapatan, 0, ',', '.') }}"
    data-pengeluaran="Rp {{ number_format($row->total_pengeluaran, 0, ',', '.') }}"
    data-laba="Rp {{ number_format($row->laba_bersih, 0, ',', '.') }}"
    data-ket="{{ $row->keterangan ?? '-' }}"
    class="p-1.5 text-gray-600 hover:bg-sky-50 rounded-md transition text-xs z-10">
    
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
</button>
                                
                                    @if(auth()->user()->role == 'ketua_kube')
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
                                    data-file="{{ $row->lampiran_keuangan }}"
                                    class="p-1.5 text-amber-500 hover:bg-amber-50 rounded-md transition text-xs">✏️</button>
                                @endif

                                @if(auth()->user()->role == 'ketua_kube')
                                <form id="delete-form-{{ $row->id_laporan }}" action="{{ route('laporan.destroy', $row->id_laporan) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $row->id_laporan }}')" class="p-1.5 text-red-400 hover:bg-red-50 rounded-md transition text-xs">
                                        🗑️
                                    </button>
                                </form> @endif
                            </div>
                        </td>
                    </tr>
                   @empty
<tr>
    <td colspan="8" class="p-16 text-center">
        <div class="flex flex-col items-center justify-center space-y-3">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-2xl">📊</div>
            @if(auth()->user()->role == 'admin' && !request('id_kube'))
                <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Silahkan pilih salah satu KUBE di atas</p>
                <p class="text-[10px] text-gray-400 italic">Data laporan akan muncul setelah kelompok dipilih.</p>
            @else
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest italic">Belum ada data laporan untuk kelompok ini.</p>
            @endif
        </div>
    </td>
</tr>
@endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}

<div id="modal-tambah-lk" tabindex="-1" class="hidden fixed inset-0 z-[100] flex justify-center items-center w-full h-full bg-slate-900/40 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-[420px] bg-white rounded-[2rem] shadow-2xl overflow-hidden">
        <div class="px-6 py-3 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight">Tambah Laporan Keuangan</h3>
            <button onclick="document.getElementById('modal-tambah-lk').classList.add('hidden')" class="w-7 h-7 flex items-center justify-center rounded-full bg-white shadow-sm border border-slate-100 text-slate-400 hover:text-red-500 transition-all text-xs">✕</button>
        </div>

        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-3">
            @csrf
          <div class="space-y-1">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelompok KUBE</label>
              
                <div class="flex items-center justify-between w-full bg-slate-100 border border-slate-200/50 rounded-xl px-4 py-2 mt-1">
                    <div class="flex flex-col">
                        <span class="text-[11px] font-bold text-slate-700 uppercase">
                            {{ $kubeMilikSaya->nama_kube ?? 'KUBE Tidak Ditemukan' }}
                        </span>
                        <span class="text-[8px] text-slate-500">ID: {{ $kubeMilikSaya->id_kube ?? '-' }}</span>
                    </div>
                    <span class="text-[9px] bg-slate-200 text-slate-500 px-2 py-0.5 rounded-md font-black">TERKUNCI</span>
                </div>

                <input type="hidden" name="id_persetujuan" value="{{ $kubeMilikSaya->id_kube ?? '' }}">
            </div>
            <div class="space-y-1">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Cluster</label>
                <select name="id_cluster" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-sky-500/20" required>
                    <option value="">-- Pilih Cluster --</option>
                    @foreach($clusters as $c) 
                        <option value="{{ $c->id_cluster }}">{{ $c->nama_cluster }}</option> 
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="p-2.5 bg-emerald-50/40 rounded-2xl border border-emerald-100/50">
                    <label class="text-[8px] font-black text-emerald-600 uppercase block mb-1 text-center">Omset (Rp)</label>
                    <input type="number" name="omset_pendapatan" placeholder="0" class="w-full bg-transparent border-none text-xs font-black text-emerald-700 text-center focus:ring-0 p-0" required>
                </div>
                <div class="p-2.5 bg-rose-50/40 rounded-2xl border border-rose-100/50">
                    <label class="text-[8px] font-black text-rose-600 uppercase block mb-1 text-center">Pengeluaran (Rp)</label>
                    <input type="number" name="total_pengeluaran" placeholder="0" class="w-full bg-transparent border-none text-xs font-black text-rose-700 text-center focus:ring-0 p-0" required>
                </div>
            </div>

            <div class="bg-slate-50/50 p-2.5 rounded-2xl flex items-center gap-3 border border-slate-100">
                <div class="flex-1 flex items-center gap-1.5 text-slate-500">
                    <span class="text-xs">📅</span>
                    <input type="date" name="tanggal_laporan" value="{{ date('Y-m-d') }}" class="w-full bg-transparent border-none p-0 text-[10px] font-bold text-slate-600 focus:ring-0">
                </div>
                <div class="h-4 w-px bg-slate-200"></div>
                <div class="flex-1">
                    <select name="periode_bulan" class="w-full bg-transparent border-none p-0 text-[10px] font-bold text-slate-600 focus:ring-0">
                        @for($m=1;$m<=12;$m++) <option value="{{$m}}" {{date('n')==$m?'selected':''}}>{{date('M',mktime(0,0,0,$m,10))}}</option> @endfor
                    </select>
                </div>
                <div class="h-4 w-px bg-slate-200"></div>
                <div class="flex-1">
                    <input type="number" name="periode_tahun" value="{{date('Y')}}" class="w-full bg-transparent border-none p-0 text-[10px] font-bold text-slate-600 focus:ring-0 text-center">
                </div>
            </div>

            <div class="space-y-1">
                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Keterangan</label>
                <textarea name="keterangan" rows="2" placeholder="Catatan singkat..." class="w-full bg-slate-50 border-none rounded-xl text-[10px] font-medium text-slate-600 placeholder:text-slate-300 focus:ring-2 focus:ring-sky-500/20 resize-none p-2.5"></textarea>
            </div>

            <div class="relative group border-2 border-dashed border-slate-100 hover:border-sky-300 rounded-xl px-4 py-2 flex items-center gap-3 transition-all">
                <input type="file" name="lampiran_keuangan" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div class="text-lg text-sky-500">📂</div>
                <div class="text-left leading-tight">
                    <p class="text-[10px] font-bold text-slate-500 group-hover:text-sky-600 truncate max-w-[250px]">Klik untuk unggah Bukti Transaksi</p>
                    <p class="text-[8px] text-slate-300 uppercase font-bold tracking-tight">JPG, PNG, PDF, Excel (Max 5MB)</p>
                </div>
            </div>

            <button type="submit" class="w-full h-11 bg-sky-600 text-white font-black rounded-xl shadow-lg shadow-sky-100 hover:bg-sky-700 transition-all text-[10px] uppercase tracking-[0.2em]">
                Simpan Laporan
            </button>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modal-edit-lk" tabindex="-1" class="hidden fixed inset-0 z-[100] flex justify-center items-center w-full h-full bg-black/40 backdrop-blur-[2px] p-4">
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="px-5 py-4 border-b flex justify-between items-center bg-amber-50/20">
            <h3 class="text-sm font-bold text-gray-800">EDIT LAPORAN</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition text-sm">✕</button>
        </div>
        <form id="form-edit-lk" method="POST" enctype="multipart/form-data" class="p-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-3 text-xs">
               <div class="col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase block">KUBE</label>
                  
                    <div class="flex items-center justify-between w-full bg-slate-100 border border-slate-200/50 rounded-xl px-4 py-2 mt-1">
                        <div class="flex flex-col">
                            <span class="text-[11px] font-bold text-slate-700 uppercase">
                                {{ $kubeMilikSaya->nama_kube ?? 'KUBE Tidak Ditemukan' }}
                            </span>
                            <span class="text-[8px] text-slate-500">ID: {{ $kubeMilikSaya->id_kube ?? '-' }}</span>
                        </div>
                        <span class="text-[9px] bg-slate-200 text-slate-500 px-2 py-0.5 rounded-md font-black">TERKUNCI</span>
                    </div>

                    <input type="hidden" name="id_persetujuan" id="edit-kube-hidden" value="{{ $kubeMilikSaya->id_kube ?? '' }}">
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase block">Cluster</label>
                    <select name="id_cluster" id="edit-cluster" class="w-full border-gray-100 rounded-lg p-2 bg-gray-50 font-bold" required>
                        @foreach($clusters as $c) <option value="{{$c->id_cluster}}">{{$c->nama_cluster}}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-emerald-600 uppercase block">Omset</label>
                    <input type="number" name="omset_pendapatan" id="edit-omset" class="w-full border-emerald-100 rounded-lg p-2 bg-emerald-50/30 font-bold text-emerald-700" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-red-600 uppercase block">Pengeluaran</label>
                    <input type="number" name="total_pengeluaran" id="edit-pengeluaran" class="w-full border-red-100 rounded-lg p-2 bg-red-50/30 font-bold text-red-700" required>
                </div>
                <div class="col-span-2 grid grid-cols-3 gap-2 py-2">
                    <input type="date" name="tanggal_laporan" id="edit-tgl-lapor" class="border-gray-100 rounded-lg p-1.5 font-bold text-[11px] bg-gray-50">
                    <select name="periode_bulan" id="edit-bulan" class="border-gray-100 rounded-lg p-1.5 font-bold text-[11px] bg-gray-50">
                        @for($m=1;$m<=12;$m++) <option value="{{$m}}">{{date('M',mktime(0,0,0,$m,10))}}</option> @endfor
                    </select>
                    <input type="number" name="periode_tahun" id="edit-tahun" class="border-gray-100 rounded-lg p-1.5 font-bold text-[11px] bg-gray-50">
                </div>
                <div class="col-span-2">
                    <textarea name="keterangan" id="edit-ket" rows="2" class="w-full border-gray-100 rounded-lg p-2 bg-gray-50"></textarea>
                </div>
               <div class="col-span-2">
    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Lampiran Baru</label>

    <div class="relative group border-2 border-dashed border-slate-100 hover:border-amber-300 rounded-xl px-4 py-2 flex items-center gap-3 transition-all mt-1">
    <input type="file" name="lampiran_keuangan" id="edit-file-upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
    
    <div class="text-amber-500">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
    </div>

    <div class="text-left leading-tight">
        <p id="edit-file-name" class="text-[10px] font-bold text-slate-500 group-hover:text-amber-600 truncate max-w-[200px]">
            Klik untuk ganti bukti transaksi
        </p>
        <p class="text-[8px] text-slate-300 uppercase font-bold tracking-tight">JPG, PNG, PDF, EXCEL (Max 5MB)</p>
    </div>
</div>
</div>
            </div>
            <button type="submit" class="mt-5 w-full py-2.5 bg-amber-500 text-white font-bold rounded-lg shadow hover:bg-amber-600 transition text-xs uppercase">Perbarui</button>
        </form>
    </div>
</div>

{{-- MODAL DETAIL --}}
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showDetail(btn) {
         const idLaporan = btn.getAttribute('data-id');
        const kube = btn.getAttribute('data-kube');
        const cluster = btn.getAttribute('data-cluster');
        const periode = btn.getAttribute('data-periode');
        const tgl = btn.getAttribute('data-tgl');
        const omset = btn.getAttribute('data-omset');
        const pengeluaran = btn.getAttribute('data-pengeluaran');
        const laba = btn.getAttribute('data-laba');
        const ket = btn.getAttribute('data-ket');

    
        document.getElementById('det-kube').innerText = kube;
        document.getElementById('det-cluster').innerText = cluster;
        document.getElementById('det-periode').innerText = "Periode " + periode;
        document.getElementById('det-omset').innerText = omset;
        document.getElementById('det-pengeluaran').innerText = pengeluaran;
        document.getElementById('det-laba').innerText = laba;
        document.getElementById('det-ket').innerText = ket;
        document.getElementById('det-tgl').innerText = tgl;

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

    function openEditModal(btn) {
        const id = btn.getAttribute('data-id');
        const file = btn.getAttribute('data-file');
        document.getElementById('form-edit-lk').action = `/laporan-keuangan/${id}`;
        document.getElementById('edit-kube').value = btn.getAttribute('data-kube');
        document.getElementById('edit-cluster').value = btn.getAttribute('data-cluster');
        document.getElementById('edit-omset').value = btn.getAttribute('data-omset');
        document.getElementById('edit-pengeluaran').value = btn.getAttribute('data-pengeluaran');
        document.getElementById('edit-tgl-lapor').value = btn.getAttribute('data-tgl');
        document.getElementById('edit-bulan').value = btn.getAttribute('data-bulan');
        document.getElementById('edit-tahun').value = btn.getAttribute('data-tahun');
        document.getElementById('edit-ket').value = btn.getAttribute('data-ket');
        document.getElementById('modal-edit-lk').classList.remove('hidden');
        document.getElementById('modal-edit-lk').classList.add('flex');
    const fileInfo = document.getElementById('edit-file-info');
    fileInfo.innerText = file ? `File saat ini: ${file}` : "Belum ada lampiran.";
    }

    function closeEditModal() {
        document.getElementById('modal-edit-lk').classList.add('hidden');
        document.getElementById('modal-edit-lk').classList.remove('flex');
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
            customClass: { popup: 'rounded-[2.5rem]', confirmButton: 'rounded-xl font-bold px-6 py-3', cancelButton: 'rounded-xl font-bold px-6 py-3' }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        })
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

    function printFormalReport() {
        const kube = document.getElementById('det-kube').innerText;
        const cluster = document.getElementById('det-cluster').innerText;
        const periode = document.getElementById('det-periode').innerText;
        const tglLapor = document.getElementById('det-tgl').innerText;
        const omset = document.getElementById('det-omset').innerText;
        const pengeluaran = document.getElementById('det-pengeluaran').innerText;
        const laba = document.getElementById('det-laba').innerText;
        const keterangan = document.getElementById('det-ket').innerText;

        let iframe = document.getElementById('printFrame');
        if (!iframe) {
            iframe = document.createElement('iframe');
            iframe.id = 'printFrame';
            iframe.style.display = 'none';
            document.body.appendChild(iframe);
        }

        const doc = iframe.contentWindow.document;
        doc.open();
        doc.write(`
            <html>
            <head>
                <title>Laporan Keuangan - ${kube}</title>
                <style>
                    body { font-family: 'Arial', sans-serif; padding: 40px; color: #333; line-height: 1.6; }
                    .header { text-align: center; border-bottom: 3px double #000; padding-bottom: 20px; margin-bottom: 30px; }
                    .header h2 { margin: 0; text-transform: uppercase; }
                    .header p { margin: 5px 0; font-size: 12px; }
                    .title { text-align: center; text-decoration: underline; margin: 30px 0 20px; font-weight: bold; font-size: 16px; }
                    .info-table { width: 100%; margin-bottom: 30px; border-collapse: collapse; }
                    .info-table td { padding: 8px 0; vertical-align: top; }
                    .info-table td.label { width: 150px; font-weight: bold; }
                    .data-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    .data-table th, .data-table td { border: 1px solid #000; padding: 12px; text-align: left; }
                    .data-table th { background-color: #f2f2f2; }
                    .footer-container { margin-top: 50px; display: flex; justify-content: space-between; }
                    .signature-box { width: 250px; text-align: center; }
                    .signature-space { height: 80px; }
                    @media print { .no-print { display: none; } }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>DINAS SOSIAL KABUPATEN CILACAP</h2>
                    <p>Program Kelompok Usaha Bersama (KUBE) - Cluster ${cluster}</p>
                    <p>Jl. Bromo Timur No.13, Sidakaya Dua, Sidakaya, Kec. Cilacap Sel., Kabupaten Cilacap, Jawa Tengah 53223</p>
                </div>
                <div class="title">LAPORAN REKAPITULASI KEUANGAN BULANAN</div>
                <table class="info-table">
                    <tr><td class="label">Nama KUBE</td><td>: ${kube}</td></tr>
                    <tr><td class="label">Cluster Usaha</td><td>: ${cluster}</td></tr>
                    <tr><td class="label">Periode</td><td>: ${periode}</td></tr>
                    <tr><td class="label">Tanggal Input</td><td>: ${tglLapor}</td></tr>
                </table>
                <table class="data-table">
                    <thead>
                        <tr><th>Deskripsi</th><th>Nilai (Rupiah)</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Total Omset / Pendapatan</td><td>${omset}</td></tr>
                        <tr><td>Total Pengeluaran Operasional</td><td>${pengeluaran}</td></tr>
                        <tr style="font-weight: bold;"><td>Laba Bersih</td><td>${laba}</td></tr>
                    </tbody>
                </table>
                <p><strong>Catatan/Keterangan:</strong><br>${keterangan}</p>
                <div class="footer-container">
                    <div class="signature-box">
                        <p>&nbsp;</p>
                        <p>Ketua KUBE,</p>
                        <div class="signature-space"></div>
                        <p><strong>( ____________________ )</strong></p>
                    </div>
                    <div class="signature-box">
                        <p>Cilacap, ${new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'})}</p>
                        <p>Pendamping KUBE,</p>
                        <div class="signature-space"></div>
                        <p><strong>( ____________________ )</strong></p>
                    </div>
                </div>
            </body>
            </html>
        `);
        doc.close();
        setTimeout(() => {
            iframe.contentWindow.focus();
            iframe.contentWindow.print();
        }, 500);
    }

document.getElementById('file-upload').addEventListener('change', function(e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : "Klik atau seret file ke sini";
    const label = document.getElementById('file-name');
    label.innerText = fileName;
    label.classList.add('text-sky-600');

document.getElementById('edit-file-upload').addEventListener('change', function(e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : "Klik untuk ganti bukti transaksi";
    document.getElementById('edit-file-name').innerText = fileName;
});
});
function filterKubeInstant() {
    const input = document.getElementById('search-kube-input');
    const filter = input.value.toLowerCase().trim();
    const clearBtn = document.getElementById('clear-search-btn');
    const cards = document.getElementsByClassName('kube-item-card');
    const emptyMsg = document.getElementById('search-empty-msg');
    
    let adaYangCocok = false;

    if (filter.length > 0) {
        clearBtn.classList.remove('hidden');
    } else {
        clearBtn.classList.add('hidden');
    }

    for (let i = 0; i < cards.length; i++) {
        const namaKube = cards[i].getAttribute('data-nama');
        
        if (namaKube && namaKube.includes(filter)) {
            cards[i].style.display = "";
            adaYangCocok = true;
        } else {
            cards[i].style.display = "none"; 
        }
    }

    if (emptyMsg) {
        if (!adaYangCocok && cards.length > 0) {
            emptyMsg.classList.remove('hidden');
        } else {
            emptyMsg.classList.add('hidden');
        }
    }
}

function resetSearchKube() {
    const input = document.getElementById('search-kube-input');
    input.value = '';
    filterKubeInstant();
    input.focus();
}
</script>
  <iframe id="print-frame" class="hidden" style="display:none;"></iframe>
@endsection