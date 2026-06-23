@extends('admin.layout')

@section('title', 'Monitoring Bantuan - KUBE')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Monitoring Bantuan</h2>
        <p class="text-gray-500 mt-1">Kelola data monitoring bantuan KUBE.</p>
    </div>
    {{-- TOMBOL TAMBAH - Konsisten pakai atribut data-modal --}}
    @if(auth()->user()->role == 'pendamping')
    <button type="button" onclick="toggleModal('modal-tambah')"
        class="text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded-md transition shadow-sm font-medium text-sm">
        Tambah Monitoring
    </button>
@endif
</div>

{{-- 🔥 SUMMARY BOX --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    <div class="bg-green-50 p-4 rounded-lg shadow border border-green-200">
        <p class="text-sm text-green-600 font-medium">Sesuai</p>
        <h3 class="text-2xl font-bold text-green-700">{{ $monitoringList->where('kesesuaian','sesuai')->count() }}</h3>
    </div>
    <div class="bg-red-50 p-4 rounded-lg shadow border border-red-200">
        <p class="text-sm text-red-600 font-medium">Tidak Sesuai</p>
        <h3 class="text-2xl font-bold text-red-700">{{ $monitoringList->where('kesesuaian','tidak sesuai')->count() }}</h3>
    </div>
</div>

<div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
    {{-- Search Bar --}}
    <div class="relative flex-1 w-full md:w-auto">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
            </svg>
        </span>
        <input type="text" id="searchInput" placeholder="Cari nama KUBE, jenis bantuan..."
               onkeyup="searchTable()"
               class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    {{-- Ekspor PDF --}}
    <a href="{{ route('monitoring.pdf') }}"
       class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg transition duration-200">
        <i data-lucide="file-text" class="w-4 h-4"></i>
        Ekspor PDF
    </a>
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
            <th class="px-6 py-3">Tanggal</th>
            <th class="px-6 py-3">Kesesuaian</th>
            <th class="px-6 py-3">Catatan</th> <th class="px-6 py-3 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($monitoringList as $item)
        <tr class="border-b hover:bg-gray-50 searchable-row">
            <td class="px-6 py-4 flex justify-center">
                @if($item->foto_monitoring)
                    <img src="{{ asset('storage/'.$item->foto_monitoring) }}" class="w-12 h-12 rounded object-cover cursor-pointer border" onclick="showImage('{{ asset('storage/'.$item->foto_monitoring) }}')">
                @else
                    <span class="text-gray-400">-</span>
                @endif
            </td>
            <td class="px-6 py-4">{{ $item->jenisBantuan->jenis_bantuan ?? '-' }}</td>
            <td class="px-6 py-4">{{ $item->kube->nama_kube ?? '-' }}</td>
            <td class="px-6 py-4">{{ date('d-m-Y', strtotime($item->tanggal_monitoring)) }}</td>
            <td class="px-6 py-4">
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $item->kesesuaian == 'sesuai' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ ucfirst($item->kesesuaian) }}
                </span>
            </td>
            <td class="px-6 py-4 text-gray-600 max-w-[200px] truncate">
                {{ $item->catatan ?? '-' }} </td>
            <td class="px-6 py-4 text-center">
                <div class="flex items-center justify-center gap-2">
                    <button type="button" onclick='openDetailModal(@json($item))' class="text-blue-500 hover:text-blue-700"><i data-lucide="eye" class="w-5 h-5"></i></button>
                    @if(auth()->user()->role == 'pendamping')
                        <button type="button" onclick='openEditModal(@json($item))' class="text-amber-500 hover:text-amber-600"><i data-lucide="square-pen" class="w-5 h-5"></i></button>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" class="px-6 py-10 text-center text-gray-500">Belum ada data.</td></tr>
        @endforelse
    </tbody>
</table>
    </div>
</div>


{{-- MODAL FORM MONITORING --}}
<div id="modal-form-monitoring" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[80vh] flex flex-col">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold">Tambah Data Monitoring</h3>
            <button onclick="toggleModal('modal-form-monitoring')" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        
        <form id="form-monitoring" action="{{ route('monitoring.store') }}" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto">
            @csrf
            {{-- ID Pencairan akan diisi lewat JavaScript --}}
            <input type="hidden" name="id_pencairan" id="input-id-pencairan">

            <div class="mb-4">
                <label class="block text-sm font-medium">Tanggal Monitoring</label>
                <input type="date" name="tanggal_monitoring" class="w-full border p-2 rounded" required>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium">Kesesuaian</label>
                <select name="kesesuaian" class="w-full border p-2 rounded" required>
                    <option value="sesuai">Sesuai</option>
                    <option value="tidak sesuai">Tidak Sesuai</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Catatan</label>
                <textarea name="catatan" class="w-full border p-2 rounded"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Foto Monitoring</label>
                <input type="file" name="foto_monitoring" class="w-full border p-2 rounded" accept="image/*">
            </div>

<button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-bold transition">
    Simpan Monitoring
</button>        
</form>
    </div>
</div>

{{-- MODAL EDIT MONITORING --}}
<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg max-h-[80vh] flex flex-col">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold">Edit Data Monitoring</h3>
            <button onclick="toggleModal('modal-edit')" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        
        <form id="form-edit" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto">
    @csrf
    {{-- Input ID Monitoring --}}
    <input type="hidden" name="id" id="edit-id">
    
    {{-- WAJIB: Tambahkan input ini agar validasi 'required' di controller terpenuhi --}}
    <input type="hidden" name="id_jenis_bantuan" id="edit-id-jenis-bantuan">

    <div class="mb-4">
        <label class="block text-sm font-medium">Tanggal Monitoring</label>
        <input type="date" name="tanggal_monitoring" id="edit-tanggal" class="w-full border p-2 rounded" required>
    </div>
    
    <div class="mb-4">
        <label class="block text-sm font-medium">Kesesuaian</label>
        <select name="kesesuaian" id="edit-kesesuaian" class="w-full border p-2 rounded" required>
            <option value="sesuai">Sesuai</option>
            <option value="tidak sesuai">Tidak Sesuai</option>
        </select>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium">Catatan</label>
        <textarea name="catatan" id="edit-catatan" class="w-full border p-2 rounded"></textarea>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium">Ganti Foto (Opsional)</label>
        <input type="file" name="foto_monitoring" class="w-full border p-2 rounded" accept="image/*">
    </div>

<button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 font-bold transition">
    Update Monitoring
</button></form>
    </div>
</div>

{{-- MODAL DETAIL MONITORING --}}
<div id="modal-detail" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
        <div class="flex justify-between items-center border-b pb-4 mb-4">
            <h3 class="text-lg font-bold">Detail Monitoring</h3>
            <button onclick="toggleModal('modal-detail')" class="text-gray-400 hover:text-gray-600">✕</button>
        </div>
        
        <div id="detail-content" class="space-y-3">
            <div class="flex justify-center mb-4">
                <img id="detail-foto" src="" class="w-40 h-40 object-cover rounded border">
            </div>
            <p><strong>Jenis Bantuan:</strong> <span id="detail-jenis"></span></p>
            <p><strong>Nama KUBE:</strong> <span id="detail-kube"></span></p>
            <p><strong>Tanggal:</strong> <span id="detail-tanggal"></span></p>
            <p><strong>Nilai Bantuan:</strong> <span id="detail-nilai"></span></p>
            <p><strong>Catatan:</strong> <span id="detail-catatan"></span></p>
        </div>
    </div>
</div>

{{-- MODAL PILIH KUBE (Sistem Modal Terpusat) --}}
<div id="modal-tambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[80vh] flex flex-col">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="text-lg font-bold">Pilih KUBE untuk Dimonitoring</h3>
            <button onclick="toggleModal('modal-tambah')" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        <div class="p-6 overflow-y-auto">
            <table class="w-full text-sm text-left">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3">Nama KUBE</th>
            <th class="p-3">Jenis Bantuan</th>
            <th class="p-3">Nilai Bantuan</th>
            <th class="p-3">Tanggal Cair</th> <th class="p-3 text-center">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pencairanTersedia as $row)
        <tr class="border-b">
            <td class="p-3">{{ $row->nama_kube }}</td>
            <td class="p-3">{{ $row->jenis_bantuan }}</td>
            <td class="p-3">Rp {{ number_format($row->jumlah_bantuan, 0, ',', '.') }}</td>
            <td class="p-3">{{ date('d-m-Y', strtotime($row->tanggal_cair)) }}</td> <td class="p-3 text-center">
                <button type="button" 
                        onclick="bukaFormMonitoring('{{ $row->id_pencairan }}')" 
                        class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 text-xs">
                    Lakukan Monitoring
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
        </div>
    </div>
</div>

<script>
    // Fungsi Toggle Modal yang universal
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function bukaFormMonitoring(id) {
        document.getElementById('input-id-pencairan').value = id;
        toggleModal('modal-tambah');
        toggleModal('modal-form-monitoring');
    }

    function openDetailModal(item) {
        console.log("Data Item:", item);

        const imgElement = document.getElementById('detail-foto');
        if (item.foto_monitoring) {
            imgElement.src = "{{ asset('storage/') }}/" + item.foto_monitoring;
            imgElement.classList.remove('hidden');
        } else {
            imgElement.src = "";
            imgElement.classList.add('hidden');
        }
        
        document.getElementById('detail-jenis').innerText = item.jenis_bantuan?.jenis_bantuan || '-';
        document.getElementById('detail-kube').innerText = item.kube?.nama_kube || '-';
        document.getElementById('detail-tanggal').innerText = item.tanggal_monitoring || '-';
        document.getElementById('detail-catatan').innerText = item.catatan || '-';
        
        let nilai = 0;
        if (item.pencairan && item.pencairan.pengajuan) {
            nilai = item.pencairan.pengajuan.jumlah_bantuan;
        } else if (item.pengajuan) {
            nilai = item.pengajuan.jumlah_bantuan;
        }
        
        document.getElementById('detail-nilai').innerText = 'Rp ' + parseInt(nilai || 0).toLocaleString('id-ID');

        toggleModal('modal-detail');
    } // <--- Tutup kurung kurawal openDetailModal di sini

    // Sekarang fungsi ini berdiri sendiri di luar
    function openEditModal(item) {
    // Pastikan item.id_monitoring sesuai dengan primary key di database Anda (bisa jadi item.id)
    const id = item.id_monitoring || item.id; 
    
    document.getElementById('form-edit').action = "/monitoring/update/" + id;
    
    document.getElementById('edit-id').value = id;
    document.getElementById('edit-tanggal').value = item.tanggal_monitoring;
    document.getElementById('edit-kesesuaian').value = item.kesesuaian;
    document.getElementById('edit-catatan').value = item.catatan || '';
    
    // ISI DATA PENTING INI:
    document.getElementById('edit-id-jenis-bantuan').value = item.id_jenis_bantuan;
    
    toggleModal('modal-edit');
}

    function searchTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const table = document.querySelector("table");
        // Mengambil semua baris di dalam tbody
        const rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

        for (let i = 0; i < rows.length; i++) {
            const text = rows[i].textContent.toLowerCase();
            // Jika teks baris mengandung kata kunci, tampilkan, jika tidak sembunyikan
            rows[i].style.display = text.includes(filter) ? "" : "none";
        }
    }

</script>
@endsection