@extends('pendamping.dashboard.index')

@section('title', 'Detail Prediksi KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Detail Prediksi</span>
@stop

@section('content')

<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Detail Prediksi KUBE</h2>
    <p class="text-gray-500 mt-1">Berikut detail hasil prediksi KUBE yang telah dilakukan.</p>
</div>

@if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

    {{-- INFORMASI UTAMA --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">

        <div>
            <p class="text-sm text-gray-500">Nama KUBE</p>
            <p class="font-semibold text-gray-800">{{ $first->nama_kube ?? '-' }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Pendamping</p>
            <p class="font-semibold text-gray-800">{{ $first->nama_pendamping ?? '-' }}</p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Periode Prediksi</p>
            <p class="font-semibold text-gray-800">
                {{ \Carbon\Carbon::create()->month($first->bulan)->translatedFormat('F') }} {{ $first->tahun }}
            </p>
        </div>

        <div>
            <p class="text-sm text-gray-500">Status</p>
            @if($status == 'Berhasil')
                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                    {{ $status }}
                </span>
            @else
                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                    {{ $status }}
                </span>
            @endif
        </div>

    </div>

    {{-- RINGKASAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4">
            <p class="text-sm text-gray-500 mb-1">Total Jawaban Ya</p>
            <h3 class="text-2xl font-bold text-blue-600">{{ $totalYa }}</h3>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
            <p class="text-sm text-gray-500 mb-1">Jumlah Pertanyaan</p>
            <h3 class="text-2xl font-bold text-gray-700">{{ $data->count() }}</h3>
        </div>

        <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4">
            <p class="text-sm text-gray-500 mb-1">Persentase</p>
            <h3 class="text-2xl font-bold text-yellow-600">
                {{ $data->count() > 0 ? number_format(($totalYa / $data->count()) * 100, 2, ',', '.') : 0 }}%
            </h3>
        </div>

    </div>

    {{-- PROGRESS BAR --}}
    <div class="mb-6">
        <div class="w-full bg-gray-200 rounded-full h-4">
            <div
                class="h-4 rounded-full transition-all duration-500 {{ $status == 'Berhasil' ? 'bg-green-600' : 'bg-red-500' }}"
                style="width: {{ $data->count() > 0 ? ($totalYa / $data->count()) * 100 : 0 }}%">
            </div>
        </div>
    </div>

    {{-- TABEL DETAIL --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 font-semibold">No</th>
                    <th class="px-4 py-3 font-semibold">Pertanyaan</th>
                    <th class="px-4 py-3 font-semibold">Jawaban</th>
                    <th class="px-4 py-3 font-semibold">Catatan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 bg-white">
                @foreach($data as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3 text-gray-800">{{ $item->pertanyaan }}</td>

                        <td class="px-4 py-3">
                            @if($item->jawaban == 1)
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                                    Ya
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                                    Tidak
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-3 text-gray-700">
                            {{ $item->catatan ?: '-' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TOMBOL --}}
    <div class="flex justify-end gap-3 mt-6">

        <a href="{{ route('prediksi.daftar') }}"
           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            Kembali
        </a>

        <a href="{{ route('prediksi.edit', $first->id_prediksi) }}"
           class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
            Edit Prediksi
        </a>

    </div>

</div>

@stop