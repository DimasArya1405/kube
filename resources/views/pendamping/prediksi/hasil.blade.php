@extends('admin.layout')

@section('title', 'Hasil Prediksi KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Hasil Prediksi</span>
@stop

@section('content')

<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Hasil Prediksi KUBE</h2>
    <p class="text-gray-500 mt-1">Berikut hasil prediksi KUBE berdasarkan pertanyaan yang telah diisi.</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

```
{{-- INFORMASI KUBE --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div>
        <p class="text-gray-500">Nama KUBE</p>
        <p class="font-semibold text-gray-800">{{ $kube->nama_kube ?? '-' }}</p>
    </div>
    <div>
        <p class="text-gray-500">Bulan/Tahun</p>
        <p class="font-semibold text-gray-800">{{ $bulan ?? '-' }} / {{ $tahun ?? '-' }}</p>
    </div>
    <div>
        <p class="text-gray-500">Pendamping</p>
        <p class="font-semibold text-gray-800">{{ $pendamping->nama_pendamping ?? '-' }}</p>
    </div>
</div>

{{-- TABEL HASIL --}}
<div class="overflow-x-auto">
    <table class="w-full border border-gray-200 rounded-lg text-left">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-4 py-2 border">No</th>
                <th class="px-4 py-2 border">Pertanyaan</th>
                <th class="px-4 py-2 border">Jawaban</th>
                <th class="px-4 py-2 border">Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pertanyaan as $index => $p)
                @php
                    $hasil = $hasilPrediksi->firstWhere('id_pertanyaan', $p->id);
                @endphp
                <tr class="border-b">
                    <td class="px-4 py-2 border">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 border">{{ $p->pertanyaan }}</td>

                    {{-- JAWABAN --}}
                    <td class="px-4 py-2 border">
                        @if($hasil)
                            {{ $hasil->jawaban ? 'Ya' : 'Tidak' }}
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>

                    {{-- CATATAN --}}
                    <td class="px-4 py-2 border">
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
    <div>
        <p class="text-gray-500">Total Poin</p>
        <p class="font-semibold text-gray-800">
            {{ $totalPoin ?? 0 }} / {{ count($pertanyaan) }}
        </p>
    </div>
    <div>
        <p class="text-gray-500">Persentase</p>
        <p class="font-semibold text-gray-800">
            {{ isset($persentase) ? number_format($persentase, 2) . '%' : '0%' }}
        </p>
    </div>
    <div>
        <p class="text-gray-500">Hasil Prediksi</p>
        <p class="font-semibold 
            {{ ($status ?? '') == 'berhasil' ? 'text-green-600' : 'text-red-600' }}">
            {{ isset($status) ? ucfirst($status) : '-' }}
        </p>
    </div>
</div>

{{-- PROGRESS BAR --}}
<div class="w-full bg-gray-200 rounded-full h-4 mt-4">
    <div 
        class="h-4 rounded-full transition-all duration-500
            {{ ($status ?? '') == 'berhasil' ? 'bg-green-600' : 'bg-red-500' }}"
        style="width: {{ $persentase ?? 0 }}%">
    </div>
</div>
```

</div>

@stop
