@extends('admin.layout')

@section('title', 'Data Pendamping - KUBE')

@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold">Data Pendamping</h2>
        <p class="text-gray-500">Kelola data pendamping</p>
    </div>
</div>

{{-- SUMMARY --}}
<div class="flex gap-4 mb-6">
    <div class="bg-orange-400 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Pendamping Aktif</p>
        <p class="text-4xl font-bold mt-1">{{ $pendamping->where('status','Aktif')->count() }}</p>
    </div>
    <div class="bg-green-300 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Pendamping Non-Aktif</p>
        <p class="text-4xl font-bold mt-1">{{ $pendamping->where('status','Tidak Aktif')->count() }}</p>
    </div>
</div>


{{-- TOOLBAR: Search + Export + Tambah --}}
<div class="flex flex-wrap items-center gap-3 mb-4">

    {{-- Search --}}
    <div class="relative flex-1 min-w-[200px]">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
            </svg>
        </span>
        <input type="text" id="searchInput" placeholder="Cari..."
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

    {{-- Tambah Pendamping --}}
    <button onclick="openModal()"
    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
    + Tambah Pendamping
</button>

</div>

{{-- TABLE --}}
<table class="w-full border">
    <thead class="bg-gray-200">
        <tr>
            <th>Foto</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>Kecamatan</th>
            <th>No HP</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($pendamping as $item)
        <tr class="searchable-row border-t">
            <td>
                @if($item->foto)
                <img src="{{ asset('storage/foto_pendamping/'.$item->foto) }}" width="40">
                @endif
            </td>
            <td>{{ $item->nama_pendamping }}</td>
            <td>{{ $item->nik }}</td>
            <td>{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
            <td>{{ $item->no_hp }}</td>
            <td>{{ $item->status }}</td>
            <td class="px-4 py-2">
    <div class="flex items-center gap-2">
        {{-- Tombol Edit (Orange) --}}
        <button type="button" 
            onclick="openEditModal({ $item })"
            class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1.5 rounded text-xs font-medium transition">
            Edit
        </button>
        
        {{-- Form & Tombol Hapus (Hijau) --}}
        <form action="{{ route('pendamping.delete', $item->id_pendamping) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" 
                onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')"
                class="bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-xs font-medium transition">
                Hapus
            </button>
        </form>
    </div>
</td>
        </tr>
        @endforeach
    </tbody>
</table>

{{-- MODAL --}}
<div id="modal"
    onclick="closeModal()"
    class="hidden fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">

    <div class="bg-white w-[600px] max-h-[90vh] rounded-xl shadow-lg flex flex-col"
        onclick="event.stopPropagation()">

        {{-- HEADER --}}
        <div class="px-5 py-3 border-b">
            <h2 class="text-lg font-semibold">Tambah Pendamping</h2>
        </div>

        {{-- BODY --}}
        <div class="p-5 overflow-y-auto">

            <form action="{{ route('pendamping.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

    <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">

    <div class="bg-white w-[700px] max-h-[90vh] rounded-xl shadow-lg flex flex-col">

        {{-- HEADER --}}
        <div class="p-4 border-b font-semibold text-lg">
            Tambah Pendamping
        </div>

        {{-- BODY (SCROLLABLE) --}}
        <div class="p-4 overflow-y-auto">

            <div class="grid grid-cols-2 gap-4">

                {{-- NIK --}}
                <div class="col-span-2">
                    <label class="block text-sm font-semibold mb-1">NIK</label>
                    <input name="nik" class="w-full border rounded-lg px-3 py-2">
                </div>

                {{-- Nama --}}
                <div class="col-span-2">
                    <label class="block text-sm font-semibold mb-1">Nama Pendamping</label>
                    <input name="nama_pendamping" class="w-full border rounded-lg px-3 py-2">
                </div>

                {{-- Jenis Kelamin --}}
                <div class="col-span-2">
                    <label class="block text-sm font-semibold mb-1">Jenis Kelamin</label>
                    <div class="flex gap-6 mt-2">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="jenis_kelamin" value="L" class="accent-blue-600">
                            Laki-laki
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="jenis_kelamin" value="P" class="accent-pink-500">
                            Perempuan
                        </label>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold">Tempat Lahir</label>
                    <input name="tempat_lahir" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div class="col-span-2">
                    <label class="text-sm font-semibold">Alamat</label>
                    <textarea name="alamat" class="w-full border rounded-lg px-3 py-2"></textarea>
                </div>

                <div>
                    <label class="text-sm font-semibold">No HP</label>
                    <input name="no_hp" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Email</label>
                    <input name="email" class="w-full border rounded-lg px-3 py-2">
                </div>

                <div>
                    <label class="text-sm font-semibold">Pendidikan</label>
                    <select name="pendidikan_terakhir" class="w-full border rounded-lg px-3 py-2">
                        <option>SMA</option>
                        <option>D3</option>
                        <option>S1</option>
                        <option>S2</option>
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">Kecamatan</label>
                    <select name="id_kecamatan" class="w-full border rounded-lg px-3 py-2">
                        @foreach($kecamatan as $kec)
                            <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">Tahun Mulai</label>
                    <select name="tahun_mulai" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-400 outline-none bg-white">
                    <option value="" disabled selected>-- Pilih Tahun --</option>
                        @php
                            $tahunSekarang = date('Y'); // Mengambil tahun otomatis (2026)
                            $tahunAwal = 1970;        // Batas bawah sesuai keinginan Anda
                        @endphp
        
                         @for ($i = $tahunSekarang; $i >= $tahunAwal; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold">Status</label>
                    <select name="status" class="w-full border rounded-lg px-3 py-2">
                        <option>Aktif</option>
                        <option>Tidak Aktif</option>
                    </select>
                </div>

                <div class="col-span-2">
                    <label class="text-sm font-semibold">Foto</label>
                    <input type="file" name="foto" class="w-full border rounded-lg px-3 py-2">
                </div>

            </div>
        </div>

        {{-- FOOTER (TOMBOL SELALU KELIHATAN) --}}
<div class="p-4 border-t flex justify-end gap-2 bg-white">

    {{-- BATAL --}}
    <button type="button" onclick="closeModal()"
        class="bg-yellow-400 hover:bg-yellow-500 text-white px-4 py-2 rounded-lg">
        Batal
    </button>

    {{-- SIMPAN --}}
    <button type="submit"
        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
        Simpan
    </button>

</div>

{{-- SCRIPT --}}
<script>
function openModal() {
    document.getElementById('modal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

document.getElementById('searchInput').addEventListener('keyup', function () {
    let keyword = this.value.toLowerCase();
    document.querySelectorAll('.searchable-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
    });
});
</script>

@endsection