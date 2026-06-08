@extends('admin.layout')

@section('title', 'Daftar Prediksi KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Daftar Prediksi</span>
@stop

@section('content')

<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Daftar Prediksi KUBE</h2>
    <p class="text-gray-500 mt-1">Berikut data seluruh hasil prediksi KUBE yang telah dilakukan.</p>
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

{{-- CARD RINGKASAN --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">

        <div class="bg-green-50 border border-green-100 rounded-xl p-4">
            <p class="text-sm text-gray-500 mb-1">Berhasil</p>
            <h3 class="text-3xl font-bold text-green-600">{{ $jumlahBerhasil }}</h3>
        </div>

        <div class="bg-red-50 border border-red-100 rounded-xl p-4">
            <p class="text-sm text-gray-500 mb-1">Gagal</p>
            <h3 class="text-3xl font-bold text-red-500">{{ $jumlahGagal }}</h3>
        </div>

        <div class="bg-yellow-50 border border-yellow-100 rounded-xl p-4">
            <p class="text-sm text-gray-500 mb-1">Total Prediksi</p>
            <h3 class="text-3xl font-bold text-yellow-500">{{ $totalPrediksi }}</h3>
        </div>

        <div>
            <form method="GET" action="{{ route('admin.prediksi-kube.daftar') }}">
                <label class="block text-sm font-medium text-gray-600 mb-2">Filter Tahun</label>
                <select name="tahun"
                        onchange="this.form.submit()"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

    </div>
</div>

{{-- TABEL --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="px-6 py-4 font-semibold">No</th>
                    <th class="px-6 py-4 font-semibold">Kecamatan</th>
                    <th class="px-6 py-4 font-semibold">Nama KUBE</th>
                    <th class="px-6 py-4 font-semibold">Pendamping</th>
                    <th class="px-6 py-4 font-semibold">Periode Prediksi</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($dataPrediksi as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-700">
                            {{ ($dataPrediksi->currentPage() - 1) * $dataPrediksi->perPage() + $index + 1 }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $item->nama_kecamatan }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $item->nama_kube }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $item->nama_pendamping ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ \Carbon\Carbon::create()->month($item->bulan)->translatedFormat('F') }} {{ $item->tahun }}
                        </td>
                        <td class="px-6 py-4">
                            @if ($item->status == 'Berhasil')
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                                    {{ $item->status }}
                                </span>
                            @else
                                <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-600">
                                    {{ $item->status }}
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.prediksi-kube.detail', $item->id_prediksi) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-blue-50 text-blue-600 hover:bg-blue-100 transition"
                                   title="Lihat Detail">
                                    👁
                                </a>

                                <a href="{{ route('admin.prediksi-kube.track', [$item->id_kube, $item->tahun]) }}"
                                   class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-purple-50 text-purple-600 hover:bg-purple-100 transition"
                                   title="Track Record">
                                    📊
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Data prediksi belum ada.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($dataPrediksi->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-white">
            {{ $dataPrediksi->links() }}
        </div>
    @endif
</div>

@stop