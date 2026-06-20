@extends('admin.layout')

@section('title', 'Data Mitra - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Data Mitra</span>
@stop

@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data Mitra</h2>
        <p class="text-gray-500 mt-1">Kelola data mitra kolaborasi KUBE</p>
    </div>
    <div>
        {{-- Button Tambah --}}
        <button data-modal-target="modal-tambah-mitra" data-modal-toggle="modal-tambah-mitra"
            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition shadow-sm font-medium">
            Tambah Mitra
        </button>
    </div>
</div>

{{-- SUMMARY CARDS --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white p-4 rounded-lg shadow border">
        <p class="text-sm font-medium">Total Mitra</p>
        <h3 class="text-2xl font-bold text-gray-800">{{ $mitras->count() }}</h3>
    </div>
    <div class="bg-green-50 p-4 rounded-lg shadow border border-green-200">
        <p class="text-sm text-green-600">Mitra Aktif</p>
        <h3 class="text-4xl font-bold mt-1">{{ $mitras->where('status','Aktif')->count() }}</h3>
    </div>
    <div class="bg-red-50 p-4 rounded-lg shadow border border-red-200">
        <p class="text-sm text-red-600">Mitra Tidak Aktif</p>
        <h3 class="text-2xl font-bold text-red-700">{{ $mitras->where('status','Tidak Aktif')->count() }}</h3>
    </div>
</div>

{{-- TOOLBAR --}}
<div class="flex flex-wrap items-center gap-3 mb-4">
    {{-- Search --}}
    <div class="relative flex-1 min-w-200px">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <i data-lucide="search" class="h-4 w-4"></i>
        </span>
        <input type="text" id="searchInput" placeholder="Cari nama mitra...."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>
    <select id="filterStatus" onchange="filterByStatus(this.value)"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <option value="" {{ request('status') == '' ? 'selected' : '' }}>Semua Status</option>
        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
        <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Non-Aktif</option>
    </select>


    {{-- Ekspor PDF --}}
    <a href="{{ route('mitra.pdf') }}"
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
        </svg>
        Ekspor PDF
    </a>

    {{-- Ekspor Excel --}}
    <a href="{{ route('mitra.excel') }}"
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
        <table class="w-full text-sm text-left text-gray-500" id="mitraTable">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-4 py-3 text-center">No</th>
                    <th class="px-4 py-3">Nama Mitra</th>
                    <th class="px-4 py-3">Jenis</th>
                    <th class="px-4 py-3">Email</th>
                    <th class="px-4 py-3">Alamat</th>
                    <th class="px-4 py-3">Kolaborasi</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mitras as $index => $item)
                <tr class="border-t border-gray-100 hover:bg-gray-50 searchable-row">
                    <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">
                        <div class="text-gray-800 font-bold">{{ $item->nama_mitra }}</div>
                        <div class="text-[10px] text-gray-400">Tgl: {{ \Carbon\Carbon::parse($item->tgl_mou)->format('d/m/Y') }}</div>
                        <div class="text-[10px] text-orange-500 font-bold">Masa: {{ $item->masa_berlaku }} Tahun</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-gray-800 font-bold">{{ $item->jenis_mitra }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-1"><i data-lucide="mail" class="w-3 h-3"></i> {{ $item->email }}</div>
                        <div class="text-xs text-gray-400 italic font-medium">{{ $item->no_telp }}</div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-700">{{ $item->alamat }}</div>
                    </td>
                    {{-- Data Kolaborasi masih dummy --}}
                    <td class="px-4 py-3">
                        <div class="flex flex-col items-center">
                            <span class="bg-indigo-50 text-indigo-700 text-xs font-bold px-2.5 py-1 rounded-md border border-indigo-100">
                                {{ $item->bantuan_kolaborasi_count }} Kali
                            </span>
                            <p class="text-[10px] text-gray-400 mt-1 uppercase font-semibold italic">Kolaborasi</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($item->status == 'Aktif')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium ">Aktif</span>
                        @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-medium ">Non-Aktif</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex justify-center items-center gap-2">
                            <a href="{{ route('bantuan.index', ['id_mitra' => $item->id_mitra]) }}" 
                            class="text-indigo-500 hover:text-indigo-700" title="Riwayat Bantuan">
                                <i data-lucide="handshake" class="w-4 h-4"></i>
                            </a>
                            <button type="button" onclick='openDetailModal(@json($item))' class="text-blue-500 hover:text-blue-700">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button type="button" 
                                onclick='openEditModal(@json($item))'' 
                                class="text-amber-500 hover:text-amber-700">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </button>
                            <button type="button" 
                                    class="trigger-hapus-mitra text-red-500 hover:text-red-700"
                                    data-id="{{ $item->id_mitra }}"
                                    data-nama="{{ $item->nama_mitra }}">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-10 text-center text-gray-400 italic text-sm border-t">Belum ada data mitra yang tersimpan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH MITRA --}}
<div id="modal-tambah-mitra" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    
    {{-- Background Overlay untuk menutup modal saat diklik di luar area --}}
    <div class="fixed inset-0" data-modal-toggle="modal-tambah-mitra"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        {{-- Header --}}
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Tambah Mitra Baru</h3>
            <button type="button" data-modal-toggle="modal-tambah-mitra" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Form --}}
        <form action="{{ route('mitra.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            
            {{-- Body Content (Scrollable area) --}}
            <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
                <div class="grid grid-cols-2 gap-4">

                    {{-- Baris 1: Nama Mitra (Full Width) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Mitra</label>
                        <input type="text" name="nama_mitra" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 2: Jenis Usaha & Telepon --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Usaha</label>
                        <select name="jenis_mitra" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                            <option value="Akademisi">Akademisi</option>
                            <option value="LSM">LSM</option>
                            <option value="Perusahaan">Perusahaan</option>
                            <option value="Pemerintah">Pemerintah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Telepon</label>
                        <input type="text" name="no_telp" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 3: Email & Dokumen Pendukung (MOU) diletakkan Berdampingan Sejajar --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Dokumen Pendukung</label>
                        <div class="flex items-center border {{ $errors->has('mou') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} rounded-lg overflow-hidden bg-white">
                            {{-- Input File Asli (Tersembunyi) --}}
                            <input type="file" name="mou" id="mouInput" class="hidden" accept=".pdf,.jpg,.jpeg,.png" required>

                            {{-- Input Text Palsu untuk Label Nama File --}}
                            <input type="text" id="mouLabel" readonly 
                                placeholder="{{ $errors->has('mou') ? 'Gagal Upload!' : 'Silahkan Upload MOU (.pdf, .jpg, .png)' }}"
                                class="flex-1 px-3 py-2 text-sm {{ $errors->has('mou') ? 'text-red-600 font-bold' : 'text-gray-500' }} cursor-pointer bg-transparent outline-none"
                                onclick="document.getElementById('mouInput').click()">

                            {{-- Tombol Pilih File --}}
                            <button type="button"
                                onclick="document.getElementById('mouInput').click()"
                                class="bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold px-4 py-2 transition-colors whitespace-nowrap">
                                Pilih File
                            </button>
                        </div>
                        {{-- Pesan Error Validasi Laravel --}}
                        @if ($errors->has('mou'))
                            <div class="text-red-600 text-xs mt-1.5 font-bold flex items-center gap-1">
                                <span class="bg-red-600 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px]">!</span>
                                {{ $errors->first('mou') }}
                            </div>
                        @endif
                    </div>

                    {{-- Baris 4: Nama PIC & Telepon PIC --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama PIC</label>
                        <input type="text" name="nama_pic" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Telepon PIC</label>
                        <input type="text" name="telp_pic" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 5: Alamat & Masa Berlaku --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none resize-none" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Masa Berlaku</label>
                        <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <div>
                                <span class="text-[10px] text-gray-500 font-semibold uppercase block mb-1">Dari Tanggal</span>
                                <input type="date" name="tgl_mou" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-xs outline-none bg-white focus:ring-2 focus:ring-blue-400" required>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-500 font-semibold uppercase block mb-1">Masa (Tahun)</span>
                                <input type="number" name="masa_berlaku" min="1" oninput="if(this.value <1) this.value = 1;" placeholder="Thn" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-xs outline-none bg-white focus:ring-2 focus:ring-blue-400" required>
                            </div>
                        </div>
                    </div>

                    {{-- Data Status Hidden (Tetap disertakan tanpa merusak sistem) --}}
                    <input type="hidden" name="status" value="Aktif">
                    
                </div>
            </div>
            
            {{-- Footer Buttons --}}
            <div class="p-4 border-t bg-gray-50 flex justify-end gap-3">
                <button type="button" data-modal-toggle="modal-tambah-mitra" 
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" 
                    class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 shadow-md transition">
                    Simpan Mitra
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT MITRA --}}
<div id="modal-edit-mitra" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    
    {{-- Background Overlay untuk menutup modal saat diklik di luar area --}}
    <div class="fixed inset-0" onclick="closeEditModal()"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        {{-- Header --}}
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800">Edit Data Mitra</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Form Edit --}}
        <form id="editForm" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @method('PUT')
            
            {{-- Body Content (Scrollable area) --}}
            <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
                <div class="grid grid-cols-2 gap-4">

                    {{-- Baris 1: Nama Mitra (Full Width) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Mitra</label>
                        <input type="text" name="nama_mitra" id="edit_nama_mitra" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 2: Jenis Usaha & Telepon Resmi --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Usaha</label>
                        <select name="jenis_mitra" id="edit_jenis_mitra" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                            <option value="Akademisi">Akademisi</option>
                            <option value="LSM">LSM</option>
                            <option value="Perusahaan">Perusahaan</option>
                            <option value="Pemerintah">Pemerintah</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Telepon Resmi</label>
                        <input type="text" name="no_telp" id="edit_no_telp" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 3: Email Resmi & Update Dokumen Pendukung (Berjajar di Samping Email) --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email Resmi</label>
                        <input type="email" name="email" id="edit_email" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Update Dokumen <span class="text-[11px] font-normal text-gray-400">(Kosongkan jika tetap)</span></label>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden bg-white">
                            {{-- Input File Asli Tersembunyi --}}
                            <input type="file" name="mou" id="mouEditInput" class="hidden" accept=".pdf,.jpg,.jpeg,.png">

                            {{-- Input Text Palsu untuk Label Nama File --}}
                            <input type="text" id="mouEditLabel" readonly 
                                placeholder="Klik untuk ganti file MOU" 
                                class="flex-1 px-3 py-2 text-sm text-gray-500 cursor-pointer bg-transparent outline-none"
                                onclick="document.getElementById('mouEditInput').click()">

                            {{-- Tombol Pilih File --}}
                            <button type="button"
                                onclick="document.getElementById('mouEditInput').click()"
                                class="bg-teal-500 hover:bg-teal-600 text-white text-sm font-semibold px-4 py-2 transition-colors whitespace-nowrap">
                                Pilih File
                            </button>
                        </div>
                    </div>

                    {{-- Baris 4: Nama PIC & Telepon PIC --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama PIC</label>
                        <input type="text" name="nama_pic" id="edit_nama_pic" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Telepon PIC</label>
                        <input type="text" name="telp_pic" id="edit_telp_pic" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 5: Alamat & Masa Berlaku --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" id="edit_alamat" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none resize-none" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Masa Berlaku</label>
                        <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                            <div>
                                <span class="text-[10px] text-gray-500 font-semibold uppercase block mb-1">Mulai Tanggal</span>
                                <input type="date" name="tgl_mou" id="edit_tgl_mou" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-xs outline-none bg-white focus:ring-2 focus:ring-blue-400" required>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-500 font-semibold uppercase block mb-1">Durasi (Tahun)</span>
                                <input type="number" name="masa_berlaku" id="edit_masa_berlaku" min="1" oninput="if(this.value < 1) this.value = 1;" placeholder="Thn" class="w-full border border-gray-300 rounded-lg px-2 py-1.5 text-xs outline-none bg-white focus:ring-2 focus:ring-blue-400" required>
                            </div>
                        </div>
                    </div>

                    {{-- Input Data Status Hidden (Tetap dipertahankan di luar layout utama) --}}
                    <input type="hidden" name="status" id="edit_status">
                    
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
                    Update Mitra
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DETAIL MITRA --}}
<div id="modal-detail-mitra" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-950/50 p-4 backdrop-blur-sm">
    
    {{-- Background Overlay untuk menutup modal saat diklik di luar area --}}
    <div class="fixed inset-0" onclick="closeDetailModal()"></div>

    <div class="relative bg-white rounded-lg shadow-xl w-full max-w-6xl max-h-[90vh] overflow-hidden flex flex-col z-10">
        
        {{-- Header --}}
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
                <i data-lucide="info" class="w-5 h-5 text-blue-600"></i> Detail Informasi Mitra
            </h3>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Body Content (Scrollable area) --}}
        <div class="p-6 overflow-x-auto overflow-y-auto flex-1">
            <div class="grid grid-cols-2 gap-4">
                
                {{-- Baris 1: Nama Mitra (Full Width) --}}
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama Mitra</label>
                    <input type="text" id="detail_nama_mitra" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold text-gray-800 outline-none" readonly>
                </div>
                
                {{-- Baris 2: Jenis Usaha & Status --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Usaha</label>
                    <input type="text" id="detail_jenis_mitra" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 outline-none" readonly>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                    <input type="text" id="detail_status" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm font-bold outline-none" readonly>
                </div>

                {{-- Baris 3: Telepon Resmi & Email Resmi --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Telepon Resmi</label>
                    <input type="text" id="detail_no_telp" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 outline-none" readonly>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Email Resmi</label>
                    <input type="text" id="detail_email" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 outline-none" readonly>
                </div>
                
                {{-- Baris 4: Nama PIC & Telepon PIC --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama PIC</label>
                    <input type="text" id="detail_nama_pic" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 outline-none" readonly>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Telepon PIC</label>
                    <input type="text" id="detail_telp_pic" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 outline-none" readonly>
                </div>

                {{-- Baris 5: Alamat & Masa Berlaku --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Alamat</label>
                    <textarea id="detail_alamat" rows="4" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-800 outline-none resize-none" readonly></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Masa Berlaku</label>
                    <div class="grid grid-cols-2 gap-2 bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <div>
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block mb-1">Mulai Tanggal</span>
                            <input type="text" id="detail_tgl_mou" class="w-full bg-white border border-gray-300 rounded-lg px-2 py-1.5 text-xs text-gray-800 outline-none" readonly>
                        </div>
                        <div>
                            <span class="text-[10px] text-gray-500 font-semibold uppercase block mb-1">Durasi (Tahun)</span>
                            <input type="text" id="detail_masa_berlaku" class="w-full bg-white border border-gray-300 rounded-lg px-2 py-1.5 text-xs text-gray-800 outline-none" readonly>
                        </div>
                    </div>
                </div>
                
                {{-- Baris 6: Dokumen MOU (Full Width di bagian bawah) --}}
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-gray-700 mb-1">Dokumen Pendukung / MOU</label>
                    <div id="detail_mou_container" class="p-3 bg-gray-50 border border-gray-200 rounded-lg">
                        {{-- Elemen link/file akan di-inject otomatis ke sini oleh JS bawaanmu --}}
                    </div>
                </div>

            </div>
        </div>

        {{-- Footer Buttons --}}
        <div class="p-4 border-t bg-gray-50 flex justify-end gap-3">
            <button type="button" onclick="closeDetailModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Tutup
            </button>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        @if ($errors->any())
        const modalTambah = document.getElementById('modal-tambah-mitra');
        if (modalTambah) {
            modalTambah.classList.remove('hidden');
            modalTambah.classList.add('flex');
            // Opsional: Jika menggunakan backdrop manual, pastikan ditampilkan juga
        }
        @endif
        // --- 1. PENANGANAN LABEL & VALIDASI FILE (TAMBAH MITRA) ---
        const mouInput = document.getElementById('mouInput');
        const mouLabel = document.getElementById('mouLabel');

        if (mouInput) {
            mouInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    const fileSize = this.files[0].size / 1024 / 1024; // Convert ke MB
                    if (fileSize > 5) {
                        alert('Ukuran file terlalu besar! Maksimal 5MB. File Anda: ' + fileSize.toFixed(2) + ' MB');
                        this.value = ''; // Reset input
                        mouLabel.value = "Silahkan Upload MOU";
                    } else {
                        mouLabel.value = this.files[0].name;
                        mouLabel.classList.add('text-indigo-600', 'font-bold');
                    }
                }
            });
        }

        // --- 2. PENANGANAN SEARCH ---
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function () {
                const keyword = this.value.toLowerCase();
                document.querySelectorAll('.searchable-row').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
                });
            });
        }

        // --- 3. TRIGGER HAPUS MITRA ---
        const btnHapus = document.querySelectorAll('.trigger-hapus-mitra');
        btnHapus.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();

                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const modal = document.getElementById('modalDeleteMitra');
                const textLabel = document.getElementById('textDeleteNameMitra');
                const form = document.getElementById('formDeleteMitra');

                const baseUrl = window.location.origin + window.location.pathname.split('/mitra')[0];
                form.action = baseUrl + "/mitra/" + id;
                textLabel.innerText = `Apakah Anda yakin ingin menghapus mitra "${nama}"?`;

                modal.classList.remove('hidden');
                modal.classList.add('flex');
                if(window.lucide) lucide.createIcons();
            }, true);
        });
    });

    // --- 4. FUNGSI MODAL EDIT ---
    function openEditModal(mitra) {
        const modal = document.getElementById('modal-edit-mitra');
        const form = document.getElementById('editForm');
        form.action = "/admin/mitra/" + mitra.id_mitra;
        
        document.getElementById('edit_nama_mitra').value = mitra.nama_mitra || '';
        document.getElementById('edit_jenis_mitra').value = mitra.jenis_mitra || '';
        document.getElementById('edit_status').value = mitra.status || '';
        document.getElementById('edit_no_telp').value = mitra.no_telp || '';
        document.getElementById('edit_email').value = mitra.email || '';
        document.getElementById('edit_alamat').value = mitra.alamat || '';
        
        if (mitra.tgl_mou) {
            document.getElementById('edit_tgl_mou').value = mitra.tgl_mou.substring(0, 10);
        }
        
        document.getElementById('edit_masa_berlaku').value = mitra.masa_berlaku || '';
        document.getElementById('edit_nama_pic').value = mitra.nama_pic || '';
        document.getElementById('edit_telp_pic').value = mitra.telp_pic || '';

        const labelFile = document.getElementById('mouEditLabel');
        if (mitra.mou) {
            labelFile.value = "File Aktif: " + mitra.mou;
            labelFile.classList.add('text-indigo-600', 'font-bold');
        } else {
            labelFile.value = "Belum ada dokumen diunggah";
            labelFile.classList.remove('text-indigo-600');
        }
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        if(window.lucide) lucide.createIcons();
    }

    // Event Listener untuk Input File di Modal Edit
    const editMouInput = document.getElementById('mouEditInput');
    if(editMouInput) {
        editMouInput.addEventListener('change', function() {
            const labelFile = document.getElementById('mouEditLabel');
            if (this.files && this.files.length > 0) {
                const fileSize = this.files[0].size / 1024 / 1024;
                if (fileSize > 5) {
                    alert('Ukuran file terlalu besar! Maksimal 5MB.');
                    this.value = '';
                    labelFile.value = "Klik untuk ganti file MOU";
                } else {
                    labelFile.value = "File baru terpilih: " + this.files[0].name;
                    labelFile.classList.replace('text-indigo-600', 'text-green-600');
                }
            }
        });
    }

    // --- 5. FUNGSI MODAL DETAIL ---
    function openDetailModal(mitra) {
        const modal = document.getElementById('modal-detail-mitra');
        document.getElementById('detail_nama_mitra').value = mitra.nama_mitra || '';
        document.getElementById('detail_jenis_mitra').value = mitra.jenis_mitra || '';
        document.getElementById('detail_status').value = mitra.status || '';
        document.getElementById('detail_no_telp').value = mitra.no_telp || '';
        document.getElementById('detail_email').value = mitra.email || '';
        document.getElementById('detail_alamat').value = mitra.alamat || '';
        
        if(mitra.tgl_mou) {
            const d = new Date(mitra.tgl_mou);
            document.getElementById('detail_tgl_mou').value = d.toLocaleDateString('id-ID');
        }

        document.getElementById('detail_masa_berlaku').value = (mitra.masa_berlaku || 0) + " Tahun";
        document.getElementById('detail_nama_pic').value = mitra.nama_pic || '';
        document.getElementById('detail_telp_pic').value = mitra.telp_pic || '';
        
        const container = document.getElementById('detail_mou_container');
        if(mitra.mou) {
            
            const pdfUrl = `/admin/mitra/view-pdf/${mitra.id_mitra}`;
            container.innerHTML = `
                <a href="${pdfUrl}" target="_blank" 
                class="flex items-center gap-2 w-fit bg-blue-100 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-200 transition-all font-bold text-sm">
                    <i data-lucide="file-text" class="w-4 h-4"></i> Lihat Dokumen MOU
                </a>`;
        } else {
            container.innerHTML = `<span class="text-gray-400 italic text-sm">Tidak ada dokumen pendukung.</span>`;
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        if(window.lucide) lucide.createIcons();
    }

    // --- 6. FUNGSI CLOSE MODALS ---
    function closeEditModal() {
        const modal = document.getElementById('modal-edit-mitra');
        modal.classList.replace('flex', 'hidden');
    }

    function closeDetailModal() {
        const modal = document.getElementById('modal-detail-mitra');
        modal.classList.replace('flex', 'hidden');
    }

    function closeDeleteModalMitra() {
        const modal = document.getElementById('modalDeleteMitra');
        modal.classList.replace('flex', 'hidden');
    }

    function filterByStatus(status) {
        // Mengambil URL saat ini tanpa query string lawas
        let url = new URL(window.location.href);
        
        if (status) {
            // Jika memilih 'aktif' atau 'non-aktif', set query string (?status=aktif)
            url.searchParams.set('status', status);
        } else {
            // Jika memilih 'Semua Status', hapus parameter status dari URL
            url.searchParams.delete('status');
        }
        
        // Alihkan halaman ke URL yang baru
        window.location.href = url.toString();
    }
</script>
@stop