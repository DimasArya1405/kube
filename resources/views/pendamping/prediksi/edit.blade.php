@extends('pendamping.dashboard.index')

@section('title', 'Edit Prediksi KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Edit Prediksi</span>
@stop

@section('content')

<div class="mb-8">
    <h2 class="text-3xl font-bold text-gray-800">Edit Prediksi KUBE</h2>
    <p class="text-gray-500 mt-1">Silakan perbarui jawaban prediksi di bawah ini.</p>
</div>

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
            <p class="text-sm text-gray-500">Jumlah Pertanyaan</p>
            <p class="font-semibold text-gray-800">{{ $data->count() }}</p>
        </div>
    </div>

    <form action="{{ route('prediksi.update', $first->id_prediksi) }}" method="POST">
        @csrf
        @method('PUT')

        @foreach($data as $item)
            <div class="border border-gray-200 p-4 mb-4 rounded-lg bg-white">
                <p class="mb-3 font-medium text-gray-800">
                    {{ $loop->iteration }}. {{ $item->pertanyaan }}
                </p>

                <div class="flex gap-6 mb-3">
                    <label class="cursor-pointer flex items-center gap-2">
                        <input type="radio"
                               name="jawaban[{{ $item->id_pertanyaan }}]"
                               value="ya"
                               {{ $item->jawaban == 1 ? 'checked' : '' }}
                               required>
                        <span>Ya</span>
                    </label>

                    <label class="cursor-pointer flex items-center gap-2">
                        <input type="radio"
                               name="jawaban[{{ $item->id_pertanyaan }}]"
                               value="tidak"
                               {{ $item->jawaban == 0 ? 'checked' : '' }}
                               required>
                        <span>Tidak</span>
                    </label>
                </div>

                <input type="text"
                       name="catatan[{{ $item->id_pertanyaan }}]"
                       value="{{ $item->catatan }}"
                       class="w-full border border-gray-300 p-2.5 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Keterangan untuk pertanyaan {{ $loop->iteration }}...">
            </div>
        @endforeach

        <div class="flex justify-end gap-3 mt-6">
            <a href="{{ route('prediksi.daftar') }}"
               class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                Kembali
            </a>

            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>

</div>

@stop