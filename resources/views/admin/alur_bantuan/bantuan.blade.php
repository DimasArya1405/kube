
@extends('admin.layout')

@section('title', 'Bantuan Kolaborasi - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Bantuan Kolaborasi</span>
@stop

@section('content')


<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Bantuan Kolaborasi</h2>
        <p class="text-gray-500 mt-1">Kelola data penyaluran bantuan dari mitra ke KUBE</p>
    </div>
    <div>
         <button data-modal-target="modal-tambah-bantuan" data-modal-toggle="modal-tambah-bantuan" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-md">
            Tambah Bantuan
        </button>
    </div>
</div>

{{--Card--}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow border">
        <p class="text-sm font-medium">Total Bantuan</p>
        <h3 class="text-2xl font-bold text-gray-800">{{ $bantuans->count() }}</h3>
    </div>
    <div class="bg-green-50 p-4 rounded-lg shadow border border-green-200">
        <p class="text-sm text-green-600">Kolaborasi Berjalan</p>
        <h3 class="text-4xl font-bold mt-1">{{ $bantuans->where('status', 'Berjalan')->count() }}</h3>
    </div>
    <div class="bg-red-50 p-4 rounded-lg shadow border border-red-200">
        <p class="text-sm text-red-600">Kolaborasi Selesai</p>
        <h3 class="text-2xl font-bold text-red-700">{{ $bantuans->where('status', 'Selesai')->count() }}</h3>
    </div>
</div>

{{-- INFO FILTER MITRA --}}
@if(isset($filterMitra))
<div class="mb-6 p-4 bg-indigo-50 border border-indigo-100 rounded-lg flex justify-between items-center shadow-sm">
    <div class="flex items-center gap-3">
        <div class="bg-indigo-500 text-white p-2 rounded-lg">
            <i data-lucide="filter" class="w-5 h-5"></i>
        </div>
        <div>
            <p class="text-indigo-800 text-xs font-bold uppercase tracking-wider">Menampilkan Data Untuk Mitra:</p>
            <h3 class="text-xl font-extrabold text-indigo-900">{{ $filterMitra->nama_mitra }}</h3>
        </div>
    </div>
    <a href="{{ route('bantuan.index') }}" class="flex items-center gap-2 bg-white border border-indigo-200 px-4 py-2 rounded-lg text-indigo-600 hover:bg-indigo-50 text-sm font-bold shadow-sm transition-all">
        <i data-lucide="refresh-cw" class="w-4 h-4"></i> Tampilkan Semua
    </a>
</div>
@endif


{{-- TOOLBAR --}}
<div class="flex flex-wrap items-center gap-3 mb-4">
    <div class="relative flex-1 min-w-200px">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <i data-lucide="search" class="h-4 w-4"></i>
        </span>
        <input type="text" id="searchInput" placeholder="Cari bantuan, mitra, atau KUBE..."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
    </div>

    <form action="{{ route('kolaborasi.index') }}" method="GET" class="flex items-center">
        @if(request('id_mitra'))
            <input type="hidden" name="id_mitra" value="{{ request('id_mitra') }}">
        @endif

        <select name="tahun" onchange="this.form.submit()" 
            class="border border-gray-300 rounded-lg text-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
            <option value="">Semua Tahun</option>
            @foreach($listTahun as $th)
                <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>
                    {{ $th }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- Ekspor PDF --}}
    <a href="{{ route('kolaborasi.pdf', request()->all()) }}"
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
        </svg>
        Ekspor PDF
    </a>

    {{-- Ekspor Excel --}}
    <a href="{{ route('kolaborasi.excel', request()->all()) }}"
        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6M3 17V7a2 2 0 012-2h14a2 2 0 012 2v10" />
        </svg>
        Ekspor Excel
    </a>
</div>

{{-- TABLE --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500" id="bantuanTable">
            <thead class="text-sm text-gray-700 bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-center">No</th>
                    <th class="px-4 py-3">Mitra</th>
                    <th class="px-4 py-3">Nama Bantuan</th>
                    <th class="px-4 py-3">Jenis</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bantuans as $index => $item)
                <tr class="border-t border-gray-100 hover:bg-gray-50 searchable-row">
                    <td class="px-4 py-3 text-center font-medium">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-col">
                            <span class="text-gray-800 font-bold uppercase text-[11px] tracking-tight flex items-center gap-1">
                                <i data-lucide="building-2" class="w-3 h-3 text-indigo-500"></i> {{ $item->mitra->nama_mitra ?? 'Mitra Terhapus' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 font-semibold text-gray-700">{{ $item->nama_bantuan }}</td>
                    <td class="px-4 py-3 text-xs uppercase font-bold text-gray-500">{{ $item->jenis_bantuan }}</td>
                    <td class="px-4 py-3 text-gray-600 italic">{{ \Carbon\Carbon::parse($item->tgl_pelaksanaan)->format('d/m/Y') }}</td>
                    <td class="px-4 py-3">
                        @php
                            $statusColor = [
                                'Terencana' => 'bg-blue-100 text-blue-700',
                                'Berjalan' => 'bg-amber-100 text-amber-700',
                                'Selesai' => 'bg-green-100 text-green-700',
                            ];
                        @endphp
                        <span class="{{ $statusColor[$item->status] ?? 'bg-gray-100' }} text-[10px] font-bold px-2 py-1 rounded-full uppercase">
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center items-center gap-2">
                            {{-- TOMBOL BARU: ISI PENYALURAN --}}
                            @if($item->status !== 'Selesai')
                                <button onclick='openPenyaluranModal(@json($item))' 
                                        class="text-green-500 hover:text-green-700 p-1" 
                                        title="Isi Bukti Penyaluran">
                                    <i data-lucide="package-check" class="w-4 h-4"></i>
                                </button>
                            @else
                                <button class="text-gray-300 cursor-not-allowed p-1" title="Sudah Disalurkan">
                                    <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                                </button>
                            @endif
                            <button onclick='openDetailModal(@json($item))' class="text-blue-500 hover:text-blue-700">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button onclick='openEditModal(@json($item))' class="text-amber-500 hover:text-amber-700">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            <button type="button" 
                                    onclick="openModalDelete('{{ $item->id_kolaborasi }}', '{{ $item->nama_bantuan }}')" 
                                    class="text-red-500 hover:text-red-700 p-1">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic text-sm border-t">Belum ada riwayat bantuan kolaborasi.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH BANTUAN --}}
<div id="modal-tambah-bantuan" tabindex="-1" class="hidden fixed inset-0 z-50 items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    
    {{-- Background Overlay untuk menutup modal saat diklik di luar area --}}
    <div class="fixed inset-0" data-modal-toggle="modal-tambah-bantuan"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        {{-- Header --}}
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Buat Bantuan Baru</h3>
            <button type="button" data-modal-toggle="modal-tambah-bantuan" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form action="{{ route('bantuan.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            
            {{-- Body Content (Scrollable area) --}}
            <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
                <div class="grid grid-cols-2 gap-4">
                    
                    {{-- Baris 1: Kategori KUBE & KUBE Penerima --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kategori KUBE</label>
                        <select id="kategoriSelect" data-kubes='{!! json_encode($kubes) !!}' class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id_kategori }}">{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pilih KUBE Penerima (Bisa Lebih dari 1)</label>
                        {{-- name="id_kube" DIHAPUS dari select utama karena select ini hanya berfungsi sebagai jembatan pilihan --}}
                        <select id="kubeSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none bg-gray-50" disabled>
                            <option value="">-- Pilih Kategori Dahulu --</option>
                        </select>
                    </div>

                    {{-- Baris Baru: Tempat List KUBE yang Terpilih (Gunakan Full Width col-span-2) --}}
                    <div class="col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Daftar KUBE Penerima Terpilih:</label>
                        
                        {{-- Input hidden wajib ini untuk validasi HTML agar form tidak bisa disubmit jika belum ada KUBE yang dipilih --}}
                        <input type="checkbox" id="validateKube" required class="opacity-0 absolute w-0 h-0">
                        
                        {{-- Wadah tempat baris-baris KUBE baru akan muncul --}}
                        <div id="kubeListContainer" class="space-y-2">
                            <p id="emptyKubeMessage" class="text-xs text-gray-500 italic">Belum ada KUBE yang dipilih.</p>
                        </div>
                    </div>

                    {{-- Baris 2: Pilih Mitra & Tanggal Pelaksanaan --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Mitra</label>
                        <select name="id_mitra" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                            @foreach($mitras as $m)
                                <option value="{{ $m->id_mitra }}" {{ isset($filterMitra) && $filterMitra->id_mitra == $m->id_mitra ? 'selected' : '' }}>{{ $m->nama_mitra }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tgl_pelaksanaan" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 3: Jenis Bantuan & Nama Program Bantuan/Kolaborasi --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Bantuan</label>
                        <select name="jenis_bantuan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                            <option value="Modal Usaha">Modal Usaha</option>
                            <option value="Peralatan">Peralatan</option>
                            <option value="Pelatihan">Pelatihan</option>
                            <option value="Bahan Baku">Bahan Baku</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Program Bantuan/Kolaborasi</label>
                        <input type="text" name="nama_bantuan" placeholder="Contoh: Program Pelatihan Kewirausahaan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 4: Rincian Barang/Jasa & Unggah Foto Bukti (Berdampingan Sejajar) --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Rincian Barang/Jasa (Bantuan)</label>
                        <textarea name="bantuan" rows="4" placeholder="Jelaskan detail barang, misal: 2 Unit Laptop RAM 8GB" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none resize-none" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Unggah Foto Bukti Perencanaan</label>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white mb-2">
                            {{-- Input File Asli (Tersembunyi) --}}
                            <input type="file" name="foto_bukti" id="fotoBuktiInput" class="hidden" accept="image/*" required 
                                   onchange="document.getElementById('fotoBuktiLabel').value = this.files[0] ? this.files[0].name : 'Klik untuk pilih foto bukti'">

                            {{-- Input Text Palsu untuk Label Nama File --}}
                            <input type="text" id="fotoBuktiLabel" readonly 
                                placeholder="Klik untuk pilih foto bukti"
                                class="flex-1 px-3 py-2 text-sm text-gray-500 cursor-pointer bg-transparent outline-none"
                                onclick="document.getElementById('fotoBuktiInput').click()">

                            {{-- Tombol Pilih File --}}
                            <button type="button"
                                onclick="document.getElementById('fotoBuktiInput').click()"
                                class="bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold px-4 py-2 transition-colors whitespace-nowrap">
                                Pilih File
                            </button>
                        </div>
                        <span class="text-[10px] text-gray-400 block italic uppercase font-semibold">Format: JPG, PNG (Maks. 5MB)</span>
                    </div>

                    {{-- Baris 5: Deskripsi Program (Full Width) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Program</label>
                        <textarea name="deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none resize-none" placeholder="Catatan atau tujuan bantuan..." required></textarea>
                    </div>

                    {{-- Data Status Hidden --}}
                    <input type="hidden" name="status" value="Terencana">
                    
                </div>
            </div>
            
            {{-- Footer Buttons --}}
            <div class="p-4 border-t bg-gray-50 flex justify-end gap-3">
                <button type="button" data-modal-toggle="modal-tambah-bantuan" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 shadow-md transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT BANTUAN --}}
<div id="modal-edit-bantuan" tabindex="-1" class="hidden fixed inset-0 z-50 items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    
    {{-- Background Overlay untuk menutup modal saat diklik di luar area --}}
    <div class="fixed inset-0" onclick="closeEditModal()"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        {{-- Header --}}
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Edit Data Bantuan</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="form-edit-bantuan" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            
            {{-- Body Content (Scrollable area) --}}
            <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
                <div class="grid grid-cols-2 gap-4">
                    
                    {{-- Baris 1: Pilih Mitra & Pilih KUBE Penerima --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Mitra</label>
                        <select name="id_mitra" id="edit_id_mitra" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                            @foreach($mitras as $m)
                                <option value="{{ $m->id_mitra }}">{{ $m->nama_mitra }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Baris KUBE Menggunakan Checkbox List Dinamis agar Bisa Menampung Lebih dari 1 Pilihan --}}
                    <div class="col-span-2 bg-gray-50 p-4 rounded-lg border border-gray-200 mt-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">KUBE Penerima Terpilih (Bisa Lebih dari 1):</label>
                        
                        <div id="editKubeCheckboxContainer" data-master-kubes='{!! json_encode($kubes) !!}' class="grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-2 bg-white rounded-md border">
                            <p class="text-xs text-gray-500 italic">Memuat data KUBE...</p>
                        </div>
                    </div>

                    {{-- Baris 2: Jenis Bantuan & Tanggal Pelaksanaan --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Bantuan</label>
                        <select name="jenis_bantuan" id="edit_jenis_bantuan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                            <option value="Modal Usaha">Modal Usaha</option>
                            <option value="Peralatan">Peralatan</option>
                            <option value="Pelatihan">Pelatihan</option>
                            <option value="Bahan Baku">Bahan Baku</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tgl_pelaksanaan" id="edit_tgl_pelaksanaan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 3: Nama Program Bantuan/Kolaborasi (Full Width col-span-2) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Program Bantuan/Kolaborasi</label>
                        <input type="text" name="nama_bantuan" id="edit_nama_bantuan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 4: Rincian Barang/Jasa & Ganti Foto Bukti (Berdampingan Sejajar) --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Rincian Barang/Jasa (Bantuan)</label>
                        <textarea name="bantuan" id="edit_bantuan" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none resize-none" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Ganti Foto (Opsional)</label>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white mb-2">
                            {{-- Input File Asli (Tersembunyi) --}}
                            <input type="file" name="foto_bukti" id="fotoBuktiEditInput" class="hidden" accept="image/*" 
                                   onchange="document.getElementById('fotoBuktiEditLabelText').value = this.files[0] ? this.files[0].name : 'Pilih foto baru'">

                            {{-- Input Text Palsu untuk Label Nama File --}}
                            <input type="text" id="fotoBuktiEditLabelText" readonly 
                                placeholder="Pilih foto baru"
                                class="flex-1 px-3 py-2 text-sm text-gray-500 cursor-pointer bg-transparent outline-none"
                                onclick="document.getElementById('fotoBuktiEditInput').click()">

                            {{-- Tombol Pilih File --}}
                            <button type="button"
                                onclick="document.getElementById('fotoBuktiEditInput').click()"
                                class="bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold px-4 py-2 transition-colors whitespace-nowrap">
                                Pilih File
                            </button>
                        </div>
                        <span class="text-[10px] text-gray-400 block italic uppercase font-semibold mb-2">Format: JPG, PNG (Maks. 5MB)</span>
                    </div>

                    {{-- Baris 5: Deskripsi Program & Status Pelaksanaan --}}
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Program</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none resize-none" required></textarea>
                    </div>
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                       
                        <div id="status_readonly" class="hidden bg-gray-100 p-3 rounded-lg border text-gray-600 text-sm font-bold items-center gap-2">
                            <svg class="w-5 h-5 text-green-500 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Selesai (Sudah dilaksanakan)
                        </div>
                        
                        <select name="status" id="edit_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none">
                            <option value="Terencana">Terencana</option>
                            <option value="Berjalan">Berjalan</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                        <input type="hidden" name="status" id="hidden_status_selesai" value="Selesai" disabled>
                    </div>
                    
                </div>
            </div>
            
            {{-- Footer Buttons --}}
            <div class="p-4 border-t bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 shadow-md transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DETAIL BANTUAN --}}
<div id="modal-detail-bantuan" tabindex="-1" class="hidden fixed inset-0 z-50 items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    
    {{-- Background Overlay untuk menutup modal saat diklik di luar area --}}
    <div class="fixed inset-0" onclick="closeDetailModal()"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        {{-- Header (Disamakan dengan Modal Tambah) --}}
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Detail Bantuan Kolaborasi</h3>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Body Content (Scrollable area - Disamakan dengan Modal Tambah) --}}
        <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
            <div class="grid grid-cols-2 gap-4">
                
                {{-- Baris 1: Mitra & KUBE Penerima Banyak ID --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Mitra</label>
                    <div id="detail_mitra" class="w-full border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-sm font-semibold text-gray-800"></div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">KUBE Penerima</label>
                    {{-- Diubah menjadi area scrollable jika nama KUBE terpilih sangat banyak --}}
                    <div id="detail_kube" class="w-full border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-sm font-semibold text-indigo-700 max-h-85px overflow-y-auto leading-relaxed">
                        {{-- Nama-nama KUBE hasil terjemahan array JavaScript akan disuntikkan di sini --}}
                    </div>
                </div>

                {{-- Baris 2: Jenis Bantuan & Tanggal Pelaksanaan --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Bantuan</label>
                    <div id="detail_jenis" class="w-full border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 font-medium"></div>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Perencanaan</label>
                    <div id="detail_tgl" class="w-full border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 font-mono font-medium"></div>
                </div>

                {{-- Baris 3: Nama Program Bantuan/Kolaborasi (Full Width) --}}
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Program Bantuan/Kolaborasi</label>
                    <div id="detail_nama_bantuan" class="w-full border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-sm font-bold text-gray-800"></div>
                </div>

                {{-- Baris 4: Rincian Barang/Jasa & Foto Bukti / Status (Sejajar Berdampingan) --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Rincian Barang/Jasa (Bantuan)</label>
                    <div id="detail_bantuan" class="w-full h-40 border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 whitespace-pre-line italic overflow-y-auto"></div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Foto Bukti Perencanaan</label>
                        <img id="detail_foto_bukti" src="" alt="Bukti" class="w-full h-126px object-cover rounded-lg border border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status Pelaksanaan</label>
                        <div class="mt-2">
                            <span id="detail_status" class="inline-block px-4 py-2 rounded-full text-xs font-extrabold uppercase tracking-wider shadow-sm"></span>
                        </div>
                    </div>
                </div>

                {{-- Baris 5: Deskripsi Program (Full Width) --}}
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Program</label>
                    <div id="detail_deskripsi" class="w-full border border-gray-300 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 leading-relaxed min-h-60px"></div>
                </div>

                {{-- SECTION BUKTI PENYALURAN (Muncul jika sudah disalurkan) --}}
                <div id="section_detail_penyaluran" class="col-span-2 mt-4 pt-4 border-t-2 border-dashed border-gray-200 hidden">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-2">
                            <i data-lucide="verified" class="w-5 h-5 text-green-600"></i>
                            <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Laporan Pelaksanaan</h4>
                        </div>

                        {{-- FORM HAPUS PENYALURAN (Tetap Utuh) --}}
                        <form id="form-hapus-penyaluran" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmDeletePenyaluran()" 
                                    class="group flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg border border-red-200 transition-all">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5 transition-transform group-hover:scale-110"></i>
                                <span class="text-[11px] font-bold uppercase">Hapus Bukti</span>
                            </button>
                        </form>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-green-50/50 p-4 rounded-xl border border-green-100">
                        <div class="col-span-2 sm:col-span-1">
                            <label class="block text-[10px] font-bold text-green-600 uppercase mb-1">Tanggal Pelaksanaan</label>
                            <div id="detail_tgl_penyaluran" class="text-sm font-bold text-gray-800"></div>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-green-600 uppercase mb-1">Catatan Pelaksanaan</label>
                            <div id="detail_catatan_penyaluran" class="text-sm text-gray-700 leading-relaxed italic"></div>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-[10px] font-bold text-green-600 uppercase mb-1">Dokumentasi Pelaksanaan</label>
                            <div id="galeri_dokumentasi" class="grid grid-cols-3 gap-2 mt-2"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer Buttons (Disamakan strukturnya dengan Modal Tambah) --}}
        <div class="p-4 border-t bg-gray-50 flex justify-end">
            <button type="button" onclick="closeDetailModal()" 
                class="px-6 py-2 bg-gray-800 text-white font-semibold rounded-lg hover:bg-gray-900 shadow-md transition">
                Tutup Halaman
            </button>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS --}}
<div id="modalDelete" class="hidden fixed inset-0 z-9999 items-center justify-center">
    <div class="absolute inset-0 bg-gray-900/60 backdrop-blur-sm"></div>
    
    <div class="relative bg-white rounded-xl shadow-xl max-w-sm w-full mx-4 p-6 transition-all">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i data-lucide="alert-circle" class="h-10 w-10 text-red-600"></i>
            </div>
            
            <h3 class="text-lg font-bold text-gray-900">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-500 mt-2" id="textDeleteName"></p>
            
            <div class="mt-6 flex justify-center gap-3">
                <button type="button" onclick="closeModalDelete()" 
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold rounded-lg transition-all">
                    Batal
                </button>
                
                <form id="formDeleteAction" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg shadow-md transition-all">
                        Ya, Hapus Data
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ISI PENYALURAN BANTUAN --}}
<div id="modal-penyaluran-bantuan" tabindex="-1" class="hidden fixed inset-0 z-50 items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    
    {{-- Background Overlay untuk menutup modal saat diklik di luar area --}}
    <div class="fixed inset-0" onclick="closePenyaluranModal()"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        {{-- Header --}}
        <div class="p-6 border-b flex justify-between items-center">
            <div class="flex items-center gap-2">
                <i data-lucide="package-check" class="w-6 h-6 text-green-600"></i>
                <h3 class="text-xl font-semibold text-gray-800">Input Bukti Pelaksanaan</h3>
            </div>
            <button type="button" onclick="closePenyaluranModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form id="formPenyaluran" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            
            {{-- Body Content (Scrollable area) --}}
            <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
                <div class="grid grid-cols-2 gap-4">
                    
                    {{-- Baris 1: Info Program (Full Width col-span-2) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Program Bantuan</label>
                        <div id="labelNamaBantuanPenyaluran" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm font-bold text-indigo-700"></div>
                    </div>

                    {{-- Baris 2: Tanggal Penyaluran & Input Multiple Foto (Sejajar Berdampingan seperti Baris 4 Modal Tambah) --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Pelaksanaan</label>
                        <input type="date" name="tgl_penyaluran" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Dokumentasi Pelaksanaan</label>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white mb-2">
                            {{-- Input File Asli (Tersembunyi) --}}
                            <input type="file" name="foto[]" id="multipleFotoInput" class="hidden" accept="image/*" multiple required>

                            {{-- Input Text Palsu untuk Label Nama File --}}
                            <div id="multipleFotoLabel" readonly 
                                class="flex-1 px-3 py-2 text-sm text-gray-500 cursor-pointer bg-transparent outline-none truncate select-none whitespace-nowrap"
                                onclick="document.getElementById('multipleFotoInput').click()">
                                Klik untuk pilih beberapa foto sekaligus
                            </div>

                            {{-- Tombol Pilih File --}}
                            <button type="button"
                                onclick="document.getElementById('multipleFotoInput').click()"
                                class="bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold px-4 py-2 transition-colors whitespace-nowrap">
                                Pilih File
                            </button>
                        </div>
                        <span class="text-[10px] text-gray-400 block italic uppercase font-semibold">Format: JPG, PNG • Max: 5MB/file</span>
                    </div>

                    {{-- Baris 3: Catatan Pelaksanaan (Full Width col-span-2) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Catatan Detail Pelaksanaan</label>
                        <textarea name="catatan_pelaksanaan" id="input_catatan_penyaluran" rows="4" minlength="30" placeholder="Tuliskan detail proses penyerahan bantuan..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none resize-none leading-relaxed" required></textarea>
                        <div class="flex justify-between mt-1 px-1">
                            <p id="error_catatan_penyaluran" class="text-[10px] text-red-500 font-bold hidden italic">
                                Catatan terlalu singkat, mohon deskripsikan lebih jelas.
                            </p>
                            <p id="count_catatan_penyaluran" class="text-[10px] text-gray-400 ml-auto font-medium tracking-wide">
                                0 / 30 Karakter
                            </p>
                        </div>
                    </div>
                    
                </div>
            </div>
            
            {{-- Footer Buttons --}}
            <div class="p-4 border-t bg-gray-50 flex justify-end gap-3">
                <button type="button" onclick="closePenyaluranModal()" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition text-sm">
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 shadow-md transition text-sm flex items-center gap-2">
                    <i data-lucide="save" class="w-4 h-4"></i> Simpan Laporan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // 1. Search Function
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        document.querySelectorAll('.searchable-row').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
        });
    });

    // 2. Fungsi Modal Edit
    function openEditModal(item) {
        // 1. Tampilkan modal terlebih dahulu
        document.getElementById('modal-edit-bantuan').classList.remove('hidden');
        
        // 2. Set action URL Form ke Route update Laravel
        const form = document.getElementById('form-edit-bantuan');
        form.action = `/admin/bantuan/update/${item.id_kolaborasi}`;
        
        // 3. Masukkan data teks standar ke input form masing-masing
        document.getElementById('edit_id_mitra').value = item.id_mitra;
        document.getElementById('edit_jenis_bantuan').value = item.jenis_bantuan;
        document.getElementById('edit_tgl_pelaksanaan').value = item.tgl_pelaksanaan;
        document.getElementById('edit_nama_bantuan').value = item.nama_bantuan;
        document.getElementById('edit_bantuan').value = item.bantuan;
        document.getElementById('edit_deskripsi').value = item.deskripsi;
        document.getElementById('edit_status').value = item.status;

        // 4. Reset display input file foto (agar bersih dari data klik sebelumnya)
        if (document.getElementById('fotoBuktiEditLabelText')) document.getElementById('fotoBuktiEditLabelText').value = '';
        if (document.getElementById('fotoBuktiEditInput')) document.getElementById('fotoBuktiEditInput').value = '';

        // 5. PROSES CEK & RENDER CHEKBOX KUBE SECARA DINAMIS
        const checkboxContainer = document.getElementById('editKubeCheckboxContainer');
        if (checkboxContainer) {
            // Ambil data master KUBE dari attribute HTML
            const masterKubes = JSON.parse(checkboxContainer.getAttribute('data-master-kubes') || '[]');
            checkboxContainer.innerHTML = ''; // bersihkan kontainer halaman

            // Parsing data id_kube item (bisa mendeteksi format string "1" atau format json ["1","2"])
            let selectedKubeIds = [];
            try {
                if (typeof item.id_kube === 'string' && (item.id_kube.startsWith('[') || item.id_kube.startsWith('{'))) {
                    selectedKubeIds = JSON.parse(item.id_kube);
                } else if (Array.isArray(item.id_kube)) {
                    selectedKubeIds = item.id_kube;
                } else if (item.id_kube) {
                    selectedKubeIds = [item.id_kube.toString()];
                }
            } catch (e) {
                selectedKubeIds = [String(item.id_kube)]; // Fallback jika gagal parse
            }

            // Konversi semua ID ke String untuk komparasi presisi data '.includes()'
            selectedKubeIds = selectedKubeIds.map(id => String(id));

            // Buat element checkbox
            if (masterKubes.length === 0) {
                checkboxContainer.innerHTML = '<p class="text-xs text-gray-500 italic col-span-2">Tidak ada data master KUBE.</p>';
            } else {
                masterKubes.forEach(kube => {
                    const isChecked = selectedKubeIds.includes(String(kube.id_kube)) ? 'checked' : '';
                    
                    const checkboxHtml = `
                        <label class="flex items-center gap-2 p-2 rounded hover:bg-gray-50 cursor-pointer border border-gray-100 text-xs">
                            <input type="checkbox" name="id_kube[]" value="${kube.id_kube}" ${isChecked} class="w-4 h-4 rounded text-blue-600 focus:ring-blue-400 border-gray-300">
                            <span class="text-gray-700 font-medium">${kube.nama_kube}</span>
                        </label>
                    `;
                    checkboxContainer.insertAdjacentHTML('beforeend', checkboxHtml);
                });
            }
        }

        // 6. Logika Tampilan Kondisi Status Readonly (Bawaan Sistem Anda)
        const statusSelect = document.getElementById('edit_status');
        const statusReadOnly = document.getElementById('status_readonly');
        const hiddenStatus = document.getElementById('hidden_status_selesai');

        if (item.bukti_penyaluran) {
            statusSelect.classList.add('hidden');
            statusReadOnly.classList.remove('hidden');
            hiddenStatus.disabled = false;
        } else {
            statusSelect.classList.remove('hidden');
            statusReadOnly.classList.add('hidden');
            statusSelect.value = item.status;
            hiddenStatus.disabled = true;
        }
    }

    function closeEditModal() {
        document.getElementById('modal-edit-bantuan').classList.add('hidden');
    }

    // Fungsi Modal Detail (SUDAH DIPERBAIKIN UNTUK BANYAK KUBE)
    function openDetailModal(item) {
        const modal = document.getElementById('modal-detail-bantuan');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('detail_nama_bantuan').innerText = item.nama_bantuan;
        document.getElementById('detail_mitra').innerText = item.mitra ? item.mitra.nama_mitra : 'N/A';
        
        // ==================== PERBAIKAN LOGIKA BANYAK KUBE ====================
        const kubeContainer = document.getElementById('detail_kube');
        kubeContainer.innerHTML = ''; // Bersihkan sisa teks modal sebelumnya

        // Ambil data master KUBE yang tersimpan di atribut data-kubes milik kategoriSelect
        const kategoriSelect = document.getElementById('kategoriSelect');
        const allKubes = JSON.parse(kategoriSelect.getAttribute('data-kubes') || '[]');

        let namaKubeTerpilih = [];
        let arrayIdKube = [];

        // Cek format id_kube (Array JSON, String JSON, atau Teks Tunggal Biasa)
        if (Array.isArray(item.id_kube)) {
            arrayIdKube = item.id_kube;
        } else {
            try {
                arrayIdKube = JSON.parse(item.id_kube);
                if (!Array.isArray(arrayIdKube)) {
                    arrayIdKube = [arrayIdKube];
                }
            } catch (e) {
                // Jika data lama bertipe tunggal/bukan JSON (Contoh: "1", "2")
                arrayIdKube = item.id_kube ? [String(item.id_kube)] : [];
            }
        }

        // Terjemahkan ID menjadi Nama KUBE
        arrayIdKube.forEach(id => {
            const cocok = allKubes.find(k => String(k.id_kube) === String(id));
            if (cocok) {
                namaKubeTerpilih.push(cocok.nama_kube);
            }
        });

        // Tampilkan ke UI Modal
        if (namaKubeTerpilih.length > 0) {
            kubeContainer.innerHTML = `<span class="text-gray-800">👥 ${namaKubeTerpilih.join(', ')}</span>`;
        } else {
            // Backup cadangan jika master data tidak cocok, gunakan relasi formalitas Laravel
            kubeContainer.innerText = item.kube ? item.kube.nama_kube : 'N/A';
        }
        // ======================================================================

        document.getElementById('detail_tgl').innerText = item.tgl_pelaksanaan;
        document.getElementById('detail_jenis').innerText = item.jenis_bantuan;
        document.getElementById('detail_bantuan').innerText = item.bantuan;
        document.getElementById('detail_deskripsi').innerText = item.deskripsi;
        
        const statusEl = document.getElementById('detail_status');
        statusEl.innerText = item.status;
        statusEl.className = "inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold uppercase ";
        if(item.status === 'Selesai') statusEl.classList.add('bg-green-100', 'text-green-700');
        else if(item.status === 'Berjalan') statusEl.classList.add('bg-amber-100', 'text-amber-700');
        else statusEl.classList.add('bg-blue-100', 'text-blue-700');

        //Foto bukti
        const fotoEl = document.getElementById('detail_foto_bukti');
        if (item.foto_bukti) {
            fotoEl.src = "/admin/bantuan/lihat-foto/" + item.foto_bukti;
            fotoEl.classList.remove('hidden');
        } else {
            fotoEl.classList.add('hidden');
        }

        const penampungPenyaluran = document.getElementById('section_detail_penyaluran');
        const galeri = document.getElementById('galeri_dokumentasi');
        const formHapus = document.getElementById('form-hapus-penyaluran'); 
    
        if (item.bukti_penyaluran) {
            penampungPenyaluran.classList.remove('hidden');

            if (formHapus) {
                formHapus.action = `/penyaluran-kolaborasi/destroy/${item.id_kolaborasi}`;
            }
            document.getElementById('detail_tgl_penyaluran').innerText = item.bukti_penyaluran.tgl_penyaluran;
            document.getElementById('detail_catatan_penyaluran').innerText = item.bukti_penyaluran.catatan_pelaksanaan;
            galeri.innerHTML = '';

            if(item.bukti_penyaluran.dokumentasi && item.bukti_penyaluran.dokumentasi.length > 0) {
                item.bukti_penyaluran.dokumentasi.forEach(dok => {
                    const imgHtml = `
                        <div class="relative group aspect-square overflow-hidden rounded-lg border shadow-sm bg-gray-100">
                            <img src="/uploads/dokumentasi/${dok.foto_path}" 
                                class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110 cursor-pointer"
                                onclick="window.open(this.src)"
                                onerror="this.src='https://placehold.co/400x400?text=Foto+Tidak+Ditemukan'">
                        </div>`;
                    galeri.insertAdjacentHTML('beforeend', imgHtml);
                });
            } else {
                galeri.innerHTML = '<p class="text-xs text-gray-400 italic col-span-3">Tidak ada foto dokumentasi.</p>';
            }
        } else {
            penampungPenyaluran.classList.add('hidden');
        }
    }

    function closeDetailModal() {
        const modal = document.getElementById('modal-detail-bantuan');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    
    // 4. Fungsi Modal Delete
    function openModalDelete(id, nama) {
        if (window.event) {
            window.event.preventDefault();
            window.event.stopPropagation();
        }

        const modal = document.getElementById('modalDelete');
        const textLabel = document.getElementById('textDeleteName');
        const form = document.getElementById('formDeleteAction');

        textLabel.innerText = `Apakah Anda yakin ingin menghapus bantuan "${nama}"?`;
        form.action = `/admin/bantuan/delete/${id}`;

        modal.classList.remove('hidden');
        return false;
    }

    function closeModalDelete() {
        document.getElementById('modalDelete').classList.add('hidden');
    }

    // 5. Fungsi Penyaluran
    function openPenyaluranModal(item) {
        const modal = document.getElementById('modal-penyaluran-bantuan');
        const form = document.getElementById('formPenyaluran');
        const labelBantuan = document.getElementById('labelNamaBantuanPenyaluran');

        form.action = `/penyaluran-kolaborasi/store/${item.id_kolaborasi}`;
        if (labelBantuan) labelBantuan.innerText = item.nama_bantuan;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        if (window.lucide) lucide.createIcons();
    }

    function closePenyaluranModal() {
        const modal = document.getElementById('modal-penyaluran-bantuan');
        const form = document.getElementById('formPenyaluran');
        const multiLabel = document.getElementById('multipleFotoLabel');
        const countLabel = document.getElementById('count_catatan_penyaluran');
        const errorLabel = document.getElementById('error_catatan_penyaluran');

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        form.reset();

        if (countLabel) {
        countLabel.innerText = '0 / 30 Karakter';
        countLabel.className = 'text-[10px] text-gray-400 ml-auto font-medium tracking-wide';
        }
        if (errorLabel) errorLabel.classList.add('hidden');

        if (multiLabel) {
            multiLabel.innerText = "Klik untuk pilih beberapa foto sekaligus";
            multiLabel.classList.remove('text-green-600', 'font-bold');
        }
    }

    // 6. Init and Event Listeners
    document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. VALIDASI CATATAN PENYALURAN ---
    const catatanPenyaluran = document.getElementById('input_catatan_penyaluran');
    const countLabel = document.getElementById('count_catatan_penyaluran');
    const errorLabel = document.getElementById('error_catatan_penyaluran');

    if (catatanPenyaluran) {
        catatanPenyaluran.addEventListener('input', function() {
            const length = this.value.length;
            if (countLabel) countLabel.innerText = `${length} / 30 Karakter`;

            if (length > 0 && length < 30) {
                if (countLabel) countLabel.className = 'text-[10px] text-amber-500 ml-auto font-medium tracking-wide';
                if (errorLabel) errorLabel.classList.remove('hidden');
            } else if (length >= 30) {
                if (countLabel) countLabel.className = 'text-[10px] text-green-600 ml-auto font-medium tracking-wide';
                if (errorLabel) errorLabel.classList.add('hidden');
            } else {
                if (countLabel) countLabel.className = 'text-[10px] text-gray-400 ml-auto font-medium tracking-wide';
                if (errorLabel) errorLabel.classList.add('hidden');
            }
        });
    }

    // --- 2. MULTIPLE FOTO PENYALURAN (DOKUMENTASI) ---
    const multiInput = document.getElementById('multipleFotoInput');
    const multiLabel = document.getElementById('multipleFotoLabel');

    if (multiInput && multiLabel) {
        multiInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                multiLabel.innerText = this.files.length + " foto terpilih";
                multiLabel.classList.add('text-green-600', 'font-bold');
            } else {
                multiLabel.innerText = "Klik untuk pilih beberapa foto sekaligus";
                multiLabel.classList.remove('text-green-600', 'font-bold');
            }
        });
    }

    // --- 3. SINGLE FOTO BUKTI (MODAL TAMBAH) ---
    const buktiInput = document.getElementById('fotoBuktiInput');
    const buktiLabel = document.getElementById('fotoBuktiLabel');

    if (buktiInput && buktiLabel) {
        buktiInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                buktiLabel.innerText = fileName.length > 25 ? fileName.substring(0, 25) + "..." : fileName;
                buktiLabel.classList.add('text-indigo-600', 'font-bold');
            } else {
                buktiLabel.innerText = "Klik untuk pilih foto bukti";
                buktiLabel.classList.remove('text-indigo-600', 'font-bold');
            }
        });
    }

    // --- 4. SINGLE FOTO BUKTI (MODAL EDIT) ---
    const editFotoInput = document.getElementById('fotoBuktiEditInput');
    const editFotoLabel = document.getElementById('fotoBuktiEditLabel');

    if (editFotoInput && editFotoLabel) {
        editFotoInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                const fileName = this.files[0].name;
                editFotoLabel.innerText = fileName.length > 25 ? fileName.substring(0, 25) + "..." : fileName;
                editFotoLabel.classList.add('text-indigo-600', 'font-bold');
            }
        });
    }
    });

    

    // Global Click Handler
    window.addEventListener('click', function(e) {
        const modalTambah = document.getElementById('modal-tambah-bantuan');
        const modalEdit = document.getElementById('modal-edit-bantuan');
        const modalDetail = document.getElementById('modal-detail-bantuan');
        const modalDelete = document.getElementById('modalDelete');
        const modalPenyaluran = document.getElementById('modal-penyaluran-bantuan');

        if (e.target == modalTambah) modalTambah.classList.add('hidden');
        if (e.target == modalEdit) modalEdit.classList.add('hidden');
        if (e.target == modalDetail) closeDetailModal();
        if (e.target == modalPenyaluran) closePenyaluranModal();
        if (e.target.classList.contains('bg-gray-900/60')) closeModalDelete();
    }, true);

    function confirmDeletePenyaluran() {
    // 1. Ambil URL action dari form hapus yang ada di detail
    const detailFormHapus = document.getElementById('form-hapus-penyaluran');
    const actionUrl = detailFormHapus.getAttribute('action');
    
    // 2. Siapkan Modal Konfirmasi (modalDelete)
    const modalKonfirmasi = document.getElementById('modalDelete');
    const textLabel = document.getElementById('textDeleteName');
    const formKonfirmasi = document.getElementById('formDeleteAction');

    // 3. Set Pesan Khusus Penyaluran
    textLabel.innerHTML = "Apakah Anda yakin ingin menghapus <strong>bukti penyaluran</strong> ini? <br><span class='text-[11px] text-red-400'>*File foto akan dihapus permanen dan status kembali menjadi Berjalan.</span>";
    
    // 4. Set Action Form Modal ke URL hapus penyaluran
    formKonfirmasi.action = actionUrl;

    // 5. Tampilkan Modal
    modalKonfirmasi.classList.remove('hidden');
    if (window.lucide) lucide.createIcons();
}

document.addEventListener("DOMContentLoaded", function () {
    const kategoriSelect = document.getElementById('kategoriSelect');
    const kubeSelect = document.getElementById('kubeSelect');
    const kubeListContainer = document.getElementById('kubeListContainer');
    const emptyKubeMessage = document.getElementById('emptyKubeMessage');
    const validateKube = document.getElementById('validateKube');

    // Kumpulan ID KUBE yang sudah dipilih agar tidak bisa dipilih dua kali
    let selectedKubeIds = [];

    const allKubes = JSON.parse(kategoriSelect.getAttribute('data-kubes') || '[]');
    
    // 1. Logika Filter Dropdown berdasarkan Kategori (Tetap seperti sebelumnya)
    kategoriSelect.addEventListener('change', function () {
        const selectedKategoriId = this.value;
        kubeSelect.innerHTML = '';

        if (!selectedKategoriId) {
            kubeSelect.innerHTML = '<option value="">-- Pilih Kategori Dahulu --</option>';
            kubeSelect.disabled = true;
            kubeSelect.classList.add('bg-gray-50');
            return;
        }

        const filteredKubes = allKubes.filter(kube => {
            return kube.cluster_usaha && String(kube.cluster_usaha.id_kategori) === String(selectedKategoriId);
        });

        if (filteredKubes.length > 0) {
            kubeSelect.innerHTML = '<option value="">-- Pilih & Tambah KUBE --</option>';
            filteredKubes.forEach(kube => {
                // Tampilkan hanya jika KUBE belum terpilih di list bawah
                if (!selectedKubeIds.includes(String(kube.id_kube))) {
                    const opt = document.createElement('option');
                    opt.value = kube.id_kube;
                    opt.textContent = kube.nama_kube;
                    kubeSelect.appendChild(opt);
                }
            });
            kubeSelect.disabled = false;
            kubeSelect.classList.remove('bg-gray-50');
        } else {
            kubeSelect.innerHTML = '<option value="">Tidak ada KUBE aktif untuk kategori ini</option>';
            kubeSelect.disabled = true;
            kubeSelect.classList.add('bg-gray-50');
        }
    });

    // 2. Logika Menambah Baris KUBE Baru saat Dropdown Dipilih
    kubeSelect.addEventListener('change', function () {
        const kubeId = this.value;
        const kubeName = this.options[this.selectedIndex].text;

        if (!kubeId) return;

        // Hilangkan tulisan "Belum ada KUBE yang dipilih"
        if (emptyKubeMessage) emptyKubeMessage.style.display = 'none';

        // Buat element baris baru (UI HTML)
        const row = document.createElement('div');
        row.className = "flex items-center justify-between bg-white p-2 rounded-md border border-gray-300 shadow-sm animate-fade-in";
        row.setAttribute('data-id', kubeId);

        row.innerHTML = `
            <span class="text-sm font-medium text-gray-800">${kubeName}</span>
            <input type="hidden" name="id_kube[]" value="${kubeId}">
            <button type="button" class="remove-kube-btn text-red-500 hover:text-red-700 p-1 text-xs font-semibold uppercase tracking-wider transition">
                Hapus
            </button>
        `;

        // Masukkan ke dalam container list
        kubeListContainer.appendChild(row);

        // Catat ID agar tidak duplikat dan hapus opsi dari dropdown utama
        selectedKubeIds.push(String(kubeId));
        this.options[this.selectedIndex].remove();
        this.value = ""; // Reset dropdown ke default

        // bypass validasi html form (menyatakan KUBE sudah diisi)
        validateKube.checked = true;
    });

    // 3. Logika Menghapus Baris KUBE dari List
    kubeListContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-kube-btn')) {
            const row = e.target.parentElement;
            const kubeId = row.getAttribute('data-id');

            // Hapus baris dari UI
            row.remove();

            // Hapus ID dari tracker array
            selectedKubeIds = selectedKubeIds.filter(id => id !== String(kubeId));

            // Jika list kosong, munculkan kembali pesan text kosong & aktifkan validasi form required
            if (selectedKubeIds.length === 0) {
                if (emptyKubeMessage) emptyKubeMessage.style.display = 'block';
                validateKube.checked = false;
            }

            // Trigger perubahan kategori agar dropdown memperbarui opsinya kembali
            kategoriSelect.dispatchEvent(new Event('change'));
        }
    });
});
</script>

@stop