@extends('admin.layout')

@section('title', 'Data Mitra - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Data Mitra</span>
@stop

@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data Mitra</h2>
        <p class="text-gray-500 mt-1">Kelola data mitra kolaborasi KUBE.</p>
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
        <input type="text" id="searchInput" placeholder="Cari nama mitra atau PIC..."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    {{-- Ekspor PDF --}}
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
                                {{ rand(1, 10) }} Kali
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
                            <a href="{{ route('mitra.edit', $item->id_mitra) }}" class="text-amber-500 hover:text-amber-700">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('mitra.delete', $item->id_mitra) }}" method="POST" onsubmit="return confirm('Hapus data ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
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
<div id="modal-tambah-mitra" tabindex="-1" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
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
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none" required>
                            <option value="Aktif">Aktif</option>
                            <option value="Tidak Aktif">Tidak Aktif</option>
                        </select>
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
                                <input type="number" name="masa_berlaku" placeholder="Thn" class="w-full border border-gray-300 rounded-lg px-2 py-2 text-xs outline-none" required>
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
                        <div class="flex items-center border-2 border-dashed border-gray-300 rounded-lg p-4 justify-center flex-col hover:bg-gray-50 transition-colors cursor-pointer" onclick="document.getElementById('mouInput').click()">
                            <i data-lucide="upload-cloud" class="w-8 h-8 text-gray-400 mb-2"></i>
                            <input type="file" name="mou" id="mouInput" class="hidden" accept=".pdf,.jpg,.jpeg,.png" required>
                            <input type="text" id="mouLabel" readonly placeholder="Silahkan Upload MOU" 
                                class="text-center text-sm text-gray-500 bg-transparent outline-none cursor-pointer w-full">
                        </div>
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

{{-- SCRIPT SEARCH --}}
<script>
    const mouInput = document.getElementById('mouInput');
    const mouLabel = document.getElementById('mouLabel');

    if (mouInput) {
        mouInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                // Ambil nama file yang dipilih
                const fileName = this.files[0].name;
                // Masukkan nama file ke dalam input text label
                mouLabel.value = fileName;
            } else {
                mouLabel.value = "Belum ada file dipilih...";
            }
        });
    }
    document.getElementById('searchInput').addEventListener('keyup', function () {
        const keyword = this.value.toLowerCase();
        const rows = document.querySelectorAll('.searchable-row');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(keyword) ? '' : 'none';
        });
    });
</script>

@stop