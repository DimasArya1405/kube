@extends('admin.layout')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">
            Detail Laporan KUBE
        </h2>

        <!-- BUTTON EXIT -->
        <a href="{{ route('laporan.kecamatan') }}"
           class="bg-red-500 text-white px-4 py-2 rounded">
           Exit
        </a>
    </div>

    <div class="grid grid-cols-2 gap-4">

        <div>
            <label>Nama KUBE</label>
            <input class="border p-2 w-full"
                   value="{{ $data->nama_kube }}" readonly>
        </div>

        <div>
            <label>Cluster</label>
            <input class="border p-2 w-full"
                   value="{{ $data->nama_cluster }}" readonly>
        </div>

        <div>
            <label>Kecamatan</label>
            <input class="border p-2 w-full"
                   value="{{ $data->nama_kecamatan }}" readonly>
        </div>

        <div>
            <label>Status</label>
            <input class="border p-2 w-full"
                   value="{{ $data->status }}" readonly>
        </div>

        <div>
            <label>Total Omset</label>
            <input class="border p-2 w-full"
                   value="Rp {{ number_format($data->total_omset,0,',','.') }}" readonly>
        </div>

        <div>
            <label>Total Laba Bersih</label>
            <input class="border p-2 w-full"
                   value="Rp {{ number_format($data->laba_bersih,0,',','.') }}" readonly>
        </div>

    </div>

</div>

@endsection