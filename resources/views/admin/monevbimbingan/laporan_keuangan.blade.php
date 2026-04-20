@extends('admin.layout')

@section('content')
<div class="p-4 bg-gray-50 min-h-screen font-sans">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-5 gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800 tracking-tight">Keuangan KUBE</h1>
            <p class="text-[11px] text-gray-500">Monitoring omset dan laba bulanan KUBE.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
          <a href="#"
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </svg>
        Ekspor PDF
    </a>

    {{-- Ekspor Excel --}}
    <a href="#"
        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6M3 17V7a2 2 0 012-2h14a2 2 0 012 2v10"/>
        </svg>
        Ekspor Excel
    </a>
      <button data-modal-target="modal-tambah-lk" data-modal-toggle="modal-tambah-lk"
        class="flex items-center gap-2 bg-sky-700 hover:bg-sky-900 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all">
        + Tambah Laporan
    </button>

        </div>
    </div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
    {{-- Card 1: Omset --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-lg shadow-gray-200/50 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 ease-in-out flex items-center gap-4 group cursor-default">
        <div class="w-12 h-12 bg-orange-50 rounded-2xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform duration-300">💰</div>
        <div class="min-w-0">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Total Omset</p>
            <h3 class="text-xl font-black text-gray-800 tracking-tight">Rp {{ number_format($totalOmset, 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- Card 2: Laba --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-lg shadow-gray-200/50 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 ease-in-out flex items-center gap-4 group cursor-default">
        <div class="w-12 h-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform duration-300">📈</div>
        <div class="min-w-0">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Laba Bersih</p>
            <h3 class="text-xl font-black text-emerald-600 tracking-tight">Rp {{ number_format($totalLaba, 0, ',', '.') }}</h3>
        </div>
    </div>

    {{-- Card 3: Perkembangan --}}
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-lg shadow-gray-200/50 hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 ease-in-out flex items-center gap-4 group cursor-default">
        <div class="w-12 h-12 bg-sky-50 rounded-2xl flex items-center justify-center text-xl group-hover:scale-110 transition-transform duration-300">📊</div>
        <div class="min-w-0">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-0.5">Perkembangan</p>
            <h3 class="text-xl font-black text-sky-600 tracking-tight">{{ $perkembangan }}</h3>
        </div>
    </div>
</div>

    {{-- Table Section --}}
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
                        <td class="px-4 py-2.5 text-center">
                            @if($row->lampiran_keuangan)
                                <a href="{{ asset('uploads/keuangan/'.$row->lampiran_keuangan) }}" target="_blank" class="text-sky-400 hover:text-sky-600 text-xs">📂</a>
                            @else
                                <span class="text-gray-300 text-[10px]">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-2.5 text-center">
                            <div class="flex justify-center gap-1.5">
                                {{-- BUTTON DETAIL --}}
                                <button type="button" 
                                    onclick="showDetail(this)"
                                    data-kube="{{ $dataKube->nama_kube ?? 'N/A' }}"
                                    data-cluster="{{ $row->cluster->nama_cluster ?? '-' }}"
                                    data-periode="{{ date('F Y', mktime(0, 0, 0, $row->periode_bulan, 10)) }}"
                                    data-tgl="{{ \Carbon\Carbon::parse($row->tanggal_laporan)->translatedFormat('d F Y') }}"
                                    data-omset="Rp {{ number_format($row->omset_pendapatan, 0, ',', '.') }}"
                                    data-pengeluaran="Rp {{ number_format($row->total_pengeluaran, 0, ',', '.') }}"
                                    data-laba="Rp {{ number_format($row->laba_bersih, 0, ',', '.') }}"
                                    data-ket="{{ $row->keterangan ?? '-' }}"
                                    class="p-1.5 text-sky-400 hover:bg-sky-50 rounded-md transition text-xs z-10">👁️</button>
                                
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
                                
                                <form id="delete-form-{{ $row->id_laporan }}" action="{{ route('laporan.destroy', $row->id_laporan) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $row->id_laporan }}')" class="p-1.5 text-red-400 hover:bg-red-50 rounded-md transition text-xs">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="p-12 text-center text-gray-400 text-xs italic">Data belum tersedia.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="modal-tambah-lk" tabindex="-1" class="hidden fixed inset-0 z-[100] flex justify-center items-center w-full h-full bg-slate-900/40 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-[420px] bg-white rounded-[2rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        
        {{-- Header --}}
        <div class="px-6 py-3 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-tight">Tambah Laporan Keuangan </h3>
            <button data-modal-toggle="modal-tambah-lk" class="w-7 h-7 flex items-center justify-center rounded-full bg-white shadow-sm border border-slate-100 text-slate-400 hover:text-red-500 transition-all text-xs">✕</button>
        </div>

        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="p-5 space-y-3">
            @csrf
        
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelompok KUBE</label>
                    <select name="id_persetujuan" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-sky-500/20" required>
                        <option value="">-- Pilih --</option>
                        @foreach($kubeDisetujui as $k) <option value="{{ $k->id_pengajuan_kube }}">{{ $k->nama_tampilan }}</option> @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Cluster</label>
                    <select name="id_cluster" class="w-full bg-slate-50 border-none rounded-xl px-3 py-2 text-[10px] font-bold text-slate-700 focus:ring-2 focus:ring-sky-500/20" required>
                        <option value="">-- Pilih --</option>
                        @foreach($clusters as $c) <option value="{{ $c->id_cluster }}">{{ $c->nama_cluster }}</option> @endforeach
                    </select>
                </div>
            </div>


            <div class="grid grid-cols-2 gap-3">
                <div class="p-2.5 bg-emerald-50/40 rounded-2xl border border-emerald-100/50">
                    <label class="text-[8px] font-black text-emerald-600 uppercase block mb-1 text-center">Omset Pendapatan (Rp)</label>
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
                <input type="file" name="lampiran_keuangan" id="file-upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div class="text-lg text-sky-500">📂</div>
                <div class="text-left leading-tight">
                    <p id="file-name" class="text-[10px] font-bold text-slate-500 group-hover:text-sky-600 truncate max-w-[250px]">Klik untuk unggah Bukti Transaksi</p>
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
                    <select name="id_persetujuan" id="edit-kube" class="w-full border-gray-100 rounded-lg p-2 bg-gray-50 font-bold" required>
                        @foreach($kubeDisetujui as $k) <option value="{{$k->id_pengajuan_kube}}">{{$k->nama_tampilan}}</option> @endforeach
                    </select>
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
    {{-- Container Wrapper --}}
    <div class="relative group border-2 border-dashed border-slate-100 hover:border-amber-300 rounded-xl px-4 py-2 flex items-center gap-3 transition-all mt-1">
        <input type="file" name="lampiran_keuangan" id="edit-file-upload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
        <div class="text-lg text-amber-500">📂</div>
        <div class="text-left leading-tight">
            {{-- Label ini akan berubah teksnya lewat JS saat file dipilih --}}
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
<div id="modal-detail-lk" class="hidden fixed inset-0 z-[150] flex justify-center items-center w-full h-full bg-black/50 backdrop-blur-[2px] p-4">
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p id="det-cluster" class="text-[9px] font-bold text-sky-500 uppercase tracking-widest mb-0.5"></p>
                    <h4 id="det-kube" class="text-base font-black text-gray-900 tracking-tight leading-tight"></h4>
                    <p id="det-periode" class="text-[10px] text-gray-400 font-bold uppercase"></p>
                    <p id="det-tgl" class="hidden"></p>
                </div>
                <button type="button" onclick="closeDetail()" class="text-gray-300 hover:text-gray-600 transition text-xl p-1">✕</button>
            </div>
            
            <div class="space-y-3 py-4 border-y border-gray-50 mb-4">
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Total Omset</span>
                    <span id="det-omset" class="text-xs font-bold text-gray-700"></span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">Pengeluaran</span>
                    <span id="det-pengeluaran" class="text-xs font-bold text-red-500"></span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t border-gray-50">
                    <span class="text-[10px] font-black text-gray-900 uppercase">Laba Bersih</span>
                    <span id="det-laba" class="text-sm font-black text-emerald-500"></span>
                </div>
            </div>

            <div class="mb-6 bg-gray-50 p-3 rounded-xl border border-gray-100">
                <p class="text-[9px] font-bold text-gray-400 uppercase mb-1 text-center tracking-tighter">Catatan Tambahan</p>
                <p id="det-ket" class="text-[11px] text-gray-500 leading-relaxed text-center italic"></p>
            </div>

            <div class="flex flex-col gap-2">
                <button type="button" onclick="printFormalReport()" class="w-full py-2.5 bg-sky-700 text-white font-bold rounded-xl text-[10px] tracking-widest hover:bg-sky-900 transition-all flex items-center justify-center gap-2 shadow-lg">
                    🖨️ CETAK PDF
                </button>
                <button type="button" onclick="closeDetail()" class="w-full py-2 bg-transparent text-gray-400 font-bold rounded-lg text-[10px] hover:text-gray-600 transition">
                    KEMBALI
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showDetail(btn) {
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
        // Tampilkan info file lama
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

    // Listener untuk Modal Edit
document.getElementById('edit-file-upload').addEventListener('change', function(e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : "Klik untuk ganti bukti transaksi";
    document.getElementById('edit-file-name').innerText = fileName;
});
});
</script>
@endsection