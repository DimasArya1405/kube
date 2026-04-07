@extends('admin.layout')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

<form method="GET" action="{{ route('laporan.kecamatan') }}">
    <div class="grid grid-cols-4 gap-4">

        <!-- Tahun -->
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

        <!-- Kecamatan -->
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

        <!-- Cluster -->
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

        <!-- Button -->
        <div class="flex items-end">
            <button type="submit"
                class="bg-indigo-600 text-white px-4 py-2 rounded w-full">
                Search
            </button>
        </div>

    </div>
</form>

</div>

{{-- CARD --}}
<div class="grid grid-cols-5 gap-4 mt-6">

<div class="bg-cyan-400 text-white p-6 rounded-xl text-center">
    <div class="text-4xl font-bold">{{ $totalKube ?? 0 }}</div>
    <div>TOTAL KUBE</div>
</div>

<div class="bg-green-500 text-white p-6 rounded-xl text-center">
    <div class="text-4xl font-bold">{{ $kubeAktif ?? 0 }}</div>
    <div>KUBE AKTIF</div>
</div>

<div class="bg-red-500 text-white p-6 rounded-xl text-center">
    <div class="text-4xl font-bold">{{ $kubeNonaktif ?? 0 }}</div>
    <div>KUBE NONAKTIF</div>
</div>

<div class="bg-gray-100 p-4 rounded-xl">
    <div>💰 Total Omset</div>
    <div class="font-bold">
        Rp {{ number_format($totalOmset ?? 0,0,',','.') }}
    </div>
</div>

<div class="bg-gray-100 p-4 rounded-xl">
    <div>📊 Total Laba</div>
    <div class="font-bold">
        Rp {{ number_format($totalLaba ?? 0,0,',','.') }}
    </div>
</div>

</div>

{{-- TABEL --}}
<div class="bg-white p-6 rounded-xl shadow mt-6">

@if(count($data) > 0)

<table class="w-full border">
    <thead class="bg-gray-100">
        <tr>
            <th class="border p-2">No</th>
            <th class="border p-2">Nama</th>
            <th class="border p-2">Kecamatan</th>
            <th class="border p-2">Cluster</th>
            <th class="border p-2">Omset</th>
            <th class="border p-2">Laba</th>
            <th class="border p-2">Status</th>
            <th class="border p-2">Aksi</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $d)
        <tr>
            <td class="border p-2 text-center">{{ $loop->iteration }}</td>
            <td class="border p-2">{{ $d->nama_kube }}</td>
            <td class="border p-2">{{ $d->nama_kecamatan }}</td>
            <td class="border p-2">{{ $d->nama_cluster }}</td>
            <td class="border p-2">
                Rp {{ number_format($d->total_omset,0,',','.') }}
            </td>
            <td class="border p-2">
                Rp {{ number_format($d->laba_bersih,0,',','.') }}
            </td>
            <td class="border p-2 text-center">
                @if($d->status == 'aktif')
                    <span class="text-green-600 font-semibold">Aktif</span>
                @else
                    <span class="text-red-600 font-semibold">Tidak Aktif</span>
                @endif
            </td>

            <!-- AKSI -->
            <td class="border p-2 text-center">
                <button 
                    onclick="openModal(this)"
                    data-nama="{{ $d->nama_kube }}"
                    data-cluster="{{ $d->nama_cluster }}"
                    data-kecamatan="{{ $d->nama_kecamatan }}"
                    data-omset="{{ $d->total_omset }}"
                    data-laba="{{ $d->laba_bersih }}"
                    data-status="{{ $d->status }}"
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
<div id="modalDetail" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center">

    <div class="bg-white w-2/3 p-6 rounded-xl relative">

        <h2 class="text-xl font-bold mb-4">
            Detail KUBE
        </h2>

        <div id="modalContent"></div>

    </div>

</div>

{{-- JS --}}
<script>
function openModal(button){

    let nama = button.dataset.nama;
    let cluster = button.dataset.cluster;
    let kecamatan = button.dataset.kecamatan;
    let omset = button.dataset.omset;
    let laba = button.dataset.laba;
    let status = button.dataset.status;

    document.getElementById('modalContent').innerHTML = `
        <div class="grid grid-cols-2 gap-4">

            <div>
                <label class="text-sm text-gray-500">Nama KUBE</label>
                <div class="border p-2 rounded bg-gray-50">${nama}</div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Cluster</label>
                <div class="border p-2 rounded bg-gray-50">${cluster}</div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Kecamatan</label>
                <div class="border p-2 rounded bg-gray-50">${kecamatan}</div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Status</label>
                <div class="border p-2 rounded bg-gray-50">${status}</div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Total Omset</label>
                <div class="border p-2 rounded bg-gray-50">
                    Rp ${Number(omset).toLocaleString('id-ID')}
                </div>
            </div>

            <div>
                <label class="text-sm text-gray-500">Laba Bersih</label>
                <div class="border p-2 rounded bg-gray-50">
                    Rp ${Number(laba).toLocaleString('id-ID')}
                </div>
            </div>

        </div>

        <div class="mt-6 text-right">
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