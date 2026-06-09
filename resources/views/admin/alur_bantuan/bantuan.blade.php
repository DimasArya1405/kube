
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

{{-- SUMMARY CARDS --}}
<div class="flex gap-4 mb-6">
    <div class="bg-indigo-600 text-white rounded-lg px-6 py-4 text-center min-w-[150px] shadow-md">
        <p class="text-sm font-medium opacity-80">Total Bantuan</p>
        <p class="text-4xl font-bold mt-1">{{ $bantuans->count() }}</p>
    </div>
    {{-- Card 2: Kolaborasi Berjalan --}}
    <div class="bg-green-500 text-white rounded-lg px-6 py-4 text-center min-w-[150px] shadow-md">
        <p class="text-sm font-medium opacity-80">Masih Berjalan</p>
        <p class="text-4xl font-bold mt-1">
            {{ $bantuans->where('status', 'Berjalan')->count() }}
        </p>
    </div>

    {{-- Card 3: Kolaborasi Selesai --}}
    <div class="bg-orange-500 text-white rounded-lg px-6 py-4 text-center min-w-[150px] shadow-md">
        <p class="text-sm font-medium opacity-80">Selesai</p>
        <p class="text-4xl font-bold mt-1">
            {{ $bantuans->where('status', 'Selesai')->count() }}
        </p>
    </div>
</div>

{{-- TOOLBAR --}}
<div class="flex flex-wrap items-center gap-3 mb-4">
    <div class="relative flex-1 min-w-[200px]">
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

    <button data-modal-target="modal-tambah-bantuan" data-modal-toggle="modal-tambah-bantuan"
        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-md">
        <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Bantuan Baru
    </button>
</div>

{{-- TABLE --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500" id="bantuanTable">
            <thead class="text-sm text-gray-700 bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-center">No</th>
                    <th class="px-4 py-3">Mitra & KUBE</th>
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
                            <span class="text-indigo-600 font-medium text-xs mt-0.5 italic flex items-center gap-1">
                                <i data-lucide="users" class="w-3 h-3 text-gray-400"></i> KUBE: {{ $item->kube->nama_kube ?? 'KUBE Terhapus' }}
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
<div id="modal-tambah-bantuan" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-xl font-bold text-gray-800">Buat Bantuan Baru</h3>
            <button type="button" data-modal-toggle="modal-tambah-bantuan" class="text-gray-400 hover:text-red-500 text-xl font-bold">✕</button>
        </div>

        <form action="{{ route('bantuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 overflow-y-auto max-h-[75vh]">
                <div class="grid grid-cols-2 gap-4">
                    {{-- Mitra --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Mitra</label>
                        <select name="id_mitra" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                            @foreach($mitras as $m)
                            <option value="{{ $m->id_mitra }}" {{ isset($filterMitra) && $filterMitra->id_mitra == $m->id_mitra ? 'selected' : '' }}>{{ $m->nama_mitra }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- KUBE --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pilih KUBE Penerima</label>
                        <select name="id_kube" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                            @foreach($kubes as $k)
                            <option value="{{ $k->id_kube }}">{{ $k->nama_kube }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Jenis & Nama Bantuan --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Bantuan</label>
                        <select name="jenis_bantuan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                            <option value="Modal Usaha">Modal Usaha</option>
                            <option value="Peralatan">Peralatan</option>
                            <option value="Pelatihan">Pelatihan</option>
                            <option value="Bahan Baku">Bahan Baku</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tgl_pelaksanaan" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Program Bantuan/Kolaborasi</label>
                        <input type="text" name="nama_bantuan" placeholder="Contoh: Program Pelatihan Kewirausahaan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                    </div>
                    {{-- Bantuan (Sesuai Migrasi) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Rincian Barang/Jasa (Bantuan)</label>
                        <textarea name="bantuan" rows="2" placeholder="Jelaskan detail barang, misal: 2 Unit Laptop RAM 8GB" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required></textarea>
                    </div>
                    {{-- Deskripsi (Sesuai Migrasi) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Program</label>
                        <textarea name="deskripsi" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" placeholder="Catatan atau tujuan bantuan..." required></textarea>
                    </div>
                    {{-- Foto Bukti --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Unggah Foto Bukti Perencanaan</label>
                        <div class="flex items-center border-2 border-dashed border-gray-300 rounded-xl p-6 justify-center flex-col hover:bg-indigo-50 hover:border-indigo-400 transition-colors cursor-pointer" 
                            onclick="document.getElementById('fotoBuktiInput').click()">
                            
                            <i data-lucide="image-plus" class="w-8 h-8 text-gray-400 mb-2"></i>
                            <input type="file" name="foto_bukti" id="fotoBuktiInput" class="hidden" accept="image/*" required>
                            
                            <p id="fotoBuktiLabel" class="text-center text-sm text-gray-500 font-medium">
                                Klik untuk pilih foto bukti
                            </p>
                            <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-tighter italic">Format: JPG, PNG (Maks. 5MB)</p>
                        </div>
                    </div>
                    {{-- Status --}}
                    <input type="hidden" name="status" value="Terencana">
                </div>
            </div>
            <div class="flex justify-end gap-3 p-4 border-t bg-gray-50">
                <button type="button" data-modal-toggle="modal-tambah-bantuan" class="bg-gray-500 text-white px-6 py-2 rounded-lg font-bold">Batal</button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold shadow-lg">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT BANTUAN --}}
<div id="modal-edit-bantuan" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-xl font-bold text-gray-800">Edit Data Bantuan</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 text-xl font-bold">✕</button>
        </div>

        <form id="form-edit-bantuan" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') {{-- Laravel butuh method PUT untuk update --}}
            
            <div class="p-6 overflow-y-auto max-h-[75vh]">
                <div class="grid grid-cols-2 gap-4">
                    {{-- Mitra --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pilih Mitra</label>
                        <select name="id_mitra" id="edit_id_mitra" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                            @foreach($mitras as $m)
                            <option value="{{ $m->id_mitra }}">{{ $m->nama_mitra }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- KUBE --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pilih KUBE Penerima</label>
                        <select name="id_kube" id="edit_id_kube" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                            @foreach($kubes as $k)
                            <option value="{{ $k->id_kube }}">{{ $k->nama_kube }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Jenis --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Bantuan</label>
                        <select name="jenis_bantuan" id="edit_jenis_bantuan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                            <option value="Modal Usaha">Modal Usaha</option>
                            <option value="Peralatan">Peralatan</option>
                            <option value="Pelatihan">Pelatihan</option>
                            <option value="Bahan Baku">Bahan Baku</option>
                        </select>
                    </div>
                    {{-- Tanggal --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal</label>
                        <input type="date" name="tgl_pelaksanaan" id="edit_tgl_pelaksanaan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                    </div>
                    {{-- Nama Bantuan --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Program Bantuan/Kolaborasi</label>
                        <input type="text" name="nama_bantuan" id="edit_nama_bantuan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                    </div>
                    {{-- Bantuan & Deskripsi --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Rincian Barang/Jasa (Bantuan)</label>
                        <textarea name="bantuan" id="edit_bantuan" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required></textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Program</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required></textarea>
                    </div>

                    {{-- Baris Foto & Status asli --}}
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Ganti Foto (Opsional)</label>
                        {{-- GAYA BARU: Input file disembunyikan, diganti label yang bisa diklik --}}
                        <div class="relative">
                            <input type="file" name="foto_bukti" id="fotoBuktiEditInput" class="hidden" accept="image/*">
                            <label for="fotoBuktiEditInput" class="flex items-center justify-center w-full px-3 py-2 border-2 border-dashed border-gray-300 rounded-lg cursor-pointer bg-gray-50 hover:bg-indigo-50 hover:border-indigo-400 transition-all group">
                                <div class="flex items-center gap-2 text-gray-500 group-hover:text-indigo-600">
                                    <i data-lucide="image-plus" class="w-4 h-4"></i>
                                    <span id="fotoBuktiEditLabel" class="text-[11px] font-bold truncate">Pilih foto baru</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                       
                        <div id="status_readonly" class="hidden bg-gray-100 p-2 rounded-lg border text-gray-600 text-[11px] font-bold">
                            <i data-lucide="check-circle" class="w-4 h-4 inline mr-1 text-green-500"></i>
                            Selesai (Sudah dilaksanakan)
                        </div>
                        
                        <select name="status" id="edit_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="Terencana">Terencana</option>
                            <option value="Berjalan">Berjalan</option>
                            <option value="Selesai">Selesai</option>
                        </select>
                        <input type="hidden" name="status" id="hidden_status_selesai" value="Selesai" disabled>
                    </div>
                </div>
            </div>
            <div class="flex justify-end gap-3 p-4 border-t bg-gray-50">
                <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-6 py-2 rounded-lg font-bold">Batal</button>
                <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-2 rounded-lg font-bold shadow-lg">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DETAIL BANTUAN --}}
<div id="modal-detail-bantuan" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl overflow-hidden">
        {{-- Header Sama dengan Edit/Tambah --}}
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-xl font-bold text-gray-800">Detail Bantuan Kolaborasi</h3>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-red-500 text-xl font-bold">✕</button>
        </div>

        <div class="p-6 overflow-y-auto max-h-[75vh]">
            <div class="grid grid-cols-2 gap-4">
                {{-- Baris 1: Mitra & KUBE --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mitra</label>
                    <div id="detail_mitra" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm font-semibold text-gray-800"></div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">KUBE Penerima</label>
                    <div id="detail_kube" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm font-semibold text-gray-800"></div>
                </div>

                {{-- Baris 2: Jenis & Tanggal --}}
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis Bantuan</label>
                    <div id="detail_jenis" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700"></div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal</label>
                    <div id="detail_tgl" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 font-mono"></div>
                </div>

                {{-- Baris 3: Nama Bantuan --}}
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Program Bantuan/Jasa</label>
                    <div id="detail_nama_bantuan" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm font-bold text-indigo-700"></div>
                </div>

                {{-- Baris 4: Rincian Bantuan --}}
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Rincian Barang/Jasa (Bantuan)</label>
                    <div id="detail_bantuan" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 whitespace-pre-line italic"></div>
                </div>

                {{-- Baris 5: Deskripsi --}}
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Deskripsi Program</label>
                    <div id="detail_deskripsi" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 leading-relaxed"></div>
                </div>

                {{-- SECTION BUKTI PENYALURAN (Muncul jika sudah disalurkan) --}}
                <div id="section_detail_penyaluran" class="col-span-2 mt-4 pt-4 border-t-2 border-dashed border-gray-100 hidden">
                    {{-- Header dengan Tombol Hapus --}}
                    <div class="flex items-center justify-between mb-3"> {{-- Ditambah justify-between --}}
                        <div class="flex items-center gap-2">
                            <i data-lucide="verified" class="w-5 h-5 text-green-600"></i>
                            <h4 class="text-sm font-bold text-gray-800 uppercase tracking-wide">Laporan Pelaksanaan</h4>
                        </div>

                        {{-- FORM HAPUS PENYALURAN --}}
                        <form id="form-hapus-penyaluran" method="POST">
                            @csrf
                            @method('DELETE')
                            
                            <button type="button" 
                                    onclick="confirmDeletePenyaluran()" 
                                    class="group flex items-center gap-1.5 bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg border border-red-200 transition-all">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5 transition-transform group-hover:scale-110"></i>
                                <span class="text-[11px] font-bold uppercase">Hapus Bukti</span>
                            </button>
                        </form>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-green-50/50 p-4 rounded-xl border border-green-100">
                        {{-- ... isi konten tanggal dan catatan ... --}}
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

                {{-- Baris 6: Foto & Status --}}
                <div class="col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Foto Bukti</label>
                    <div class="mt-1">
                        <img id="detail_foto_bukti" src="" alt="Bukti" class="w-full h-40 object-cover rounded-lg border border-gray-200 shadow-sm">
                    </div>
                </div>
                <div class="col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status Pelaksanaan</label>
                    <div class="mt-1">
                        <span id="detail_status" class="px-4 py-2 rounded-full text-xs font-extrabold uppercase"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex justify-end p-4 border-t bg-gray-50">
            <button type="button" onclick="closeDetailModal()" class="bg-gray-800 hover:bg-gray-900 text-white px-8 py-2 rounded-lg font-bold shadow-md transition-all">Tutup</button>
        </div>
    </div>
</div>

{{-- MODAL KONFIRMASI HAPUS --}}
<div id="modalDelete" class="hidden fixed inset-0 z-[9999] flex items-center justify-center">
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
<div id="modal-penyaluran-bantuan" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl overflow-hidden">
        
        {{-- Header --}}
        <div class="flex items-center justify-between p-4 border-b bg-white">
            <div class="flex items-center gap-2">
                <i data-lucide="package-check" class="w-6 h-6 text-green-600"></i>
                <h3 class="text-xl font-bold text-gray-800">Input Bukti Pelaksanaan</h3>
            </div>
            <button type="button" onclick="closePenyaluranModal()" class="text-gray-400 hover:text-red-500 text-xl font-bold">✕</button>
        </div>

        {{-- Form --}}
        <form id="formPenyaluran" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Body: Scrollable area --}}
            <div class="p-6 overflow-y-auto max-h-[70vh]">
                <div class="grid grid-cols-2 gap-4">
                    
                    {{-- Baris 1: Info Program (Full Width) --}}
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Program Bantuan</label>
                        <div id="labelNamaBantuanPenyaluran" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm font-bold text-indigo-700"></div>
                    </div>

                    {{-- Baris 2: Tanggal Penyaluran --}}
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Pelaksanaan</label>
                        <input type="date" name="tgl_penyaluran" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-400 outline-none" 
                               required>
                    </div>

                    {{-- Baris 3: Catatan Pelaksanaan (Full Width) --}}
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Catatan Detail Pelaksanaan</label>
                        <textarea name="catatan_pelaksanaan" id="input_catatan_penyaluran" rows="4" minlength="30" placeholder="Tuliskan detail proses penyerahan bantuan..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-400 outline-none leading-relaxed" required></textarea>
                        <div class="flex justify-between mt-1 px-1">
                            <p id="error_catatan_penyaluran" class="text-[10px] text-red-500 font-bold hidden italic">
                                Catatan terlalu singkat, mohon deskripsikan lebih jelas.
                            </p>
                            <p id="count_catatan_penyaluran" class="text-[10px] text-gray-400 ml-auto font-medium tracking-wide">
                                0 / 30 Karakter
                            </p>
                        </div>
                    </div>

                    {{-- Baris 4: Upload Multiple Foto (Full Width) --}}
                    <div class="col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Dokumentasi Pelaksanaan</label>
                        <div class="flex items-center border-2 border-dashed border-gray-300 rounded-lg p-8 justify-center flex-col hover:bg-green-50 hover:border-green-400 transition-colors cursor-pointer" 
                             onclick="document.getElementById('multipleFotoInput').click()">
                            
                            <i data-lucide="images" class="w-10 h-10 text-gray-400 mb-2"></i>
                            <input type="file" name="foto[]" id="multipleFotoInput" class="hidden" accept="image/*" multiple required>
                            
                            <p id="multipleFotoLabel" class="text-center text-sm text-gray-600 font-medium">
                                Klik untuk pilih beberapa foto sekaligus
                            </p>
                            <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-widest font-bold">Format: JPG, PNG • Max: 5MB/file</p>
                        </div>
                    </div>

                </div>
            </div>


            <div class="flex justify-end gap-3 p-4 border-t bg-gray-50">
                <button type="button" onclick="closePenyaluranModal()" 
                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-8 rounded-lg transition-all text-sm shadow-md">
                    Batal
                </button>
                <button type="submit" 
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-8 rounded-lg shadow-md transition-all text-sm flex items-center gap-2">
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
        document.getElementById('modal-edit-bantuan').classList.remove('hidden');
        const form = document.getElementById('form-edit-bantuan');
        form.action = `/admin/bantuan/update/${item.id_kolaborasi}`;
        
        document.getElementById('edit_id_mitra').value = item.id_mitra;
        document.getElementById('edit_id_kube').value = item.id_kube;
        document.getElementById('edit_jenis_bantuan').value = item.jenis_bantuan;
        document.getElementById('edit_tgl_pelaksanaan').value = item.tgl_pelaksanaan;
        document.getElementById('edit_nama_bantuan').value = item.nama_bantuan;
        document.getElementById('edit_bantuan').value = item.bantuan;
        document.getElementById('edit_deskripsi').value = item.deskripsi;
        document.getElementById('edit_status').value = item.status;

        const statusSelect = document.getElementById('edit_status');
        const statusReadOnly = document.getElementById('status_readonly');
        const hiddenStatus = document.getElementById('hidden_status_selesai');

        if (item.bukti_penyaluran) {
            // Jika sudah disalurkan: Sembunyikan select, tampilkan teks readonly
            statusSelect.classList.add('hidden');
            statusReadOnly.classList.remove('hidden');
            // Aktifkan hidden input agar nilai 'Selesai' tetap terkirim
            hiddenStatus.disabled = false;
        } else {
            // Jika belum disalurkan: Tampilkan select, sembunyikan teks readonly
            statusSelect.classList.remove('hidden');
            statusReadOnly.classList.add('hidden');
            statusSelect.value = item.status;
            // Matikan hidden input agar tidak tabrakan dengan nilai select
            hiddenStatus.disabled = true;
        }
        
        document.getElementById('modal-edit-bantuan').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('modal-edit-bantuan').classList.add('hidden');
    }

    // 3. Fungsi Modal Detail
    function openDetailModal(item) {
        const modal = document.getElementById('modal-detail-bantuan');
        modal.classList.remove('hidden');
        modal.classList.add('flex');

        document.getElementById('detail_nama_bantuan').innerText = item.nama_bantuan;
        document.getElementById('detail_mitra').innerText = item.mitra ? item.mitra.nama_mitra : 'N/A';
        document.getElementById('detail_kube').innerText = item.kube ? item.kube.nama_kube : 'N/A';
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
        const formHapus = document.getElementById('form-hapus-penyaluran'); // Ambil element form hapus
    
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
</script>

@stop