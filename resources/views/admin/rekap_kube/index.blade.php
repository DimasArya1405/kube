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

{{-- HEADER --}}
<div class="mb-6">
    <h2 class="text-2xl font-extrabold text-gray-900">Rekap Kube</h2>
    <p class="text-gray-600 text-sm">Kelola data rekap kube aktif dan tidak aktif</p>
</div>

{{-- FILTER & EXPORT --}}
<div class="bg-white mb-6 rounded-lg shadow-sm border border-gray-200 p-4">
    <div class="flex flex-col md:flex-row justify-between items-end gap-4">

        {{-- FILTER --}}
        <form action="{{ route('rekap_kube.index') }}" method="GET" class="flex flex-wrap items-end gap-3">

            <div class="w-full md:w-64">
                <label class="block mb-1 text-xs font-bold text-gray-900">Kecamatan</label>
                <select name="id_kecamatan"
                    class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2">
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
                <button type="submit"
                    class="text-white bg-[#3A83A5] text-xs font-bold px-4 py-2 rounded">
                    Filter
                </button>

                <a href="{{ route('rekap_kube.index') }}"
                    class="text-[#3A83A5] border border-[#3A83A5] text-xs px-4 py-2 rounded">
                    Reset
                </a>
            </div>
        </form>

        {{-- EXPORT --}}
        <div class="flex gap-2">
            <button onclick="alert('Fitur PDF belum tersedia')"
                class="text-white bg-[#F27431] text-xs px-3 py-2 rounded">
                Ekspor PDF
            </button>

            <button onclick="alert('Fitur Excel belum tersedia')"
                class="text-white bg-[#22AD42] text-xs px-3 py-2 rounded">
                Ekspor Excel
            </button>
        </div>

    </div>
</div>

{{-- TABEL --}}
<div class="bg-white rounded-lg shadow-md border border-gray-300 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">

            {{-- HEADER --}}
            <thead class="bg-gray-200 text-gray-700 uppercase text-xs tracking-wide">
                <tr>
                    <th class="px-4 py-2.5 text-center">No</th>
                    <th class="px-4 py-2.5 text-left">Kecamatan</th>
                    <th class="px-4 py-2.5 text-center">Jumlah</th>
                    <th class="px-4 py-2.5 text-center">Aktif</th>
                    <th class="px-4 py-2.5 text-center">Tidak Aktif</th>
                    <th class="px-4 py-2.5 text-center">Aksi</th>
                </tr>
            </thead>

            {{-- BODY --}}
            <tbody class="bg-white divide-y divide-gray-200 text-gray-700">
                @forelse ($rekap as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-2.5 text-center font-medium">
                            {{ $index + 1 }}
                        </td>

                        <td class="px-4 py-2.5">
                            {{ $item['nama_kecamatan'] }}
                        </td>

                        <td class="px-4 py-2.5 text-center font-semibold">
                            {{ $item['jumlah_kube'] }}
                        </td>

                        <td class="px-4 py-2.5 text-center text-green-600 font-semibold">
                            {{ $item['kube_aktif'] }}
                        </td>

                        <td class="px-4 py-2.5 text-center text-red-600 font-semibold">
                            {{ $item['kube_tidak_aktif'] }}
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-2.5 text-center">
                            <a href="{{ route('rekap_kube.detail', $item['id_kecamatan']) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                            Belum ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>

            {{-- FOOTER --}}
            @if($rekap->isNotEmpty())
                <tfoot class="bg-gray-200 text-gray-700 uppercase text-xs">
                    <tr class="font-semibold">
                        <td colspan="2" class="px-4 py-2.5">
                            Total Keseluruhan
                        </td>

                        <td class="px-4 py-2.5 text-center">
                            {{ number_format($totalSemuaKube) }}
                        </td>

                        <td class="px-4 py-2.5 text-center text-green-600">
                            {{ number_format($totalSemuaAktif) }}
                        </td>

                        <td class="px-4 py-2.5 text-center text-red-600">
                            {{ number_format($totalSemuaTidakAktif) }}
                        </td>

                        <td></td>
                    </tr>
                </tfoot>
            @endif

        </table>
    </div>
</div>

@stop