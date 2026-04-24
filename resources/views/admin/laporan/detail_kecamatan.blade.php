@extends('admin.layout')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <!-- HEADER -->
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">
                Detail Laporan KUBE
            </h2>
            <p class="text-sm text-gray-500">
                Informasi lengkap data kelompok usaha
            </p>
        </div>

        <a href="{{ route('laporan.kecamatan') }}"
           class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
           Exit
        </a>
    </div>

    <!-- CARD UTAMA -->
    <div class="bg-gray-50 p-6 rounded-xl border">

        <div class="grid grid-cols-2 gap-6">

            <!-- NAMA -->
            <div>
                <div class="text-sm text-gray-500">Nama KUBE</div>
                <div class="font-semibold text-lg text-gray-800">
                    {{ $data->nama_kube }}
                </div>
            </div>

            <!-- STATUS -->
            <div>
                <div class="text-sm text-gray-500">Status</div>
                <div class="mt-1">
                    @if($data->status == 'aktif')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                            Aktif
                        </span>
                    @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                            Tidak Aktif
                        </span>
                    @endif
                </div>
            </div>

            <!-- KECAMATAN -->
            <div>
                <div class="text-sm text-gray-500">Kecamatan</div>
                <div class="font-semibold text-gray-700">
                    {{ $data->nama_kecamatan }}
                </div>
            </div>

            <!-- CLUSTER -->
            <div>
                <div class="text-sm text-gray-500">Cluster</div>
                <div class="font-semibold text-gray-700">
                    {{ $data->nama_cluster }}
                </div>
            </div>

        </div>

    </div>

    <!-- CARD KEUANGAN -->
    <div class="grid grid-cols-2 gap-6 mt-6">

        <!-- OMSET -->
        <div class="bg-green-50 border border-green-200 p-6 rounded-xl">
            <div class="text-sm text-green-700">
                Total Omset
            </div>
            <div class="text-2xl font-bold text-green-800 mt-2">
                Rp {{ number_format($data->total_omset,0,',','.') }}
            </div>
        </div>

        <!-- LABA -->
        <div class="bg-blue-50 border border-blue-200 p-6 rounded-xl">
            <div class="text-sm text-blue-700">
                Laba Bersih
            </div>
            <div class="text-2xl font-bold text-blue-800 mt-2">
                Rp {{ number_format($data->laba_bersih,0,',','.') }}
            </div>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="mt-8 text-right text-sm text-gray-500">
        Data ditampilkan pada {{ date('d M Y H:i') }}
    </div>

</div>

@endsection