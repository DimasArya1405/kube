@extends('admin.layout')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

<form method="GET" action="{{ route('laporan.kecamatan') }}">
    <div class="grid grid-cols-4 gap-4">

        <div>
            <label class="block mb-1">Tahun</label>
            <select name="tahun" class="border p-2 rounded w-full">
                <option value="">Semua Tahun</option>
                @foreach($tahun as $t)
                    <option value="{{ $t->tahun }}"
                        {{ request('tahun') == $t->tahun ? 'selected' : '' }}>
                        {{ $t->tahun }}
                    </option>
                @endforeach
            </select>
        </div>

<div>
    <label class="block mb-1">Kecamatan</label>
   <select name="kecamatan" class="border p-2 rounded w-full">
        <option value="">Semua Kecamatan</option>
        @foreach($kecamatan as $kec)
            <option value="{{ $kec->id_kecamatan }}"
                {{ request('kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>
                {{ $kec->nama_kecamatan }}
            </option>
        @endforeach
    </select>
</div> 
<div>
    <label class="block mb-1">Cluster</label>
    <select name="cluster" class="border p-2 rounded w-full">
        <option value="">Semua Cluster</option>
        @foreach($cluster as $c)
            <option value="{{ $c->id_cluster }}"
                {{ request('cluster') == $c->id_cluster ? 'selected' : '' }}>
                {{ $c->nama_cluster }}
            </option>
        @endforeach
    </select>
</div>
<div class="flex items-end">
    <button type="submit"
        class="bg-indigo-600 hover:bg-indigo-700 text-white h-[42px] px-8 rounded-md font-medium">
        <i class="fas fa-search mr-2"></i>
        Search
    </button>
</div>

    </div>
</form>

</div>
{{-- CARD --}}
<div class="grid grid-cols-5 gap-4 mt-6">

    <div class="bg-gradient-to-r from-cyan-500 to-cyan-600 text-white p-6 rounded-xl shadow flex items-center justify-between">
        <div>
            <div class="text-3xl font-bold">{{ $totalKube ?? 0 }}</div>
            <div class="text-sm">TOTAL KUBE</div>
        </div>
        <i class="fas fa-users text-4xl opacity-70"></i>
    </div>

    <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-xl shadow flex items-center justify-between">
        <div>
            <div class="text-3xl font-bold">{{ $kubeAktif ?? 0 }}</div>
            <div class="text-sm">KUBE AKTIF</div>
        </div>
        <i class="fas fa-check-circle text-4xl opacity-70"></i>
    </div>

    <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-6 rounded-xl shadow flex items-center justify-between">
        <div>
            <div class="text-3xl font-bold">{{ $kubeNonaktif ?? 0 }}</div>
            <div class="text-sm">KUBE NONAKTIF</div>
        </div>
        <i class="fas fa-times-circle text-4xl opacity-70"></i>
    </div>

    <div class="bg-white border p-4 rounded-xl shadow flex items-center justify-between">
        <div>
            <div class="text-sm text-gray-500">Total Omset</div>
            <div class="font-bold text-lg text-green-600">
                Rp {{ number_format($totalOmset ?? 0,0,',','.') }}
            </div>
        </div>
        <i class="fas fa-coins text-3xl text-gray-400"></i>
    </div>

    <div class="bg-white border p-4 rounded-xl shadow flex items-center justify-between">
        <div>
            <div class="text-sm text-gray-500">Total Laba</div>
            <div class="font-bold text-lg text-blue-600">
                Rp {{ number_format($totalLaba ?? 0,0,',','.') }}
            </div>
        </div>
        <i class="fas fa-chart-line text-3xl text-gray-400"></i>
    </div>

</div>

{{-- BUTTON EXPORT --}}
<div class="flex justify-between items-center mt-6">

    <div>
        <h2 class="text-xl font-bold text-gray-800">
            Laporan Data KUBE
        </h2>

        <p class="text-sm text-gray-500 mt-1">
            Berdasarkan Pengajuan yang Telah Disetujui
        </p>
    </div>

    <div class="flex gap-3">

        <a href="{{ route('laporan.kecamatan.excel', request()->query()) }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow flex items-center gap-2">
            <i class="fas fa-file-excel"></i>
            Export Excel
        </a>

        <a href="{{ route('laporan.kecamatan.pdf', request()->query()) }}"
           class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded shadow flex items-center gap-2">
            <i class="fas fa-file-pdf"></i>
            Export PDF
        </a>

    </div>

</div>

{{-- TABEL --}}
<div class="bg-white p-6 rounded-xl shadow mt-4 overflow-x-auto">

@if(count($data) > 0)

<table class="min-w-full text-sm border">

    <thead class="bg-gray-100 text-gray-700">
        <tr>
            <th class="p-3 border">No</th>
            <th class="p-3 border">Nama</th>
            <th class="p-3 border">Kecamatan</th>
            <th class="p-3 border">Kategori</th>
            <th class="p-3 border">Cluster</th>
            <th class="p-3 border">Perkembangan</th>
            <th class="p-3 border">Omset</th>
            <th class="p-3 border">Status</th>
            <th class="p-3 border">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $d)
        <tr class="hover:bg-gray-50">

            <td class="p-3 border text-center">{{ $loop->iteration }}</td>
            <td class="p-3 border font-medium">{{ $d->nama_kube }}</td>
            <td class="p-3 border">{{ $d->nama_kecamatan }}</td>

            <td class="p-3 border">
                <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded text-xs">
                    {{ $d->nama_kategori ?? '-' }}
                </span>
            </td>

            <td class="p-3 border">{{ $d->nama_cluster }}</td>

            <td class="p-3 border text-center">
                @if($d->perkembangan_usaha == 'Meningkat')
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Meningkat</span>
                @elseif($d->perkembangan_usaha == 'Menurun')
                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Menurun</span>
                @else
                    <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">Tetap</span>
                @endif
            </td>

            <td class="p-3 border text-green-600 font-semibold">
                Rp {{ number_format($d->total_omset,0,',','.') }}
            </td>

            <td class="p-3 border text-center">
                @if($d->status == 'aktif')
                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Aktif</span>
                @else
                    <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Tidak Aktif</span>
                @endif
            </td>

            <td class="p-3 border text-center">
                <button 
                    onclick="openModal(this)"
                    data-id="{{ $d->id_kube }}"
                    data-nama="{{ $d->nama_kube }}"
                    data-cluster="{{ $d->nama_cluster }}"
                    data-kecamatan="{{ $d->nama_kecamatan }}"
                    data-omset="{{ $d->total_omset }}"
                    data-laba="{{ $d->laba_bersih }}"
                    data-status="{{ $d->status }}"
                    data-pendamping="{{ $d->nama_pendamping }}"
                    data-desa="{{ $d->nama_desa_kelurahan }}"
                    data-tanggal="{{ $d->tanggal_terbentuk }}"
                    data-kategori="{{ $d->nama_kategori }}"
                    data-perkembangan="{{ $d->perkembangan_usaha }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded">
                    Detail
                </button>
            </td>

        </tr>
        @endforeach
    </tbody>

</table>

@else
<div class="text-center text-red-500 py-6">
    Tidak ada data
</div>
@endif

</div>

{{-- MODAL --}}
<div id="modalDetail"
     class="fixed inset-0 bg-black/70 hidden items-center justify-center overflow-y-auto z-[99999]">

    <div class="bg-white w-2/3 max-h-[90vh] overflow-y-auto p-6 rounded-xl shadow-xl relative z-[9999] bg-white">
        <h2 class="text-xl font-bold mb-4 border-b pb-2">
            Detail KUBE
        </h2>

        <div id="modalContent"></div>

    </div>

</div>

{{-- JS --}}
<script>
function openModal(button){

    let id = button.dataset.id;
    let nama = button.dataset.nama;
    let cluster = button.dataset.cluster;
    let kecamatan = button.dataset.kecamatan;
    let omset = button.dataset.omset;
    let laba = button.dataset.laba;
    let status = button.dataset.status;

    let pendamping = button.dataset.pendamping;
    let desa = button.dataset.desa;
    let tanggal = button.dataset.tanggal;
    let kategori = button.dataset.kategori;
    let perkembangan = button.dataset.perkembangan;


    document.getElementById('modalContent').innerHTML = `

    <!-- HEADER -->
    <div class="bg-gray-50 p-5 rounded-xl border">

        <div class="grid grid-cols-2 gap-6">

            <div>
                <div class="text-sm text-gray-500">Nama KUBE</div>
   <div class="font-semibold text-lg">${nama}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Status</div>
                ${
                    status === 'aktif'
                    ? '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">Aktif</span>'
                    : '<span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">Tidak Aktif</span>'
                }
            </div>

            <div>
                <div class="text-sm text-gray-500">Kecamatan</div>
                <div class="font-semibold">${kecamatan}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Cluster</div>
                <div class="font-semibold">${cluster}</div>
            </div>

        </div>

    </div>

    <!-- OMSET & LABA -->
    <div class="grid grid-cols-2 gap-4 mt-5">

        <div class="bg-green-50 border border-green-200 p-4 rounded-xl">
            <div class="text-sm text-green-700">Total Omset</div>
            <div class="text-xl font-bold text-green-800 mt-1">
                Rp ${Number(omset).toLocaleString('id-ID')}
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl">
            <div class="text-sm text-blue-700">Laba Bersih</div>
            <div class="text-xl font-bold text-blue-800 mt-1">
                Rp ${Number(laba).toLocaleString('id-ID')}
            </div>
        </div>

    </div>

    <!-- DETAIL TAMBAHAN -->
    <div class="bg-white border mt-5 p-5 rounded-xl">

        <h3 class="font-semibold text-gray-700 mb-3">Informasi Tambahan</h3>

        <div class="grid grid-cols-2 gap-4">

            <div>
                <div class="text-sm text-gray-500">Pendamping</div>
                <div class="font-semibold">${pendamping ?? '-'}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Desa</div>
                <div class="font-semibold">${desa ?? '-'}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Tanggal Terbentuk</div>
                <div class="font-semibold">${tanggal ?? '-'}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Kategori</div>
                <div class="font-semibold">${kategori ?? '-'}</div>
            </div>

            <div>
                <div class="text-sm text-gray-500">Perkembangan</div>
                ${
                    perkembangan === 'Meningkat'
                    ? '<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">Meningkat</span>'
                    : perkembangan === 'Menurun'
                    ? '<span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">Menurun</span>'
                    : '<span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">Tetap</span>'
                }
            </div>

        </div>

    </div>

    <!-- BUTTON -->
    <div class="mt-6 flex justify-between">

        <a href="/admin/laporan-kecamatan/pdf/${id}" 
            class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded">
            Export PDF
        </a>

        <button onclick="closeModal()" 
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
            Exit
        </button>

    </div>
    `;

    document.getElementById('modalDetail').classList.remove('hidden');
    document.getElementById('modalDetail').classList.add('flex');
}

function closeModal(){
    document.getElementById('modalDetail').classList.add('hidden');
}
</script>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    new TomSelect('select[name="tahun"]', {
        create: false,
        allowEmptyOption: true
    });

    new TomSelect('select[name="kecamatan"]', {
        create: false,
        allowEmptyOption: true
    });

    new TomSelect('select[name="cluster"]', {
        create: false,
        allowEmptyOption: true
    });

});
</script>
@endpush