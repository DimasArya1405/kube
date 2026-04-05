@extends('admin.layout')

@section('title', 'Rekap KUBE - KUBE')

@section('breadcrumb')
<nav class="flex text-gray-700 mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-sm">
        <li class="inline-flex items-center text-gray-500">Dashboard</li>
        <li>
            <div class="flex items-center">
                <span class="mx-2 text-gray-400">/</span>
                <span class="text-gray-800 font-bold">Rekap KUBE</span>
            </div>
        </li>
    </ol>
</nav>
@stop

@section('content')
<div class="mb-6">
    <h2 class="text-3xl font-extrabold text-gray-900">Rekap Kube</h2>
    <p class="text-gray-600">Kelola data rekap kube aktif dan tidak aktif</p>
</div>

{{-- Filter & Export Section --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border border-gray-200 p-6">
    <div class="flex flex-col md:flex-row justify-between items-end gap-6">

        {{-- Form Filter (Sisi Kiri) --}}
        <form action="{{ route('rekap_kube.index') }}" method="GET" class="flex flex-wrap items-end gap-4 flex-grow">

            {{-- FILTER TAHUN (DINONAKTIFKAN DENGAN KOMEN) --}}
            {{--
                <div class="w-full md:w-32">
                    <label for="tahun" class="block mb-2 text-sm font-bold text-gray-900">Tahun:</label>
                    <input type="number" name="tahun" id="tahun" value="{{ request('tahun') }}"
            placeholder="2025"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#3A83A5] focus:border-[#3A83A5] block w-full p-2.5">
    </div>
    --}}

    {{-- FILTER BULAN (DINONAKTIFKAN DENGAN KOMEN) --}}
    {{--
                <div class="w-full md:w-44">
                    <label for="bulan" class="block mb-2 text-sm font-bold text-gray-900">Bulan:</label>
                    <select name="bulan" id="bulan" class="bg-gray-50 border border-gray-300 text-sm rounded-lg block w-full p-2.5">
                        <option value="">Semua Bulan</option>
                        @foreach([1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'] as $key => $val)
                            <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>{{ $val }}</option>
    @endforeach
    </select>
</div>
--}}

{{-- Filter Kecamatan (Aktif) --}}
<div class="w-full md:w-72">
    <label for="id_kecamatan" class="block mb-2 text-sm font-bold text-gray-900">Kecamatan:</label>
    <select name="id_kecamatan" id="id_kecamatan"
        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-[#3A83A5] focus:border-[#3A83A5] block w-full p-2.5">
        <option value="">Semua Kecamatan</option>
        @foreach ($kecamatanList as $kecamatan)
        <option value="{{ $kecamatan->id_kecamatan }}"
            {{ request('id_kecamatan') == $kecamatan->id_kecamatan ? 'selected' : '' }}>
            {{ $kecamatan->nama_kecamatan }}
        </option>
        @endforeach
    </select>
</div>

<div class="flex gap-2">
    <button type="submit" class="text-white bg-[#3A83A5] hover:bg-[#2d6681] font-bold rounded-md text-sm px-6 py-2.5 transition duration-200">
        Filter
    </button>
    <a href="{{ route('rekap_kube.index') }}" class="text-[#3A83A5] border border-[#3A83A5] hover:bg-gray-50 font-bold rounded-md text-sm px-6 py-2.5 transition duration-200">
        Reset
    </a>
</div>
</form>

{{-- Tombol Ekspor (Sisi Kanan) - Tanpa Route Sementara --}}
<div class="flex gap-3 w-full md:w-auto">
    <button type="button" onclick="alert('Fitur Ekspor PDF sedang disiapkan!')"
        class="flex items-center justify-center gap-2 text-white bg-[#F27431] hover:bg-[#d95f26] font-bold rounded-md text-xs px-4 py-2.5 transition w-full md:w-auto shadow-sm cursor-pointer">
        <i class="fas fa-file-pdf"></i> Ekspor PDF
    </button>

    <button type="button" onclick="alert('Fitur Ekspor Excel sedang disiapkan!')"
        class="flex items-center justify-center gap-2 text-white bg-[#22AD42] hover:bg-[#1b8a34] font-bold rounded-md text-xs px-4 py-2.5 transition w-full md:w-auto shadow-sm cursor-pointer">
        <i class="fas fa-file-excel"></i> Ekspor Excel
    </button>
</div>

</div>
</div>

{{-- Tabel Rekap --}}
<div class="bg-white rounded-lg shadow-md border border-gray-300 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left border-collapse">
            <thead class="bg-[#3A83A5] text-white uppercase font-bold">
                <tr>
                    <th class="px-6 py-5 border border-gray-400 text-center w-20">NO.</th>
                    <th class="px-6 py-5 border border-gray-400">Kecamatan</th>
                    <th class="px-6 py-5 border border-gray-400 text-center">Jumlah KUBE</th>
                    <th class="px-6 py-5 border border-gray-400 text-center">KUBE Aktif</th>
                    <th class="px-6 py-5 border border-gray-400 text-center">KUBE Tidak Aktif</th>
                </tr>
            </thead>
            <tbody class="text-gray-900 bg-white">
                @forelse ($rekap as $index => $item)
                <tr class="border-b border-gray-300 hover:bg-gray-50 transition">
                    <td class="px-6 py-4 border border-gray-300 text-center font-bold">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 border border-gray-300 font-semibold">{{ $item['nama_kecamatan'] }}</td>
                    <td class="px-6 py-4 border border-gray-300 text-center font-medium text-lg">{{ $item['jumlah_kube'] }}</td>
                    <td class="px-6 py-4 border border-gray-300 text-center font-medium text-lg">{{ $item['kube_aktif'] }}</td>
                    <td class="px-6 py-4 border border-gray-300 text-center font-medium text-lg">{{ $item['kube_tidak_aktif'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-10 text-center text-gray-400 italic bg-gray-50 border border-gray-300">
                        Tidak ada data yang ditemukan.
                    </td>
                </tr>
                @endforelse

                {{-- Baris Kosong agar mirip Mockup --}}
                @for($i = 1; $i <= 2; $i++)
                    <tr class="h-12 border-b border-gray-300">
                    <td class="border border-gray-300"></td>
                    <td class="border border-gray-300"></td>
                    <td class="border border-gray-300"></td>
                    <td class="border border-gray-300"></td>
                    <td class="border border-gray-300"></td>
                    </tr>
                    @endfor
            </tbody>

            @if($rekap->isNotEmpty())
            <tfoot class="bg-[#3A83A5] text-white font-extrabold">
                <tr>
                    <td colspan="2" class="px-6 py-5 border border-gray-400 text-left text-xl tracking-wide">Total Keseluruhan</td>
                    <td class="px-6 py-5 border border-gray-400 text-center text-xl">{{ number_format($totalSemuaKube) }}</td>
                    <td class="px-6 py-5 border border-gray-400 text-center text-xl">{{ number_format($totalSemuaAktif) }}</td>
                    <td class="px-6 py-5 border border-gray-400 text-center text-xl">{{ number_format($totalSemuaTidakAktif) }}</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@stop