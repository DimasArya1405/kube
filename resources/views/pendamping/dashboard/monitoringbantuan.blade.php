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

{{-- SUMMARY CARDS --}}
<div class="flex gap-4 mb-6">
    <div class="bg-orange-400 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Sesuai</p>
        <p class="text-4xl font-bold mt-1">
            {{ $monitoring->where('kesesuaian','sesuai')->count() }}
        </p>
    </div>

    <div class="bg-green-300 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Tidak Sesuai</p>
        <p class="text-4xl font-bold mt-1">
            {{ $monitoring->where('kesesuaian','tidak sesuai')->count() }}
        </p>
    </div>
</div>

{{-- TOOLBAR --}}
<div class="flex flex-wrap items-center gap-3 mb-4">

    {{-- SEARCH --}}
    <div class="relative flex-1 min-w-[200px]">
        <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
            🔍
        </span>
        <input type="text" id="searchInput" placeholder="Cari..."
            class="w-full pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
    </div>

    {{-- EXPORT PDF --}}
    <a href="#"
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        Ekspor PDF
    </a>

    {{-- EXPORT EXCEL --}}
    <a href="#"
        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-4 py-2 rounded-lg">
        Ekspor Excel
    </a>

    {{-- TAMBAH --}}
    <button onclick="openModal()"
        class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg">
        + Tambah Monitoring
    </button>

</div>

{{-- TABLE --}}
<div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-sm text-gray-700 bg-gray-200">
                <tr>
                    <th class="px-4 py-3">Foto</th>
                    <th class="px-4 py-3">Jenis Bantuan</th>
                    <th class="px-4 py-3">KUBE</th>
                    <th class="px-4 py-3">Pendamping</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Kesesuaian</th>
                    <th class="px-4 py-3">Catatan</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($monitoring as $item)
                <tr class="border-t hover:bg-gray-50 searchable-row">

                    {{-- FOTO --}}
                    <td class="px-4 py-3">
                        @if($item->foto_monitoring)
                        <img src="{{ asset('storage/' . ltrim($item->foto_monitoring, '/')) }}"
                            class="rounded object-cover"
                            style="width:40px;height:40px;">
                    @else
                        <span>-</span>
                    @endif
                    </td>

                    <td class="px-4 py-3">{{ $item->jenisBantuan->jenis_bantuan ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $item->kube->nama_kube ?? '-' }}</td>
                    <td class="px-4 py-3">{{ $item->pendamping->nama_pendamping ?? '-' }}</td>
                    <td class="px-4 py-3">{{ date('d-m-Y', strtotime($item->tanggal_monitoring)) }}</td>

                    <td class="px-4 py-3">
                        @if($item->kesesuaian == 'sesuai')
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded">Sesuai</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded">Tidak</span>
                        @endif
                    </td>

                    <td class="px-4 py-3">{{ $item->catatan }}</td>

                    <td class="px-4 py-3">
                        <form action="{{ route('monitoring.delete',$item->id_monitoring) }}" method="POST"
                            onsubmit="return confirm('Yakin hapus data?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500 hover:text-red-700">Hapus</button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-6 text-gray-400">
                        Belum ada data monitoring
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL TAMBAH --}}
<div id="modal-tambah"
    class="hidden fixed inset-0 z-50 bg-black bg-opacity-40 flex justify-center items-center">

    <div class="bg-white rounded-lg w-full max-w-lg p-5">

        <h3 class="text-lg font-bold mb-4">Tambah Monitoring</h3>

        <form action="{{ route('monitoring.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label>Jenis Bantuan</label>
                <select name="id_jenis_bantuan" class="w-full border rounded p-2">
                    @foreach($jenis as $j)
                        <option value="{{ $j->id_jenis_bantuan }}">{{ $j->jenis_bantuan }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>KUBE</label>
                <select name="id_kube" class="w-full border rounded p-2">
                    @foreach($kube as $k)
                        <option value="{{ $k->id_kube }}">{{ $k->nama_kube }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Pendamping</label>
                <select name="id_pendamping" class="w-full border rounded p-2">
                    @foreach($pendamping as $p)
                        <option value="{{ $p->id_pendamping }}">{{ $p->nama_pendamping }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Tanggal Monitoring</label>
                <input type="date" name="tanggal_monitoring" class="w-full border rounded p-2">
            </div>

            <div class="mb-3">
                <label>Kesesuaian</label>
                <select name="kesesuaian" class="w-full border rounded p-2">
                    <option value="sesuai">Sesuai</option>
                    <option value="tidak sesuai">Tidak Sesuai</option>
                </select>
            </div>

            <div class="mb-3">
                <label>Catatan</label>
                <textarea name="catatan" class="w-full border rounded p-2"></textarea>
            </div>

            <div class="mb-4">
                <label>Foto</label>
                <input type="file" name="foto_monitoring" class="w-full">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeModal()"
                    class="bg-gray-400 text-white px-4 py-2 rounded">
                    Batal
                </button>
                <button type="submit"
                    class="bg-green-500 text-white px-4 py-2 rounded">
                    Simpan
                </button>
            </div>

        </form>
    </div>
</div>

{{-- SCRIPT --}}
<script>
function openModal() {
    document.getElementById('modal-tambah').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modal-tambah').classList.add('hidden');
}

document.getElementById('searchInput').addEventListener('keyup', function () {
    const keyword = this.value.toLowerCase();
    document.querySelectorAll('.searchable-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
    });
});
</script>

@stop