@extends('admin.layout')

@section('title', 'Pembagian Koordinator')

@section('breadcrumb')
    Dashboard / <span class="text-gray-800">Pembagian Koordinator</span>
@stop

@section('content')

@php use Carbon\Carbon; @endphp

{{-- ================= HEADER ================= --}}
<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Pembagian Koordinator</h2>
        <p class="text-gray-500 mt-1">Kelola penugasan koordinator.</p>
    </div>
</div>

{{-- ================= SUMMARY ================= --}}
<div class="flex gap-4 mb-6">

    <div class="bg-green-400 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Data Aktif</p>
        <p class="text-3xl font-bold mt-1">
            {{ $data->flatten()->filter(fn($d) => !$d->tgl_selesai || \Carbon\Carbon::parse($d->tgl_selesai)->isFuture())->count() }}
        </p>
    </div>

    <div class="bg-yellow-400 text-white rounded-lg px-6 py-4 text-center min-w-[150px]">
        <p class="text-sm font-medium">Data Selesai</p>
        <p class="text-3xl font-bold mt-1">
            {{ $data->flatten()->filter(fn($d) => $d->tgl_selesai && \Carbon\Carbon::parse($d->tgl_selesai)->isPast())->count() }}
        </p>
    </div>

</div>

{{-- ================= TOOLBAR ================= --}}
<div class="flex flex-wrap items-center gap-3 mb-4">

    {{-- SEARCH --}}
    <input type="text" id="searchInput"
        class="border px-3 py-2 rounded-lg text-sm w-full max-w-xs"
        placeholder="Cari...">

    {{-- FILTER TAHUN --}}
    <select id="filterTahun"
        class="border px-3 py-2 rounded-lg text-sm">

        <option value="">Semua Tahun</option>

        @for($i = date('Y'); $i >= 2020; $i--)
            <option value="{{ $i }}">{{ $i }}</option>
        @endfor

    </select>

    {{-- EXPORT PDF --}}
    <a href="{{ route('pembagian_koordinator.exportPDF') }}"
        class="flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
         PDF
    </a>

    {{-- EXPORT EXCEL --}}
    <a href="{{ route('pembagian_koordinator.exportExcel') }}"
        class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm">
         Excel
    </a>

    {{-- TAMBAH --}}
    <button onclick="openModal('modal-tambah')"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
        + Tambah Data
    </button>

</div>

{{-- ================= TABLE ================= --}}
<div class="bg-white rounded-lg shadow border overflow-hidden">
    <div class="overflow-x-auto">

        <table class="w-full text-sm text-left">
            <thead class="bg-gray-200 text-gray-700">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Pendamping</th>
                    <th class="px-4 py-3">KUBE</th>
                    <th class="px-4 py-3">Tanggal Mulai</th>
                    <th class="px-4 py-3">Tanggal Selesai</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody>

                @foreach($data as $group)

                    @php
                        $koorHeader = $group->first()?->koordinator;
                    @endphp

                    {{-- HEADER KOORDINATOR --}}
                    <tr class="bg-blue-50"
                        data-koor="{{ strtolower($koorHeader->nama_koor ?? '') }}">
                        <td colspan="7" class="px-4 py-3 font-bold text-blue-700">
                            👤 {{ $koorHeader->nama_koor ?? '-' }}
                        </td>
                    </tr>

                    @foreach($group as $row)

                        @php
                            $pp = $row->pembagianPendamping;
                            $status = ($row->tgl_selesai && Carbon::parse($row->tgl_selesai)->isPast())
                                ? 'Selesai' : 'Aktif';
                        @endphp

                        <tr class="border-t hover:bg-gray-50 searchable-row"
                            data-tahun="{{ \Carbon\Carbon::parse($row->tgl_mulai)->format('Y') }}">

                            <td class="px-4 py-3">{{ $loop->iteration }}</td>

                            <td class="px-4 py-3">
                                {{ $pp->pendamping->nama_pendamping ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $pp->kube->nama_kube ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $row->tgl_mulai ? Carbon::parse($row->tgl_mulai)->format('d-m-Y') : '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $row->tgl_selesai ? Carbon::parse($row->tgl_selesai)->format('d-m-Y') : '-' }}
                            </td>

                            <td class="px-4 py-3">
                                <span class="{{ $status == 'Aktif' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} text-xs px-2 py-1 rounded-full">
                                    {{ $status }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex gap-2">

                                    {{-- EDIT --}}
                                    <button 
                                        class="text-yellow-500 hover:scale-110 transition btn-edit"
                                        data-id="{{ $row->id_pembagian_koor }}"
                                        data-koor="{{ $row->id_koor }}"
                                        data-pembagian="{{ $row->id_pembagian }}"
                                        data-kecamatan="{{ $pp->pendamping->id_kecamatan ?? '' }}"
                                        data-mulai="{{ $row->tgl_mulai }}"
                                        data-selesai="{{ $row->tgl_selesai }}">
                                        ✏️
                                    </button>

                                    {{-- DELETE --}}
                                    <form action="{{ route('pembagian_koordinator.destroy', $row->id_pembagian_koor) }}" method="POST"
                                          onsubmit="return confirm('Yakin hapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-500 hover:scale-110 transition">🗑</button>
                                    </form>

                                </div>
                            </td>

                        </tr>

                    @endforeach

                @endforeach

            </tbody>
        </table>

    </div>
</div>

{{-- ================= MODAL TAMBAH ================= --}}
<div id="modal-tambah" class="hidden fixed inset-0 bg-black/50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-96 shadow-lg">

        <h3 class="text-lg font-semibold mb-4">Tambah Data</h3>

        <form action="{{ route('pembagian_koordinator.store') }}" method="POST">
            @csrf

            {{-- KOORDINATOR --}}
            <select name="id_koor" class="w-full mb-2 border p-2 rounded" required>
                <option value="" selected disabled>Pilih Koordinator</option>
                @foreach($koor as $k)
                    <option value="{{ $k->id_koor }}">{{ $k->nama_koor }}</option>
                @endforeach
            </select>

            {{-- KECAMATAN --}}
            <select id="kecamatan" class="w-full mb-2 border p-2 rounded" required>
                <option value="">Pilih Kecamatan</option>
                @foreach($kecamatan as $kec)
                    <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                @endforeach
            </select>

            {{-- PENDAMPING --}}
            <select name="id_pembagian" id="pendamping" class="w-full mb-2 border p-2 rounded" required>
                <option value="">Pilih Pendamping</option>
            </select>

            {{-- TANGGAL --}}
            <label class="text-xs text-gray-500">Tanggal Mulai</label>
            <input type="date" name="tgl_mulai" class="w-full mb-2 border p-2 rounded">

            <label class="text-xs text-gray-500">Tanggal Selesai</label>
            <input type="date" name="tgl_selesai" class="w-full mb-3 border p-2 rounded">

            <button class="bg-blue-600 hover:bg-blue-700 text-white w-full py-2 rounded">
                Simpan
            </button>

        </form>
    </div>
</div>

{{-- ================= MODAL EDIT ================= --}}
<div id="modal-edit" class="hidden fixed inset-0 bg-black/50 flex justify-center items-center">
    <div class="bg-white p-6 rounded-lg w-96 shadow-lg">

        <h3 class="text-lg font-semibold mb-4">Edit Data</h3>

        <form method="POST" id="formEdit">
            @csrf
            @method('PUT')

            <select name="id_koor" id="edit_koor" class="w-full mb-2 border p-2 rounded">
                @foreach($koor as $k)
                    <option value="{{ $k->id_koor }}">{{ $k->nama_koor }}</option>
                @endforeach
            </select>

            <select id="edit_kecamatan" class="w-full mb-2 border p-2 rounded">
                @foreach($kecamatan as $kec)
                    <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                @endforeach
            </select>

            <select name="id_pembagian" id="edit_pendamping" class="w-full mb-2 border p-2 rounded"></select>

            <label class="text-xs text-gray-500">Tanggal Mulai</label>
            <input type="date" name="tgl_mulai" id="edit_tgl_mulai" class="w-full mb-2 border p-2 rounded">

            <label class="text-xs text-gray-500">Tanggal Selesai</label>
            <input type="date" name="tgl_selesai" id="edit_tgl_selesai" class="w-full mb-3 border p-2 rounded">

            <button class="bg-yellow-500 hover:bg-yellow-600 text-white w-full py-2 rounded">
                Update
            </button>

        </form>
    </div>
</div>

{{-- ================= SCRIPT ================= --}}
<script>

// SEARCH
function filterData() {

    let keyword = document.getElementById('searchInput').value.toLowerCase();
    let tahun = document.getElementById('filterTahun').value;

    let rows = document.querySelectorAll("tbody tr");

    let currentHeader = null;
    let headerMatch = false;

    rows.forEach(row => {

        // HEADER KOORDINATOR
        if (row.classList.contains('bg-blue-50')) {

            currentHeader = row;

            let namaKoor = row.dataset.koor || '';

            headerMatch =
                keyword !== '' &&
                namaKoor.includes(keyword);

            row.style.display = 'none';

            return;
        }

        let text = row.textContent.toLowerCase();
        let tahunRow = row.dataset.tahun || '';

        let cocokSearch =
            text.includes(keyword);

        let cocokTahun =
            tahun === '' || tahunRow === tahun;

        if ((headerMatch || cocokSearch) && cocokTahun) {

            row.style.display = '';

            if (currentHeader) {
                currentHeader.style.display = '';
            }

        } else {

            row.style.display = 'none';

        }

    });

    // jika search kosong dan tidak filter tahun
    if (keyword === '' && tahun === '') {

        document.querySelectorAll('tbody tr')
            .forEach(row => {
                row.style.display = '';
            });

    }
}

// MODAL
function openModal(id){
    document.getElementById(id).classList.remove('hidden');
}

// FILTER TAMBAH
document.getElementById('kecamatan').addEventListener('change', function () {
    fetch(`/get-pendamping/${this.value}`)
    .then(res => res.json())
    .then(data => {
        let select = document.getElementById('pendamping');
        select.innerHTML = '<option value="">Pilih Pendamping</option>';
        data.forEach(item => {
            select.innerHTML += `<option value="${item.id_pembagian}">
                ${item.nama_pendamping} - ${item.nama_kube}
            </option>`;
        });
    });
});

// EDIT
document.querySelectorAll('.btn-edit').forEach(btn => {
btn.addEventListener('click', function(){

    let id = this.dataset.id;
    let kec = this.dataset.kecamatan;
    let pembagian = this.dataset.pembagian;

    document.getElementById('formEdit').action = "/pembagian_koordinator/" + id;

    document.getElementById('edit_koor').value = this.dataset.koor;
    document.getElementById('edit_kecamatan').value = kec;

    document.getElementById('edit_tgl_mulai').value = this.dataset.mulai?.substring(0,10);
    document.getElementById('edit_tgl_selesai').value = this.dataset.selesai?.substring(0,10);

    fetch(`/get-pendamping/${kec}/${pembagian}`)
    .then(res => res.json())
    .then(data => {

        let select = document.getElementById('edit_pendamping');
        select.innerHTML = '';

        data.forEach(item => {
            let selected = item.id_pembagian == pembagian ? 'selected' : '';
            select.innerHTML += `<option value="${item.id_pembagian}" ${selected}>
                ${item.nama_pendamping} - ${item.nama_kube}
            </option>`;
        });

    });

    openModal('modal-edit');
});
});
document.getElementById('searchInput')
    .addEventListener('keyup', filterData);

document.getElementById('filterTahun')
    .addEventListener('change', filterData);

filterData();
</script>

@stop