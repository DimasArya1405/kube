@extends('admin.layout')

@section('title', 'Track Record KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Track Record</span>
@stop

@section('content')

<div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

    <div>
        <h2 class="text-3xl font-bold text-gray-800">
            Track Record KUBE
        </h2>
        <p class="text-gray-500 mt-1">
            {{ $kube->nama_kube }} - Tahun {{ $tahun }}
        </p>
    </div>

    {{-- FILTER TAHUN --}}
    <div>
        <label class="block text-sm font-medium text-gray-600 mb-2">Filter Tahun</label>
        <select
            onchange="window.location.href=this.value"
            class="rounded-xl border border-gray-300 px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        >
            @foreach($tahunList as $th)
                <option value="{{ route('admin.prediksi-kube.track', [$kube->id_kube, $th]) }}"
                    {{ $tahun == $th ? 'selected' : '' }}>
                    {{ $th }}
                </option>
            @endforeach
        </select>
    </div>

</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
<div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">

    <div class="bg-white border rounded-xl p-5 shadow-sm">
        <p class="text-sm text-gray-500">Jumlah Bulan Diprediksi</p>
        <h3 class="text-2xl font-bold text-gray-800 mt-1">
            {{ $jumlahBulanPrediksi }}
        </h3>
    </div>

    <div class="bg-white border rounded-xl p-5 shadow-sm">
        <p class="text-sm text-gray-500">Rata-rata Persentase</p>
        <h3 class="text-2xl font-bold text-blue-600 mt-1">
            {{ number_format($rataPerBulan, 2, ',', '.') }}%
        </h3>
    </div>

    <div class="bg-white border rounded-xl p-5 shadow-sm">
        <p class="text-sm text-gray-500">Kesimpulan Tahun {{ $tahun }}</p>

        <h3 class="text-2xl font-bold mt-1
            {{ $kesimpulanTahunan == 'Berhasil'
                ? 'text-green-600'
                : 'text-red-600' }}">
            {{ $kesimpulanTahunan }}
        </h3>
    </div>

</div>
    {{-- TABEL --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left border border-gray-200 rounded-lg overflow-hidden">

            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-4 py-3 font-semibold text-center">Bulan</th>
                    <th class="px-4 py-3 font-semibold text-center">Status</th>
                    <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 bg-white">
                @foreach(range(1,12) as $bulan)
                    <tr class="text-center hover:bg-gray-50">

                        {{-- BULAN --}}
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}
                        </td>

                        {{-- STATUS --}}
                        <td class="px-4 py-3">
                            @if(isset($prediksiPerBulan[$bulan]))
                                @if($prediksiPerBulan[$bulan]['status'] == 'Berhasil')
                                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                                        Berhasil
                                    </span>
                                @else
                                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                                        Gagal
                                    </span>
                                @endif
                            @else
                                <span class="text-gray-400 text-sm">Belum Ada</span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="px-4 py-3">
                            @if(isset($prediksiPerBulan[$bulan]))
                                <a href="{{ route('admin.prediksi-kube.detail', $prediksiPerBulan[$bulan]['id_prediksi']) }}"
                                   class="inline-flex items-center justify-center px-3 py-1 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-sm">
                                    Lihat Detail
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>

                    </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    {{-- TOMBOL --}}
    <div class="flex justify-end mt-6">
        <a href="{{ route('admin.prediksi-kube.daftar') }}"
           class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
            Kembali
        </a>
    </div>

</div>

@endsection