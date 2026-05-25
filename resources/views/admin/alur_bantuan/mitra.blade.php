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
</div>

{{-- SUMMARY CARDS --}}
<div class="flex gap-4 mb-6">
    <div class="bg-blue-500 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Total Mitra</p>
        <p class="text-4xl font-bold mt-1">{{ $mitras->count() }}</p>
    </div>
    <div class="bg-green-400 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Mitra Aktif</p>
        <p class="text-4xl font-bold mt-1">{{ $mitras->where('status','Aktif')->count() }}</p>
    </div>
    <div class="bg-orange-400 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Mitra Tidak Aktif</p>
        <p class="text-4xl font-bold mt-1">{{ $mitras->where('status','Tidak Aktif')->count() }}</p>
    </div>
</div>

{{-- TOOLBAR --}}
<div class="flex flex-wrap items-center gap-3 mb-4">
    {{-- Search --}}
    <div class="relative flex-1 min-w-[200px]">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <i data-lucide="search" class="h-4 w-4"></i>
        </span>
        <input type="text" id="searchInput" placeholder="Cari nama mitra...."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

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

    {{-- Button Tambah --}}
    <button data-modal-target="modal-tambah-mitra" data-modal-toggle="modal-tambah-mitra"
        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition-all">
        + Tambah Mitra
    </button>
   
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
                    <td class="px-4 py-3">
                        @if($item->status == 'Aktif')
                        <span class="bg-green-100 text-green-700 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Aktif</span>
                        @else
                        <span class="bg-gray-100 text-gray-600 text-[10px] font-bold px-2.5 py-1 rounded-full uppercase">Non-Aktif</span>
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
<div id="modal-tambah-mitra" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center ">
    <div class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="text-xl font-bold text-gray-800">Tambah Mitra Baru</h3>
            <button type="button" data-modal-toggle="modal-tambah-mitra" class="text-gray-400 hover:text-red-500 text-xl font-bold">✕</button>
        </div>

        {{-- Form --}}
        <form action="{{ route('mitra.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-6 overflow-y-auto max-h-[75vh]">
                <div class="grid grid-cols-2 gap-4">

                    {{-- Baris 1: Nama Mitra (Full Width) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Mitra</label>
                        <input type="text" name="nama_mitra" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 2: Jenis Usaha & Status --}}
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
                        <label class="block text-sm font-bold text-gray-700 mb-1"></label>
                        <input type="hidden" name="status" value="Aktif">
                    </div>

                    {{-- Baris 3: Telepon & Email --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Telepon</label>
                        <input type="text" name="no_telp" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 4: Alamat & Masa Berlaku --}}
                    <div class="row-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Masa Berlaku</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <span class="text-[10px] text-gray-500 italic">Dari Tanggal</span>
                                <input type="date" name="tgl_mou" class="w-full border border-gray-300 rounded-lg px-2 py-2 text-xs outline-none" required>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-500 italic">Masa (Tahun)</span>
                                <input type="number" name="masa_berlaku" min="1" oninput="if(this.value <1) this.value = 1;" placeholder="Thn" class="w-full border border-gray-300 rounded-lg px-2 py-2 text-xs outline-none" required>
                            </div>
                        </div>
                    </div>

                    {{-- Baris 5: Nama PIC & Telepon PIC --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama PIC</label>
                        <input type="text" name="nama_pic" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Telepon</label>
                        <input type="text" name="telp_pic" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Baris 6: Dokumen Pendukung (MOU) --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Dokumen Pendukung</label>
                        <div class="flex items-center border-2 border-dashed {{ $errors->has('mou') ? 'border-red-500 bg-red-50' : 'border-gray-300' }} rounded-lg p-4 justify-center flex-col hover:bg-gray-50 transition-colors cursor-pointer" onclick="document.getElementById('mouInput').click()">
                            <i data-lucide="upload-cloud" class="w-8 h-8 {{ $errors->has('mou') ? 'text-red-500' : 'text-gray-400' }} mb-2"></i>
                            <input type="file" name="mou" id="mouInput" class="hidden" accept=".pdf,.jpg,.jpeg,.png" required>
                            <input type="text" id="mouLabel" readonly placeholder= "{{ $errors->has('mou') ? 'Gagal Upload!' : 'Silahkan Upload MOU'}}"
                                class="text-center text-sm {{ $errors->has('mou') ? 'text-red-600 font-bold' : 'text-gray-500' }} bg-transparent outline-none cursor-pointer w-full">
                        </div>
                        {{-- Pesan Error --}}
                        @if ($errors->has('mou'))
                            <div class="text-red-600 text-xs mt-2 font-bold flex items-center gap-1">
                                <span class="bg-red-600 text-white rounded-full w-4 h-4 flex items-center justify-center text-[10px]">!</span>
                                {{ $errors->first('mou') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Footer Buttons --}}
            <div class="flex justify-end gap-4 p-6 border-t bg-gray-50">
                <button type="button" data-modal-toggle="modal-tambah-mitra" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-10 rounded-lg transition-all">Batal</button>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-10 rounded-lg shadow-lg transition-all">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT MITRA --}}
    <div id="modal-edit-mitra" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between p-4 border-b bg-gray-50">
            <h3 class="text-xl font-bold text-gray-800">Edit Data Mitra</h3>
            <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-red-500 text-xl font-bold">✕</button>
        </div>

        {{-- Form Edit --}}
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="p-6 overflow-y-auto max-h-[75vh]">
                <div class="grid grid-cols-2 gap-4">
                    
                    {{-- Nama Mitra --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama Mitra</label>
                        <input type="text" name="nama_mitra" id="edit_nama_mitra" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Jenis Usaha & Status --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Jenis Usaha</label>
                        <select name="jenis_mitra" id="edit_jenis_mitra" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                            <option value="Akademisi">Akademisi</option>
                            <option value="LSM">LSM</option>
                            <option value="Perusahaan">Perusahaan</option>
                            <option value="Pemerintah">Pemerintah</option>
                        </select>
                    </div>
                    <div class="invisible">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                        <input type="hidden" name="status" id="edit_status">
                    </div>

                    {{-- Telepon & Email --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Telepon Resmi</label>
                        <input type="text" name="no_telp" id="edit_no_telp" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Email Resmi</label>
                        <input type="email" name="email" id="edit_email" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Alamat & Masa Berlaku --}}
                    <div class="row-span-1">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Alamat</label>
                        <textarea name="alamat" id="edit_alamat" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Masa Berlaku</label>
                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <span class="text-[10px] text-gray-400 italic">Mulai Tanggal</span>
                                <input type="date" name="tgl_mou" id="edit_tgl_mou" class="w-full border border-gray-300 rounded-lg px-2 py-2 text-xs outline-none" required>
                            </div>
                            <div>
                                <span class="text-[10px] text-gray-400 italic">Durasi (Tahun)</span>
                                <input type="number" name="masa_berlaku" id="edit_masa_berlaku" min="1" oninput="if(this.value < 1) this.value = 1;" placeholder="Thn" class="w-full border border-gray-300 rounded-lg px-2 py-2 text-xs outline-none" required>
                            </div>
                        </div>
                    </div>

                    {{-- PIC --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama PIC</label>
                        <input type="text" name="nama_pic" id="edit_nama_pic" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Telepon PIC</label>
                        <input type="text" name="telp_pic" id="edit_telp_pic" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                    </div>

                    {{-- Dokumen MOU --}}
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Update Dokumen (Kosongkan jika tetap)</label>
                        <div class="flex items-center border-2 border-dashed border-gray-300 rounded-lg p-4 justify-center flex-col hover:bg-gray-50 transition-colors cursor-pointer bg-gray-50/50" onclick="document.getElementById('mouEditInput').click()">
                            <i data-lucide="upload-cloud" class="w-6 h-6 text-gray-400 mb-1"></i>
                            <input type="file" name="mou" id="mouEditInput" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                            <input type="text" id="mouEditLabel" readonly placeholder="Klik untuk ganti file MOU" 
                                class="text-center text-xs text-gray-500 bg-transparent outline-none cursor-pointer w-full font-medium">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Footer Buttons --}}
            <div class="flex justify-end gap-3 p-4 border-t bg-gray-50">
                <button type="button" onclick="closeEditModal()" 
                    class="bg-gray-400 hover:bg-gray-500 text-white font-bold px-10 py-2 rounded-lg transition-all">
                    Batal
                </button>
                <button type="submit" 
                    class="bg-amber-500 hover:bg-amber-600 text-white font-bold px-10 py-2 rounded-lg shadow-lg transition-all">
                    Update Mitra
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DETAIL MITRA --}}
<div id="modal-detail-mitra" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="relative w-full max-w-2xl bg-white rounded-xl shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between p-4 border-b bg-blue-50">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i data-lucide="info" class="w-5 h-5 text-blue-600"></i> Detail Informasi Mitra
            </h3>
            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-red-500 text-xl font-bold">✕</button>
        </div>

        <div class="p-6 overflow-y-auto max-h-[75vh]">
            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Mitra</label>
                    <input type="text" id="detail_nama_mitra" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm font-semibold text-gray-800 outline-none" readonly>
                </div>
                
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Jenis Usaha</label>
                    <input type="text" id="detail_jenis_mitra" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-800 outline-none" readonly>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Status</label>
                    <input type="text" id="detail_status" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm font-bold outline-none" readonly>
                </div>

                <div><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Telepon</label><input type="text" id="detail_no_telp" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" readonly></div>
                <div><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Email</label><input type="text" id="detail_email" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" readonly></div>
                
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Alamat</label>
                    <textarea id="detail_alamat" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none resize-none" readonly></textarea>
                </div>

                <div><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tanggal MoU</label><input type="text" id="detail_tgl_mou" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" readonly></div>
                <div><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Masa Berlaku</label><input type="text" id="detail_masa_berlaku" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" readonly></div>
                
                <div><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama PIC</label><input type="text" id="detail_nama_pic" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" readonly></div>
                <div><label class="block text-xs font-bold text-gray-400 uppercase mb-1">Telp PIC</label><input type="text" id="detail_telp_pic" class="w-full bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none" readonly></div>
                
                <div class="col-span-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Dokumen MOU</label>
                    <div id="detail_mou_container" class="mt-1">
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex justify-end p-4 border-t bg-gray-50">
            <button type="button" onclick="closeDetailModal()" class="bg-gray-800 hover:bg-gray-900 text-white font-bold px-8 py-2 rounded-lg transition-all">
                Tutup
            </button>
        </div>
    </div>
</div>

<div id="modalDeleteMitra" tabindex="-1" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-black bg-opacity-50 p-4">
    <div class="relative w-full max-w-md bg-white rounded-xl shadow-2xl overflow-hidden">
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                <i data-lucide="alert-triangle" class="h-10 w-10 text-red-600"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-500 mb-6" id="textDeleteNameMitra"></p>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModalMitra()" class="flex-1 px-4 py-2 bg-gray-100 text-gray-800 font-bold rounded-lg">Batal</button>
                <form id="formDeleteMitra" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white font-bold rounded-lg shadow-md">Ya, Hapus</button>
                </form>
            </div>
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
</script>

@stop