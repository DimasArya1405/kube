@extends('admin.layout')

@section('title', 'Pembagian Koordinator')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Pembagian Koordinator</span>
@stop

@section('content')

{{-- HEADER --}}
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Pembagian Koordinator</h2>
        <p class="text-gray-500 mt-1">Kelola penugasan koordinator.</p>
    </div>
</div>

{{-- TOOLBAR --}}
<div class="flex items-center gap-3 mb-4">

    {{-- SEARCH --}}
    <input type="text" id="searchInput"
        class="border px-3 py-2 rounded-lg text-sm w-full max-w-xs"
        placeholder="Cari...">

    {{-- TAMBAH --}}
    <button data-modal-target="modal-tambah"
        data-modal-toggle="modal-tambah"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
        + Tambah Data
    </button>

</div>

{{-- TABLE --}}
<div class="bg-white rounded-lg shadow border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Koordinator</th>
                    <th class="px-4 py-3">Pendamping</th>
                    <th class="px-4 py-3">KUBE</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $i => $row)
                <tr class="border-t hover:bg-gray-50 searchable-row">

                    <td class="px-4 py-3">{{ $i+1 }}</td>

                    <td class="px-4 py-3 font-medium">
                        {{ $row->koordinator->nama_koor ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $row->pembagianPendamping->pendamping->nama_pendamping ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        {{ $row->pembagianPendamping->kube->nama_kube ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        @if($row->status == 'Aktif')
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Aktif</span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full">Selesai</span>
                        @endif
                    </td>

                    {{-- AKSI --}}
                    <td class="px-4 py-3">
                        <div class="flex gap-2">

                            {{-- EDIT --}}
                            <button 
                                class="text-yellow-500 btn-edit"
                                data-id="{{ $row->id_pembagian_koor }}"
                                data-koor="{{ $row->id_koor }}"
                                data-pembagian="{{ $row->id_pembagian }}"
                                data-status="{{ $row->status }}"
                                data-modal-target="modal-edit"
                                data-modal-toggle="modal-edit">
                                ✏️
                            </button>

                            {{-- DELETE --}}
                            <form action="{{ route('pembagian_koordinator.destroy', $row->id_pembagian_koor) }}" 
                                  method="POST"
                                  onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')

                                <button class="text-red-500">🗑</button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-6 text-gray-400">
                        Belum ada data.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div id="modal-tambah" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white p-6 rounded-lg w-96">

        <h3 class="text-lg font-semibold mb-4">Tambah Data</h3>

        <form action="{{ route('pembagian_koordinator.store') }}" method="POST">
            @csrf

            {{-- KOORDINATOR --}}
            <select name="id_koor" class="w-full mb-2 border p-2 rounded" required>
                <option value="">Pilih Koordinator</option>
                @foreach($koor as $k)
                    <option value="{{ $k->id_koor }}">{{ $k->nama_koor }}</option>
                @endforeach
            </select>

            {{-- PENDAMPING --}}
            <select name="id_pembagian" class="w-full mb-2 border p-2 rounded" required>
                <option value="">Pilih Pendamping</option>
                @foreach($pendamping as $p)
                    <option value="{{ $p->id_pembagian }}">
                        {{ $p->nama_pendamping }} - {{ $p->nama_kube }}
                    </option>
                @endforeach
            </select>

            {{-- STATUS --}}
            <select name="status" class="w-full mb-3 border p-2 rounded">
                <option value="Aktif">Aktif</option>
                <option value="Selesai">Selesai</option>
            </select>

            <button class="bg-blue-600 text-white w-full py-2 rounded">
                Simpan
            </button>

        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white p-6 rounded-lg w-96">

        <h3 class="text-lg font-semibold mb-4">Edit Data</h3>

        <form method="POST" id="formEdit">
            @csrf
            @method('PUT')

            <select name="id_koor" id="edit_koor" class="w-full mb-2 border p-2 rounded">
                @foreach($koor as $k)
                    <option value="{{ $k->id_koor }}">{{ $k->nama_koor }}</option>
                @endforeach
            </select>

            <select name="id_pembagian" id="edit_pembagian" class="w-full mb-2 border p-2 rounded">
                @foreach($pendamping as $p)
                    <option value="{{ $p->id_pembagian }}">
                        {{ $p->nama_pendamping }} - {{ $p->nama_kube }}
                    </option>
                @endforeach
            </select>

            <select name="status" id="edit_status" class="w-full mb-3 border p-2 rounded">
                <option value="Aktif">Aktif</option>
                <option value="Selesai">Selesai</option>
            </select>

            <button class="bg-yellow-500 text-white w-full py-2 rounded">
                Update
            </button>
        </form>
    </div>
</div>

{{-- SCRIPT --}}
<script>

// SEARCH
document.getElementById('searchInput').addEventListener('keyup', function() {
    let keyword = this.value.toLowerCase();
    document.querySelectorAll('.searchable-row').forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(keyword) ? '' : 'none';
    });
});

// EDIT
document.querySelectorAll('.btn-edit').forEach(btn => {
    btn.addEventListener('click', function () {

        document.getElementById('edit_koor').value = this.dataset.koor;
        document.getElementById('edit_pembagian').value = this.dataset.pembagian;
        document.getElementById('edit_status').value = this.dataset.status;

        document.getElementById('formEdit').action =
            "/pembagian_koordinator/" + this.dataset.id;
    });
});

</script>

@stop