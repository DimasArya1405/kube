@extends('admin.layout')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
   {{-- Header Section --}}
<div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Laporan Keuangan KUBE</h1>
        <p class="text-gray-500 mt-1">Kelola data omset dan laba berdasarkan KUBE yang disetujui.</p>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        {{-- Button Export Excel --}}
        <a href="#" class="flex items-center gap-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 px-5 py-2.5 rounded-xl font-bold border border-emerald-100 transition shadow-sm">
            <span>📊</span> Export to Excel
        </a>
        
        {{-- Button Export PDF --}}
        <a href="#" class="flex items-center gap-2 bg-red-50 text-red-600 hover:bg-red-100 px-5 py-2.5 rounded-xl font-bold border border-red-100 transition shadow-sm">
            <span>📕</span> Export toPDF
        </a>

        {{-- Button Tambah Laporan --}}
        <button data-modal-target="modal-tambah-lk" data-modal-toggle="modal-tambah-lk" class="bg-sky-600 hover:bg-sky-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-sky-100 transition">
            + Tambah Laporan
        </button>
    </div>
</div>
    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-100 rounded-2xl flex items-center justify-center text-xl">💰</div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase">Total Omset</p>
                <h3 class="text-xl font-extrabold text-gray-800">Rp {{ number_format($totalOmset, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-xl">📈</div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase">Laba Bersih</p>
                <h3 class="text-xl font-extrabold text-emerald-600">Rp {{ number_format($totalLaba, 0, ',', '.') }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-sky-100 rounded-2xl flex items-center justify-center text-xl">📊</div>
            <div>
                <p class="text-xs text-gray-400 font-bold uppercase">Perkembangan</p>
                <h3 class="text-xl font-extrabold text-sky-600">{{ $perkembangan }}</h3>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div class="bg-white border border-gray-200 rounded-[2.5rem] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-gray-50/50 border-b">
                    <tr>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase">Tanggal Laporan</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase">KUBE & Cluster</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase text-center">Periode</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase text-center pr-6">Omset</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase text-center pr-6">Laba Bersih</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase text-center">Progres</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase text-center">Berkas</th>
                        <th class="p-6 text-xs font-bold text-gray-500 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($laporan as $row)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-6 text-xs font-medium text-gray-500">
                            {{ \Carbon\Carbon::parse($row->tanggal_laporan)->translatedFormat('d/m/Y') }}
                        </td>
                        <td class="p-6">
                            @php
                                $dataKube = \DB::table('pengajuan_kube')
                                    ->join('kube', 'pengajuan_kube.id_kube', '=', 'kube.id_kube')
                                    ->where('pengajuan_kube.id_pengajuan_kube', $row->id_persetujuan)
                                    ->first();
                            @endphp
                            <div class="font-bold text-gray-800">{{ $dataKube->nama_kube ?? 'N/A' }}</div>
                            <div class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ $row->cluster->nama_cluster ?? '-' }}</div>
                        </td>
                        <td class="p-6 text-center">
                            <span class="px-3 py-1 bg-gray-100 rounded-lg text-xs font-bold text-gray-600">
                                {{ date('M Y', mktime(0, 0, 0, $row->periode_bulan, 10)) }}
                            </span>
                        </td>
                        <td class="p-6 text-right font-semibold text-gray-700 pr-6">
                            Rp {{ number_format($row->omset_pendapatan, 0, ',', '.') }}
                        </td>
                        <td class="p-6 text-right font-bold text-emerald-600 pr-6">
                            Rp {{ number_format($row->laba_bersih, 0, ',', '.') }}
                        </td>
                        <td class="p-6 text-center">
                            @if($row->progres_keuangan == 'Meningkat')
                                <span class="text-emerald-500 font-bold text-[10px] uppercase">▲ Naik</span>
                            @elseif($row->progres_keuangan == 'Menurun')
                                <span class="text-red-500 font-bold text-[10px] uppercase">▼ Turun</span>
                            @else
                                <span class="text-sky-500 font-bold text-[10px] uppercase">● Tetap</span>
                            @endif
                        </td>
                        <td class="p-6 text-center">
                            @if($row->lampiran_keuangan)
                                <a href="{{ asset('uploads/keuangan/'.$row->lampiran_keuangan) }}" target="_blank" class="bg-white border border-gray-100 hover:bg-sky-50 text-sky-500 p-2 rounded-lg transition inline-block shadow-sm">📂</a>
                            @else
                                <span class="text-gray-300 text-[10px] italic">Nihil</span>
                            @endif
                        </td>
                        <td class="p-6 text-center">
                            <div class="flex justify-center gap-2">
                                <button onclick="showDetail(this)"
                                    data-kube="{{ $dataKube->nama_kube ?? 'N/A' }}"
                                    data-cluster="{{ $row->cluster->nama_cluster ?? '-' }}"
                                    data-periode="{{ date('F Y', mktime(0, 0, 0, $row->periode_bulan, 10)) }}"
                                    data-tgl="{{ \Carbon\Carbon::parse($row->tanggal_laporan)->translatedFormat('d F Y') }}"
                                    data-omset="Rp {{ number_format($row->omset_pendapatan, 0, ',', '.') }}"
                                    data-pengeluaran="Rp {{ number_format($row->total_pengeluaran, 0, ',', '.') }}"
                                    data-laba="Rp {{ number_format($row->laba_bersih, 0, ',', '.') }}"
                                    data-ket="{{ $row->keterangan ?? '-' }}"
                                    class="p-2 text-sky-400 hover:bg-sky-50 rounded-lg transition">👁️</button>
                                
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
                                    class="p-2 text-amber-500 hover:bg-amber-50 rounded-lg transition">✏️</button>
                                
                                <form id="delete-form-{{ $row->id_laporan }}" action="{{ route('laporan.destroy', $row->id_laporan) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $row->id_laporan }}')" class="p-2 text-red-400 hover:bg-red-50 rounded-lg transition">
                                        🗑️
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="p-20 text-center text-gray-400 italic">Data belum tersedia.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="modal-tambah-lk" tabindex="-1" class="hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-black/60 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-2xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-sky-100">
        <div class="p-6 border-b flex justify-between items-center bg-sky-50/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-sky-100 rounded-xl flex items-center justify-center text-lg">+</div>
                <div>
                    <h3 class="text-xl font-extrabold text-gray-800">Input Laporan Baru</h3>
                    <p class="text-[10px] font-bold text-sky-600 uppercase tracking-widest">Pencatatan Keuangan KUBE</p>
                </div>
            </div>
            <button data-modal-toggle="modal-tambah-lk" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition">✕</button>
        </div>
        <form action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2 md:col-span-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block ml-1">Kelompok KUBE</label>
                    <select name="id_persetujuan" class="w-full border-gray-100 rounded-2xl p-3.5 bg-gray-50 font-bold focus:ring-2 focus:ring-sky-500 transition" required>
                        <option value="">-- Pilih KUBE --</option>
                        @foreach($kubeDisetujui as $k) <option value="{{ $k->id_pengajuan_kube }}">{{ $k->nama_tampilan }}</option> @endforeach
                    </select>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block ml-1">Cluster Usaha</label>
                    <select name="id_cluster" class="w-full border-gray-100 rounded-2xl p-3.5 bg-gray-50 font-bold focus:ring-2 focus:ring-sky-500 transition" required>
                        <option value="">-- Pilih Cluster --</option>
                        @foreach($clusters as $c) <option value="{{ $c->id_cluster }}">{{ $c->nama_cluster }}</option> @endforeach
                    </select>
                </div>
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-emerald-600 uppercase mb-2 block ml-1 text-center">Omset (Rp)</label>
                    <input type="number" name="omset_pendapatan" class="w-full border-emerald-100 rounded-2xl p-4 bg-emerald-50/50 font-black text-emerald-700 focus:ring-2 focus:ring-emerald-500 transition text-center" required>
                </div>
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-red-600 uppercase mb-2 block ml-1 text-center">Pengeluaran (Rp)</label>
                    <input type="number" name="total_pengeluaran" class="w-full border-red-100 rounded-2xl p-4 bg-red-50/50 font-black text-red-700 focus:ring-2 focus:ring-red-500 transition text-center" required>
                </div>
                <div class="col-span-2">
                    <div class="grid grid-cols-3 gap-4 bg-gray-50 p-5 rounded-[2rem] border border-gray-100">
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Tgl Laporan</label>
                            <input type="date" name="tanggal_laporan" value="{{ date('Y-m-d') }}" class="w-full border-none bg-white rounded-xl p-2 font-bold text-sm shadow-sm" required>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Bulan</label>
                            <select name="periode_bulan" class="w-full border-none bg-white rounded-xl p-2 font-bold text-sm shadow-sm">
                                @for($m=1;$m<=12;$m++) <option value="{{$m}}" {{date('n')==$m?'selected':''}}>{{date('F',mktime(0,0,0,$m,10))}}</option> @endfor
                            </select>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Tahun</label>
                            <input type="number" name="periode_tahun" value="{{date('Y')}}" class="w-full border-none bg-white rounded-xl p-2 font-bold text-sm shadow-sm">
                        </div>
                    </div>
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block ml-1">Catatan Laporan</label>
                    <textarea name="keterangan" rows="2" class="w-full border-gray-100 rounded-2xl p-4 bg-gray-50 font-medium focus:ring-2 focus:ring-sky-500 transition text-sm"></textarea>
                </div>
                <div class="col-span-2">
                    <div class="border-2 border-dashed border-gray-100 rounded-2xl p-4 flex flex-col items-center justify-center bg-gray-50/30">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mb-2">Unggah Bukti Transaksi</label>
                        <input type="file" name="lampiran_keuangan" class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-sky-100 file:text-sky-700 cursor-pointer">
                    </div>
                </div>
            </div>
            <button type="submit" class="mt-8 w-full py-4 bg-sky-600 text-white font-extrabold rounded-2xl shadow-lg hover:bg-sky-700 hover:-translate-y-1 transition-all uppercase tracking-widest text-xs">Simpan Laporan</button>
        </form>
    </div>
</div>

{{-- MODAL EDIT --}}
<div id="modal-edit-lk" tabindex="-1" class="hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-black/60 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-2xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden border border-amber-100">
        <div class="p-6 border-b flex justify-between items-center bg-amber-50/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center text-lg">✏️</div>
                <div>
                    <h3 class="text-xl font-extrabold text-gray-800">Perbarui Laporan</h3>
                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest">Perbarui Data Laporan</p>
                </div>
            </div>
            <button onclick="closeEditModal()" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400 transition">✕</button>
        </div>
        <form id="form-edit-lk" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-5">
                <div class="col-span-2 md:col-span-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block ml-1">KUBE</label>
                    <select name="id_persetujuan" id="edit-kube" class="w-full border-gray-100 rounded-2xl p-3.5 bg-gray-50 font-bold focus:ring-2 focus:ring-amber-500 transition" required>
                        @foreach($kubeDisetujui as $k) <option value="{{$k->id_pengajuan_kube}}">{{$k->nama_tampilan}}</option> @endforeach
                    </select>
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block ml-1">Cluster</label>
                    <select name="id_cluster" id="edit-cluster" class="w-full border-gray-100 rounded-2xl p-3.5 bg-gray-50 font-bold focus:ring-2 focus:ring-amber-500 transition" required>
                        @foreach($clusters as $c) <option value="{{$c->id_cluster}}">{{$c->nama_cluster}}</option> @endforeach
                    </select>
                </div>
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-emerald-600 uppercase mb-2 block ml-1 text-center">Omset</label>
                    <input type="number" name="omset_pendapatan" id="edit-omset" class="w-full border-emerald-100 rounded-2xl p-4 bg-emerald-50/50 font-black text-emerald-700 focus:ring-2 focus:ring-emerald-500 transition text-center" required>
                </div>
                <div class="col-span-1">
                    <label class="text-[10px] font-bold text-red-600 uppercase mb-2 block ml-1 text-center">Pengeluaran</label>
                    <input type="number" name="total_pengeluaran" id="edit-pengeluaran" class="w-full border-red-100 rounded-2xl p-4 bg-red-50/50 font-black text-red-700 focus:ring-2 focus:ring-red-500 transition text-center" required>
                </div>
                <div class="col-span-2">
                    <div class="grid grid-cols-3 gap-4 bg-gray-50 p-5 rounded-[2rem] border border-gray-100">
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Tgl Lapor</label>
                            <input type="date" name="tanggal_laporan" id="edit-tgl-lapor" class="w-full border-none bg-white rounded-xl p-2 font-bold text-sm shadow-sm" required>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Bulan</label>
                            <select name="periode_bulan" id="edit-bulan" class="w-full border-none bg-white rounded-xl p-2 font-bold text-sm shadow-sm">
                                @for($m=1;$m<=12;$m++) <option value="{{$m}}">{{date('F',mktime(0,0,0,$m,10))}}</option> @endfor
                            </select>
                        </div>
                        <div>
                            <label class="text-[9px] font-bold text-gray-400 uppercase mb-1 block">Tahun</label>
                            <input type="number" name="periode_tahun" id="edit-tahun" class="w-full border-none bg-white rounded-xl p-2 font-bold text-sm shadow-sm">
                        </div>
                    </div>
                </div>
                <div class="col-span-2">
                    <label class="text-[10px] font-bold text-gray-400 uppercase mb-2 block ml-1">Keterangan</label>
                    <textarea name="keterangan" id="edit-ket" rows="2" class="w-full border-gray-100 rounded-2xl p-4 bg-gray-50 font-medium focus:ring-2 focus:ring-amber-500 transition text-sm"></textarea>
                </div>
                <div class="col-span-2">
                    <div class="border-2 border-dashed border-gray-100 rounded-2xl p-4 flex flex-col items-center justify-center bg-gray-50/30">
                        <label class="text-[10px] font-bold text-gray-400 uppercase mb-2">Ganti Berkas</label>
                        <input type="file" name="lampiran_keuangan" class="text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-amber-100 file:text-amber-700 cursor-pointer">
                    </div>
                </div>
            </div>
            <button type="submit" class="mt-8 w-full py-4 bg-amber-500 text-white font-extrabold rounded-2xl shadow-lg hover:bg-amber-600 hover:-translate-y-1 transition-all uppercase tracking-widest text-xs">Update Laporan</button>
        </form>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div id="modal-detail-lk" tabindex="-1" class="hidden fixed inset-0 z-[60] flex justify-center items-center w-full h-full bg-black/60 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-lg bg-white rounded-[2.5rem] shadow-2xl border border-gray-100 overflow-hidden">
        <div class="p-8 text-center">
            <p id="det-cluster" class="text-[10px] font-black text-sky-600 uppercase tracking-widest mb-2"></p>
            <h4 id="det-kube" class="text-2xl font-black text-gray-900 mb-1 leading-tight"></h4>
            <p id="det-periode" class="text-xs font-bold text-gray-400 mb-6"></p>
            <div class="grid grid-cols-2 gap-4 text-left bg-gray-50 p-6 rounded-3xl mb-6">
                <div><p class="text-[10px] font-bold text-gray-400 uppercase">Omset</p><p id="det-omset" class="font-bold text-gray-800 text-sm"></p></div>
                <div><p class="text-[10px] font-bold text-gray-400 uppercase">Laba</p><p id="det-laba" class="font-bold text-emerald-600 text-sm"></p></div>
                <div><p class="text-[10px] font-bold text-gray-400 uppercase">Pengeluaran</p><p id="det-pengeluaran" class="font-bold text-red-500 text-sm"></p></div>
                <div><p class="text-[10px] font-bold text-gray-400 uppercase">Tgl Input</p><p id="det-tgl" class="font-bold text-gray-800 text-sm"></p></div>
                <div class="col-span-2 pt-4 border-t border-gray-200 mt-2">
                    <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Catatan</p>
                    <p id="det-ket" class="text-xs text-gray-600 italic leading-relaxed"></p>
                </div>
            </div>
            <button onclick="closeDetail()" class="w-full py-4 bg-gray-900 text-white font-bold rounded-2xl text-[10px] tracking-widest hover:bg-black transition">TUTUP DETAIL</button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function openEditModal(btn) {
        const id = btn.getAttribute('data-id');
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
    }

    function closeEditModal() { document.getElementById('modal-edit-lk').classList.add('hidden'); }

    function showDetail(btn) {
        document.getElementById('det-kube').innerText = btn.getAttribute('data-kube');
        document.getElementById('det-cluster').innerText = btn.getAttribute('data-cluster');
        document.getElementById('det-periode').innerText = "Periode " + btn.getAttribute('data-periode');
        document.getElementById('det-tgl').innerText = btn.getAttribute('data-tgl');
        document.getElementById('det-omset').innerText = btn.getAttribute('data-omset');
        document.getElementById('det-pengeluaran').innerText = btn.getAttribute('data-pengeluaran');
        document.getElementById('det-laba').innerText = btn.getAttribute('data-laba');
        document.getElementById('det-ket').innerText = btn.getAttribute('data-ket');
        document.getElementById('modal-detail-lk').classList.remove('hidden');
    }

    function closeDetail() { document.getElementById('modal-detail-lk').classList.add('hidden'); }

    // SWEETALERT HAPUS
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

    // NOTIFIKASI SUKSES (TAMBAH/EDIT/HAPUS)
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000,
            customClass: { popup: 'rounded-[2.5rem]' }
        });
    @endif
</script>
@endsection