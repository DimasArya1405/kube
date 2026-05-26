@extends('admin.layout')

@section('title', 'Monitoring Bantuan - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Monitoring Bantuan</span>
@stop

@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Monitoring Bantuan</h2>
        <p class="text-gray-500 mt-1">Kelola data monitoring bantuan KUBE.</p>
    </div>
</div>

{{-- SUMMARY --}}
<div class="flex gap-4 mb-6">
    <div class="bg-orange-400 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p>Sesuai</p>
        <p class="text-3xl font-bold">
            {{ $monitoring->where('kesesuaian','sesuai')->count() }}
        </p>
    </div>

    <div class="bg-green-400 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p>Tidak Sesuai</p>
        <p class="text-3xl font-bold">
            {{ $monitoring->where('kesesuaian','tidak sesuai')->count() }}
        </p>
    </div>
</div>

{{-- TOOLBAR --}}
<div class="flex flex-wrap items-center gap-3 mb-4">

    {{-- SEARCH --}}
    <div class="relative flex-1 min-w-[200px]">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            <i data-lucide="search" class="w-4 h-4"></i>
        </span>
        <input type="text" id="searchInput" placeholder="Cari..."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-400">
    </div>

    {{-- EXPORT PDF --}}
    <a href="{{ route('monitoring.pdf') }}"
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm px-4 py-2 rounded-lg">
        <i data-lucide="file-text" class="w-4 h-4"></i>
        PDF
    </a>

    <!-- {{-- EXPORT EXCEL --}}
    <a href="#"
        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm px-4 py-2 rounded-lg">
        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
        Excel
    </a> -->

    {{-- TAMBAH --}}
    <button data-modal-target="modal-tambah-monitoring" data-modal-toggle="modal-tambah-monitoring"
        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm px-4 py-2 rounded-lg">
        <i data-lucide="plus" class="w-4 h-4"></i>
        Tambah
    </button>

</div>

{{-- TABLE --}}
<div class="bg-white rounded shadow overflow-x-auto">
<table class="w-full text-sm">

<thead class="bg-gray-200">
<tr>
    <th class="p-3">Foto</th>
    <th class="p-3">Jenis</th>
    <th class="p-3">KUBE</th>
    <th class="p-3">Pendamping</th>
    <th class="p-3">Tanggal</th>
    <th class="p-3">Kesesuaian</th>
    <th class="p-3">Catatan</th>
    <th class="p-3">Aksi</th>
</tr>
</thead>

<tbody>
@forelse($monitoring as $item)
<tr class="border-t searchable-row">

<td class="p-3">
    @if($item->foto_monitoring)
        <img src="{{ asset('storage/'.$item->foto_monitoring) }}"
            class="rounded cursor-pointer"
            style="width:50px;height:50px;object-fit:cover"
            onclick="showImage('{{ asset('storage/'.$item->foto_monitoring) }}')">
    @else
        -
    @endif
</td>

<td class="p-3">{{ $item->jenisBantuan->jenis_bantuan ?? '-' }}</td>
<td class="p-3">{{ $item->kube->nama_kube ?? '-' }}</td>
<td class="p-3">{{ $item->pendamping->nama_pendamping ?? '-' }}</td>
<td class="p-3">{{ date('d-m-Y', strtotime($item->tanggal_monitoring)) }}</td>

<td class="p-3">
    @if($item->kesesuaian == 'sesuai')
        <span class="bg-green-200 text-green-800 px-2 py-1 rounded text-xs">Sesuai</span>
    @else
        <span class="bg-red-200 text-red-800 px-2 py-1 rounded text-xs">Tidak</span>
    @endif
</td>

<td class="p-3">{{ $item->catatan }}</td>

<td class="p-3 flex gap-3 items-center">

    <a href="{{ route('monitoring.detail',$item->id_monitoring) }}" class="text-blue-500">
        <i data-lucide="eye" class="w-5 h-5"></i>
    </a>

    <a href="{{ route('monitoring.edit',$item->id_monitoring) }}" class="text-yellow-500">
        <i data-lucide="pencil" class="w-5 h-5"></i>
    </a>

    <form action="{{ route('monitoring.delete',$item->id_monitoring) }}" method="POST">
        @csrf
        @method('DELETE')
        <button class="text-red-500">
            <i data-lucide="trash-2" class="w-5 h-5"></i>
        </button>
    </form>

</td>

</tr>
@empty
<tr>
<td colspan="8" class="text-center p-5 text-gray-400">
Belum ada data
</td>
</tr>
@endforelse
</tbody>

</table>
</div>

{{-- MODAL TAMBAH MONITORING --}}
<div id="modal-tambah-monitoring" tabindex="-1"
    class="hidden fixed inset-0 z-50 flex justify-center items-center w-full h-full bg-black bg-opacity-40">

    <div class="relative p-4 w-full max-w-lg">
        <div class="bg-white rounded-lg shadow">

            {{-- HEADER --}}
            <div class="flex items-center justify-between p-4 border-b">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Monitoring</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Isi data monitoring dengan lengkap.</p>
                </div>
                <button type="button" onclick="closeModal()"
                    class="text-gray-400 hover:bg-gray-200 rounded-lg w-8 h-8 flex items-center justify-center">
                    ✕
                </button>
            </div>

            {{-- FORM --}}
            <form action="{{ route('monitoring.store') }}" method="POST" enctype="multipart/form-data" class="p-5">
                @csrf

                {{-- JENIS --}}
                <div class="mb-3">
                    <label class="text-sm font-medium text-gray-700">Jenis Bantuan</label>
                    <select name="id_jenis_bantuan"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                        @foreach($jenis as $j)
                        <option value="{{ $j->id_jenis_bantuan }}">{{ $j->jenis_bantuan }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- KUBE --}}
                <div class="mb-3">
    <label class="text-sm font-medium text-gray-700">KUBE</label>
    <input type="text" value="KUBE Sejahtera Mandiri"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100" readonly>

    <input type="hidden" name="id_kube" value="1">
</div>

                {{-- PENDAMPING --}}
                <div class="mb-3">
    <label class="text-sm font-medium text-gray-700">Pendamping</label>
    <input type="text" value="Budi Santoso"
        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100" readonly>

    <input type="hidden" name="id_pendamping" value="1">
</div>

                {{-- TANGGAL --}}
                <div class="mb-3">
                    <label class="text-sm font-medium text-gray-700">Tanggal Monitoring</label>
                    <input type="date" name="tanggal_monitoring"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                </div>

                {{-- KESESUAIAN --}}
                <div class="mb-3">
                    <label class="text-sm font-medium text-gray-700">Kesesuaian</label>
                    <select name="kesesuaian"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400">
                        <option value="sesuai">Sesuai</option>
                        <option value="tidak sesuai">Tidak Sesuai</option>
                    </select>
                </div>

                {{-- CATATAN --}}
                <div class="mb-3">
                    <label class="text-sm font-medium text-gray-700">Catatan</label>
                    <textarea name="catatan" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400"></textarea>
                </div>

                {{-- FOTO --}}
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-700">Foto</label>
                    <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                        <input type="file" name="foto_monitoring" id="fotoMonitoring" class="hidden" accept="image/*">

                        <input type="text" id="fotoMonitoringLabel" readonly
                            class="flex-1 px-3 py-2 text-sm text-gray-500 cursor-pointer"
                            onclick="document.getElementById('fotoMonitoring').click()">

                        <button type="button"
                            onclick="document.getElementById('fotoMonitoring').click()"
                            class="bg-teal-500 hover:bg-teal-600 text-white text-sm px-4 py-2">
                            Pilih File
                        </button>
                    </div>
                </div>

                {{-- BUTTON --}}
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeModal()"
                        class="bg-gray-400 hover:bg-gray-500 text-white text-sm px-5 py-2 rounded-lg">
                        Batal
                    </button>

                    <button type="submit"
                        class="bg-green-500 hover:bg-green-600 text-white text-sm px-5 py-2 rounded-lg">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

{{-- MODAL PREVIEW --}}
<div id="imageModal" class="hidden fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center">
<img id="previewImage" class="max-h-[80%] rounded">
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup', function(){
let val = this.value.toLowerCase();
document.querySelectorAll('.searchable-row').forEach(row=>{
row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
});
});

function showImage(src){
document.getElementById('previewImage').src = src;
document.getElementById('imageModal').classList.remove('hidden');
}

document.getElementById('imageModal').onclick = function(){
this.classList.add('hidden');
}
</script>

@stop