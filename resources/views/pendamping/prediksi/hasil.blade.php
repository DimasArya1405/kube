@extends('pendamping.dashboard.index')

@section('title', 'Hasil Prediksi KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Hasil Prediksi</span>
@stop

@section('content')

<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Hasil Prediksi KUBE</h2>
    <p class="text-gray-500 mt-1">Berikut hasil prediksi KUBE berdasarkan pertanyaan yang telah diisi.</p>
</div>

@if(session('error'))
    <div class="mb-4 p-3 bg-red-100 text-red-700 rounded-lg">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

    {{-- INFORMASI KUBE --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div>
            <p class="text-sm text-gray-500">Nama KUBE</p>
            <p class="font-semibold text-gray-800">{{ $kube->nama_kube ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Bulan / Tahun</p>
            <p class="font-semibold text-gray-800">{{ $bulan ?? '-' }} / {{ $tahun ?? '-' }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">Pendamping</p>
            <p class="font-semibold text-gray-800">{{ $pendamping->nama_pendamping ?? '-' }}</p>
        </div>
    </div>

    {{-- TABEL HASIL --}}
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200 rounded-lg text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 border">No</th>
                    <th class="px-4 py-3 border">Pertanyaan</th>
                    <th class="px-4 py-3 border">Jawaban</th>
                    <th class="px-4 py-3 border">Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pertanyaan as $index => $p)
                    @php
                        $hasil = $hasilPrediksi->firstWhere('id_pertanyaan', $p->id);
                    @endphp
                    <tr class="border-b">
                        <td class="px-4 py-3 border">{{ $index + 1 }}</td>
                        <td class="px-4 py-3 border">{{ $p->pertanyaan }}</td>
                        <td class="px-4 py-3 border">
                            @if($hasil)
                                <span class="{{ $hasil->jawaban ? 'text-green-600 font-semibold' : 'text-red-600 font-semibold' }}">
                                    {{ $hasil->jawaban ? 'Ya' : 'Tidak' }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 border">
                            {{ $hasil->catatan ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-gray-500">
                            Tidak ada data pertanyaan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- HASIL PERHITUNGAN --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <p class="text-sm text-gray-500">Total Poin</p>
            <p class="font-semibold text-gray-800 text-lg">
                {{ $totalPoin ?? 0 }} / {{ count($pertanyaan) }}
            </p>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <p class="text-sm text-gray-500">Persentase</p>
            <p class="font-semibold text-gray-800 text-lg">
                {{ isset($persentase) ? number_format($persentase, 2) . '%' : '0%' }}
            </p>
        </div>

        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <p class="text-sm text-gray-500">Hasil Prediksi</p>
            <p class="font-semibold text-lg {{ ($status ?? '') == 'berhasil' ? 'text-green-600' : 'text-red-600' }}">
                {{ isset($status) ? ucfirst($status) : '-' }}
            </p>
        </div>
    </div>

    {{-- PROGRESS BAR --}}
    <div class="mt-5">
        <div class="w-full bg-gray-200 rounded-full h-4">
            <div
                class="h-4 rounded-full transition-all duration-500 {{ ($status ?? '') == 'berhasil' ? 'bg-green-600' : 'bg-red-500' }}"
                style="width: {{ $persentase ?? 0 }}%">
            </div>
        </div>
    </div>

    {{-- TOMBOL --}}
        
       <div class="flex justify-end mt-8">
    <a href="{{ route('prediksi.daftar') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow transition">
        
        <i class="bi bi-list"></i>
        Lihat Daftar
    </a>
</div>
</div>

@stop