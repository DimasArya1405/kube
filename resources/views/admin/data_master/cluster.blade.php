@extends('admin.layout')

@section('title', 'Data Cluster Usaha - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Cluster Usaha</span>
@stop

@section('content')

{{-- HEADER --}}
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Cluster Usaha</h2>
        <p class="text-gray-500 mt-1">Kelola data cluster usaha KUBE.</p>
    </div>
</div>

{{-- TOOLBAR --}}
<div class="flex items-center gap-3 mb-4">

    {{-- SEARCH --}}
    <input type="text" id="searchInput"
        class="border px-3 py-2 rounded-lg text-sm w-full max-w-xs"
        placeholder="Cari...">

        <a href="{{ route('cluster_usaha.exportPDF') }}"
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
        PDF
        </a>

        <a href="{{ route('cluster_usaha.exportExcel') }}"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
        Excel
        </a>

    {{-- TAMBAH --}}
    <button data-modal-target="modal-tambah" data-modal-toggle="modal-tambah"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
        Tambah Cluster
    </button>

</div>

{{-- TABLE --}}
<div class="bg-white rounded-lg shadow border overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Nama Cluster</th>
                    <th class="px-4 py-3">Deskripsi</th>
                    <th class="px-4 py-3">Kategori</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $i => $row)
                <tr class="border-t hover:bg-gray-50 searchable-row">

                    <td class="px-4 py-3">{{ $i+1 }}</td>

                    <td class="px-4 py-3 font-medium text-gray-900">
                        {{ $row->nama_cluster }}
                    </td>

                    <td class="px-4 py-3">{{ $row->deskripsi }}</td>

                    <td class="px-4 py-3">
                        {{ $row->nama_kategori ?? '-' }}
                    </td>

                    <td class="px-4 py-3">
                        @if($row->status == 'Aktif')
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Aktif</span>
                        @else
                            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">Tidak Aktif</span>
                        @endif
                    </td>

                    {{-- AKSI --}}
                    <td class="px-4 py-3">
                        <div class="flex gap-2">

                            {{-- EDIT --}}
                            <button 
                                class="text-yellow-500 btn-edit"
                                data-id="{{ $row->id_cluster }}"
                                data-nama="{{ $row->nama_cluster }}"
                                data-deskripsi="{{ $row->deskripsi }}"
                                data-kategori="{{ $row->id_kategori }}"
                                data-status="{{ $row->status }}"
                                data-modal-target="modal-edit"
                                data-modal-toggle="modal-edit">
                                ✏️
                            </button>

                            {{-- DELETE --}}
                            <form action="/cluster_usaha/{{ $row->id_cluster }}" method="POST"
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
    <div class="bg-white rounded-lg p-6 w-96">

        <h3 class="text-lg font-semibold mb-4">Tambah Cluster</h3>

        <form action="/cluster_usaha" method="POST">
            @csrf

            <input type="text" name="nama_cluster" placeholder="Nama Cluster"
                class="w-full mb-2 border p-2 rounded" required>

            <textarea name="deskripsi" placeholder="Deskripsi"
                class="w-full mb-2 border p-2 rounded"></textarea>

            <select name="id_kategori" class="w-full mb-2 border p-2 rounded" required>
                <option value="">Pilih Kategori</option>
                @foreach($kategori as $k)
                    <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>

            <select name="status" class="w-full mb-3 border p-2 rounded">
                <option value="Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
            </select>

            <button class="bg-blue-600 text-white w-full py-2 rounded">
                Simpan
            </button>

        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="modal-edit" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="bg-white rounded-lg p-6 w-96">

        <h3 class="text-lg font-semibold mb-4">Edit Cluster</h3>

        <form method="POST" id="formEdit">
            @csrf
            @method('PUT')

            <input type="text" name="nama_cluster" id="edit_nama"
                class="w-full mb-2 border p-2 rounded">

            <textarea name="deskripsi" id="edit_deskripsi"
                class="w-full mb-2 border p-2 rounded"></textarea>

            <select name="id_kategori" id="edit_kategori"
                class="w-full mb-2 border p-2 rounded">
                @foreach($kategori as $k)
                    <option value="{{ $k->id_kategori }}">{{ $k->nama_kategori }}</option>
                @endforeach
            </select>

            <select name="status" id="edit_status"
                class="w-full mb-3 border p-2 rounded">
                <option value="Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
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

        document.getElementById('edit_nama').value = this.dataset.nama;
        document.getElementById('edit_deskripsi').value = this.dataset.deskripsi;
        document.getElementById('edit_kategori').value = this.dataset.kategori;
        document.getElementById('edit_status').value = this.dataset.status;

        document.getElementById('formEdit').action =
            "/cluster_usaha/" + this.dataset.id;
    });
});

</script>

@stop