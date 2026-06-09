@extends('admin.layout')

@section('title', 'Monitoring Bantuan - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Monitoring Bantuan</span>
@stop

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Monitoring Bantuan</h2>
        <p class="text-gray-500 mt-1">Kelola data monitoring bantuan KUBE.</p>
    </div>
    <div>
        @if(auth()->user()->role == 'pendamping')
        <button type="button" onclick="openTambahModal()"
            class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition shadow-sm font-medium text-sm">
            Tambah Monitoring
        </button>
        @endif
    </div>
</div>

{{-- 🔥 SUMMARY BOX --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    {{-- Sesuai --}}
    <div class="bg-green-50 p-4 rounded-lg shadow border border-green-200">
        <p class="text-sm text-green-600 font-medium">Sesuai</p>
        <h3 class="text-2xl font-bold text-green-700">
            {{ $monitoring->where('kesesuaian','sesuai')->count() }}
        </h3>
    </div>

    {{-- Tidak Sesuai --}}
    <div class="bg-red-50 p-4 rounded-lg shadow border border-red-200">
        <p class="text-sm text-red-600 font-medium">Tidak Sesuai</p>
        <h3 class="text-2xl font-bold text-red-700">
            {{ $monitoring->where('kesesuaian','tidak sesuai')->count() }}
        </h3>
    </div>
</div>

{{-- 🛠️ TOOLBAR & SEARCH --}}
<div class="bg-white mb-4 rounded-lg shadow-sm border p-4">
    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
        
        {{-- SEARCH INPUT --}}
        <div class="relative w-full flex-1">
            <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                <i data-lucide="search" class="w-4 h-4"></i>
            </span>
            <input type="text" id="searchInput" placeholder="Cari data monitoring berdasarkan jenis bantuan, nama KUBE, pendamping, kesesuaian atau catatan..."
                class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- ACTION BUTTONS --}}
        <div class="flex items-center gap-2 w-full md:w-auto justify-end shrink-0">
    <a href="{{ route('monitoring.pdf') }}"
        class="flex items-center justify-center bg-red-500 hover:bg-red-600 text-white text-sm px-5 py-2 rounded-lg transition shadow-sm font-medium">
        Ekspor PDF
    </a>
</div>

    </div>
</div>

{{-- 📊 TABEL UTAMA --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border overflow-hidden">
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-6 py-3 text-center">Foto</th>
                    <th class="px-6 py-3">Jenis Bantuan</th>
                    <th class="px-6 py-3">Nama KUBE</th>
                    <th class="px-6 py-3">Pendamping</th>
                    <th class="px-6 py-3">Tanggal</th>
                    <th class="px-6 py-3">Kesesuaian</th>
                    <th class="px-6 py-3">Catatan</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monitoring as $item)
                <tr class="border-b hover:bg-gray-50 transition-colors searchable-row">
                    
                    <td class="px-6 py-4 flex justify-center">
                        @if($item->foto_monitoring)
                            <img src="{{ asset('storage/'.$item->foto_monitoring) }}"
                                class="rounded shadow-sm cursor-pointer border hover:scale-105 transition duration-200"
                                style="width:50px;height:50px;object-fit:cover"
                                onclick="showImage('{{ asset('storage/'.$item->foto_monitoring) }}')">
                        @else
                            <span class="text-gray-400 font-mono">-</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $item->jenisBantuan->jenis_bantuan ?? '-' }}
                    </td>

                    <td class="px-6 py-4 text-gray-800">
                        {{ $item->kube->nama_kube ?? '-' }}
                    </td>

                    <td class="px-6 py-4 text-gray-700">
                        {{ $item->pendamping->nama_pendamping ?? '-' }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap font-mono text-gray-700">
                        {{ date('d-m-Y', strtotime($item->tanggal_monitoring)) }}
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap">
    @if($item->kesesuaian == 'sesuai')
        {{-- Badge Sesuai (Hijau Oval Sempurna) --}}
        <span class="px-4 py-1.5 text-xs font-semibold rounded-full bg-green-100 text-green-700 border border-green-200">
            Sesuai
        </span>
    @else
        {{-- Badge Tidak Sesuai (Merah Oval Sempurna) --}}
        <span class="px-4 py-1.5 text-xs font-semibold rounded-full bg-red-100 text-red-700 border border-red-200">
            Tidak Sesuai
        </span>
    @endif
</td>

                    <td class="px-6 py-4 max-w-xs truncate text-gray-600">
                        {{ $item->catatan ?? '-' }}
                    </td>

                    {{-- 📊 BAGIAN KOLOM AKSI PADA TABEL --}}
<td class="px-6 py-4 text-center">
    <div class="flex items-center justify-center gap-4"> {{-- Gap diganti ke 4 agar jaraknya pas dan rapi seperti di gambar --}}
        
        {{-- Icon Detail (Mata) --}}
        <button type="button" onclick='openDetailModal(@json($item))'
            class="text-blue-500 hover:text-blue-700 transition" title="Detail">
            <i data-lucide="eye" class="w-5 h-5"></i>
        </button>

        {{-- Icon Edit (Kotak Pensil - DISAMAKAN SESUAI GAMBAR) --}}
        @if(auth()->user()->role == 'pendamping')
        <button type="button" onclick='openEditModal(@json($item))'
            class="text-amber-500 hover:text-amber-600 transition" title="Edit">
            <i data-lucide="square-pen" class="w-5 h-5"></i>
        </button>
        @endif

        {{-- Icon Hapus (Tempat Sampah) --}}
        <form action="{{ route('monitoring.delete',$item->id_monitoring) }}" method="POST" 
              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data monitoring ini?')" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-500 hover:text-red-700 transition" title="Hapus">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
            </button>
        </form>
        
    </div>
</td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-gray-500 italic">
                        Belum ada data monitoring bantuan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH MONITORING (Id disesuaikan & sistem toggle murni class hidden) --}}
<div id="modal-tambah-monitoring" tabindex="-1"
    class="hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40 backdrop-blur-sm">

    <div class="relative p-4 w-full max-w-lg">
        <div class="bg-white rounded-lg shadow-xl overflow-hidden">

            {{-- HEADER --}}
            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Monitoring</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Isi data monitoring dengan lengkap.</p>
                </div>
                <button type="button" onclick="closeTambahModal()"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center font-bold">
                    ✕
                </button>
            </div>

            {{-- FORM --}}
            <form action="{{ route('monitoring.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="p-5 space-y-3">
                    {{-- JENIS --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Jenis Bantuan</label>
                        <select name="id_jenis_bantuan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            @foreach($jenis as $j)
                            <option value="{{ $j->id_jenis_bantuan }}">{{ $j->jenis_bantuan }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- KUBE --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">KUBE</label>
                        <input type="text" value="KUBE Fa Santoso Tbk"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100 text-gray-600 focus:outline-none" readonly>
                        <input type="hidden" name="id_kube" value="1">
                    </div>

                    {{-- PENDAMPING --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Pendamping</label>
                        <input type="text" value="Rana"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100 text-gray-600 focus:outline-none" readonly>
                        <input type="hidden" name="id_pendamping" value="1">
                    </div>

                    {{-- TANGGAL --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Tanggal Monitoring</label>
                        <input type="date" name="tanggal_monitoring"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    {{-- KESESUAIAN --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Kesesuaian</label>
                        <select name="kesesuaian"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            <option value="sesuai">Sesuai</option>
                            <option value="tidak sesuai">Tidak Sesuai</option>
                        </select>
                    </div>

                    {{-- CATATAN --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Catatan</label>
                        <textarea name="catatan" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 focus:outline-none"></textarea>
                    </div>

                    {{-- FOTO --}}
                    <div>
                        <label class="text-sm font-medium text-gray-700 block mb-1">Foto</label>
                        <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                            <input type="file" name="foto_monitoring" id="fotoMonitoring" class="hidden" accept="image/*">
                            <input type="text" id="fotoMonitoringLabel" readonly
                                class="flex-1 px-3 py-2 text-sm text-gray-500 cursor-pointer focus:outline-none"
                                onclick="document.getElementById('fotoMonitoring').click()">
                            <button type="button"
                                onclick="document.getElementById('fotoMonitoring').click()"
                                class="bg-gray-400 hover:bg-gray-300 text-black text-sm px-4 py-2 transition font-medium">
                                Pilih File
                            </button>
                        </div>
                    </div>
                </div>

                {{-- BUTTON ACTIONS (Ditambahkan bg-gray-50 agar matching dengan modal edit) --}}
                <div class="p-4 border-t flex justify-end gap-2 bg-gray-50 rounded-b-lg">
                    <button type="button" onclick="closeTambahModal()"
                        class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm transition font-medium">
                        Batal
                    </button>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition font-medium">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- MODAL PREVIEW FOTO --}}
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-[60] backdrop-blur-xs">
    <img id="previewImage" class="max-h-[80%] rounded shadow-2xl border-4 border-white">
</div>

{{-- MODAL DETAIL MONITORING --}}
<div id="modal-detail-monitoring"
    class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-bold text-lg text-gray-800">Detail Monitoring</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 font-bold text-lg">✕</button>
        </div>
        <div class="p-5 space-y-3">
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-1">Jenis Bantuan</label>
                <input id="detail_jenis" class="w-full border rounded-lg p-2 bg-gray-100 text-sm focus:outline-none" readonly>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-1">Tanggal</label>
                <input id="detail_tanggal" class="w-full border rounded-lg p-2 bg-gray-100 text-sm focus:outline-none" readonly>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-1">Kesesuaian</label>
                <input id="detail_kesesuaian" class="w-full border rounded-lg p-2 bg-gray-100 text-sm focus:outline-none" readonly>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-1">Catatan</label>
                <textarea id="detail_catatan" class="w-full border rounded-lg p-2 bg-gray-100 text-sm focus:outline-none" readonly></textarea>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700 block mb-1">Foto</label>
                <img id="detail_foto" class="w-full rounded-lg mt-2 max-h-[350px] object-cover border shadow-sm">
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT MONITORING --}}
<div id="modal-edit-monitoring"
    class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center backdrop-blur-sm">
    <div class="bg-white rounded-xl shadow-xl w-full max-w-lg">
        <div class="p-5 border-b flex justify-between items-center">
            <h3 class="font-bold text-lg text-gray-800">Edit Monitoring</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 font-bold text-lg">✕</button>
        </div>
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="p-5 space-y-3">
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Jenis Bantuan</label>
                    <select name="id_jenis_bantuan" id="edit_jenis" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-400">
                        @foreach($jenis as $j)
                        <option value="{{ $j->id_jenis_bantuan }}">{{ $j->jenis_bantuan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Tanggal Monitoring</label>
                    <input type="date" name="tanggal_monitoring" id="edit_tanggal" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-400">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Kesesuaian</label>
                    <select name="kesesuaian" id="edit_kesesuaian" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-400">
                        <option value="sesuai">Sesuai</option>
                        <option value="tidak sesuai">Tidak Sesuai</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Catatan</label>
                    <textarea name="catatan" id="edit_catatan" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-400"></textarea>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Foto Saat Ini</label>
                    <img id="edit_preview" class="w-32 rounded border shadow-sm mb-2 object-cover">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700 block mb-1">Ganti Foto</label>
                    <input type="file" name="foto_monitoring" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-400">
                </div>
            </div>
            <div class="p-4 border-t flex justify-end gap-2 bg-gray-50 rounded-b-xl">
                <button type="button" onclick="closeEditModal()" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg text-sm transition">
                    Batal
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Pencarian Real-time Tabel
document.getElementById('searchInput').addEventListener('keyup', function(){
    let val = this.value.toLowerCase();
    document.querySelectorAll('.searchable-row').forEach(row=>{
        row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
    });
});

// Modal Preview Gambar Besar
function showImage(src){
    document.getElementById('previewImage').src = src;
    document.getElementById('imageModal').classList.remove('hidden');
}
document.getElementById('imageModal').onclick = function(){
    this.classList.add('hidden');
}

// Custom text penamaan file upload
document.getElementById('fotoMonitoring').addEventListener('change', function() {
    let filename = this.files[0] ? this.files[0].name : '';
    document.getElementById('fotoMonitoringLabel').value = filename;
});

// Penanganan Modal TAMBAH MONITORING (Menggunakan fungsi murni agar tidak bentrok)
function openTambahModal() {
    document.getElementById('modal-tambah-monitoring').classList.remove('hidden');
}
function closeTambahModal() {
    document.getElementById('modal-tambah-monitoring').classList.add('hidden');
}

// Penanganan Modal DETAIL
function openDetailModal(data) {
    document.getElementById('detail_jenis').value = data.jenis_bantuan?.jenis_bantuan || '';
    document.getElementById('detail_tanggal').value = data.tanggal_monitoring;
    document.getElementById('detail_kesesuaian').value = data.kesesuaian;
    document.getElementById('detail_catatan').value = data.catatan ?? '';
    if(data.foto_monitoring) {
        document.getElementById('detail_foto').src = "/storage/" + data.foto_monitoring;
    }
    document.getElementById('modal-detail-monitoring').classList.remove('hidden');
}
function closeDetailModal() {
    document.getElementById('modal-detail-monitoring').classList.add('hidden');
}

// Penanganan Modal EDIT
function openEditModal(data) {
    document.getElementById('editForm').action = "/monitoring/update/" + data.id_monitoring;
    document.getElementById('edit_jenis').value = data.id_jenis_bantuan;
    document.getElementById('edit_tanggal').value = data.tanggal_monitoring;
    document.getElementById('edit_kesesuaian').value = data.kesesuaian;
    document.getElementById('edit_catatan').value = data.catatan ?? '';
    if(data.foto_monitoring) {
        document.getElementById('edit_preview').src = "/storage/" + data.foto_monitoring;
    }
    document.getElementById('modal-edit-monitoring').classList.remove('hidden');
}
function closeEditModal() {
    document.getElementById('modal-edit-monitoring').classList.add('hidden');
}
</script>
@stop