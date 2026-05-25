@extends('admin.layout')

@section('title', 'Detail Rekap KUBE')

@section('content')

<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Detail KUBE</h2>
    {{-- Subtitle dinamis sesuai filter --}}
    <p class="text-gray-500 text-sm">
        Daftar KUBE di Kecamatan {{ $namaKecamatan }}
        @if($namaKategori)
            &mdash; Kategori: <span class="font-semibold text-gray-700">{{ $namaKategori }}</span>
        @endif
    </p>
</div>

{{-- BUTTON KEMBALI --}}
<div class="mb-4">
    <a href="{{ route('rekap_kube.index') }}{{ $id_kategori ? '?id_kategori=' . $id_kategori : '' }}"
       class="bg-gray-500 text-white px-4 py-2 rounded text-sm">
        ← Kembali
    </a>
</div>

{{-- TABEL --}}
<div class="bg-white rounded-lg shadow-md border border-gray-300 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">

            <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-2 text-center">No</th>
                    <th class="px-4 py-2 text-left">Nama KUBE</th>
                    <th class="px-4 py-2 text-left">Desa/Kelurahan</th>
                    <th class="px-4 py-2 text-left">Kategori</th>
                    <th class="px-4 py-2 text-center">Status</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse ($data as $index => $item)
                    <tr>
                        <td class="px-4 py-2 text-center">{{ $index + 1 }}</td>

                        <td class="px-4 py-2">
                            {{ $item->nama_kube }}
                        </td>

                        <td class="px-4 py-2">
                            {{ $item->desa->nama_desa_kelurahan ?? '-' }}
                        </td>

                        <td class="px-4 py-2">
                            {{ $item->clusterUsaha->kategori->nama_kategori ?? '-' }}
                        </td>

                        <td class="px-4 py-2 text-center">
                            @if($item->status == 'Aktif')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                                    Aktif
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs">
                                    Tidak Aktif
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

@endsection