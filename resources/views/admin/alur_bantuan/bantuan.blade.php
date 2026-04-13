
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

    <button data-modal-target="modal-tambah-bantuan" data-modal-toggle="modal-tambah-bantuan"
        class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all shadow-md">
        <i data-lucide="plus-circle" class="w-4 h-4"></i> Catat Bantuan Baru
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
            <h3 class="text-xl font-bold text-gray-800">Catat Bantuan Baru</h3>
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
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Penyerahan</label>
                        <input type="date" name="tgl_pelaksanaan" value="{{ date('Y-m-d') }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama / Judul Bantuan</label>
                        <input type="text" name="nama_bantuan" placeholder="Contoh: Bantuan Mesin Jahit Singer" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                    </div>
                    {{-- Bantuan (Sesuai Migrasi) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Rincian Barang/Jasa (Bantuan)</label>
                        <textarea name="bantuan" rows="2" placeholder="Jelaskan detail barang, misal: 2 Unit Laptop RAM 8GB" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required></textarea>
                    </div>
                    {{-- Deskripsi (Sesuai Migrasi) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Tambahan</label>
                        <textarea name="deskripsi" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" placeholder="Catatan atau tujuan bantuan..." required></textarea>
                    </div>
                    {{-- Foto Bukti --}}
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Foto Bukti</label>
                        <input type="file" name="foto_bukti" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" accept="image/*" required>
                    </div>
                    {{-- Status --}}
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="Terencana">Terencana</option>
                            <option value="Berjalan">Berjalan</option>
                            <option value="Selesai" selected>Selesai</option>
                        </select>
                    </div>
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
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Penyerahan</label>
                        <input type="date" name="tgl_pelaksanaan" id="edit_tgl_pelaksanaan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                    </div>
                    {{-- Nama Bantuan --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama / Judul Bantuan</label>
                        <input type="text" name="nama_bantuan" id="edit_nama_bantuan" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required>
                    </div>
                    {{-- Bantuan & Deskripsi --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Rincian Barang (Bantuan)</label>
                        <textarea name="bantuan" id="edit_bantuan" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required></textarea>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Tambahan</label>
                        <textarea name="deskripsi" id="edit_deskripsi" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400" required></textarea>
                    </div>
                    {{-- Foto --}}
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Ganti Foto (Opsional)</label>
                        <input type="file" name="foto_bukti" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" accept="image/*">
                    </div>
                    {{-- Status --}}
                    <div class="col-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                        <select name="status" id="edit_status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="Terencana">Terencana</option>
                            <option value="Berjalan">Berjalan</option>
                            <option value="Selesai">Selesai</option>
                        </select>
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
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Mitra Pemberi</label>
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
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Penyerahan</label>
                    <div id="detail_tgl" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 font-mono"></div>
                </div>

                {{-- Baris 3: Nama Bantuan --}}
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama / Judul Bantuan</label>
                    <div id="detail_nama_bantuan" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm font-bold text-indigo-700"></div>
                </div>

                {{-- Baris 4: Rincian Bantuan --}}
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Rincian Barang/Jasa (Bantuan)</label>
                    <div id="detail_bantuan" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 whitespace-pre-line italic"></div>
                </div>

                {{-- Baris 5: Deskripsi --}}
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Deskripsi Tambahan</label>
                    <div id="detail_deskripsi" class="w-full border border-gray-200 bg-gray-50 rounded-lg px-3 py-2 text-sm text-gray-700 leading-relaxed"></div>
                </div>

                {{-- Baris 6: Foto & Status --}}
                <div class="col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Foto Bukti</label>
                    <div class="mt-1">
                        <img id="detail_foto_bukti" src="" alt="Bukti" class="w-full h-40 object-cover rounded-lg border border-gray-200 shadow-sm">
                    </div>
                </div>
                <div class="col-span-1">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status Penyaluran</label>
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
    }

    function closeEditModal() {
        document.getElementById('modal-edit-bantuan').classList.add('hidden');
    }

    // 3. Fungsi Modal Detail
    function openDetailModal(item) {
        document.getElementById('modal-detail-bantuan').classList.remove('hidden');
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

        const fotoEl = document.getElementById('detail_foto_bukti');
        if (item.foto_bukti) {
            fotoEl.src = "/admin/bantuan/lihat-foto/" + item.foto_bukti;
            fotoEl.classList.remove('hidden');
        } else {
            fotoEl.classList.add('hidden');
        }
    }

    function closeDetailModal() {
        document.getElementById('modal-detail-bantuan').classList.add('hidden');
    }
    
    // 4. FUNGSI MODAL DELETE (KUNCI PERBAIKAN)
    function openModalDelete(id, nama) {
        // Hentikan paksa semua script luar (termasuk admin.layout)
        if (window.event) {
            window.event.preventDefault();
            window.event.stopPropagation();
            window.event.stopImmediatePropagation();
        }

        const modal = document.getElementById('modalDelete');
        const textLabel = document.getElementById('textDeleteName');
        const form = document.getElementById('formDeleteAction');

        textLabel.innerText = `Apakah Anda yakin ingin menghapus bantuan "${nama}"?`;
        form.action = `/admin/bantuan/delete/${id}`;

        modal.classList.remove('hidden');
        return false; // Memutus rantai eksekusi event
    }

    function closeModalDelete() {
        document.getElementById('modalDelete').classList.add('hidden');
    }

    // 5. Global Click Handler (Untuk menutup semua modal jika klik luar)
    window.addEventListener('click', function(e) {
        const modalTambah = document.getElementById('modal-tambah-bantuan');
        const modalEdit = document.getElementById('modal-edit-bantuan');
        const modalDetail = document.getElementById('modal-detail-bantuan');
        const modalDelete = document.getElementById('modalDelete');

        if (e.target == modalTambah) modalTambah.classList.add('hidden');
        if (e.target == modalEdit) modalEdit.classList.add('hidden');
        if (e.target == modalDetail) modalDetail.classList.add('hidden');
        if (e.target.classList.contains('bg-gray-900/60')) closeModalDelete();
    }, true); // 'true' di sini sangat penting untuk menangkap event lebih awal
</script>

@stop