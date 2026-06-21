@extends('admin.layout')

@section('breadcrumb')
Bimbingan / <span class="text-gray-800">Edit Data Bimbingan KUBE</span>
@stop

@section('content')

<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">

    <h2 class="text-2xl font-bold mb-4">Edit Bimbingan KUBE</h2>

    {{-- ERROR VALIDATION --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('bimbingan.update', $bimbingan->id_bimbingan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- JADWAL --}}
        <div class="mb-3">
            <label class="block mb-1">Jadwal</label>
            <input type="number" name="id_jadwal"
                value="{{ $bimbingan->id_jadwal }}"
                class="w-full border rounded p-2">
        </div>

        {{-- PENDAMPING (FIX) --}}
        <div class="mb-3">
            <label class="block mb-1">Pendamping</label>

            <input type="text" value="Siti Aryani"
                class="w-full border rounded p-2 bg-gray-100"
                readonly>

            <input type="hidden" name="id_pendamping" value="1">
        </div>

        {{-- KUBE --}}
        <div class="mb-3">
            <label class="block mb-1">KUBE</label>
            <select name="id_kube" class="w-full border rounded p-2">
                @foreach($kubes as $k)
                    <option value="{{ $k->id_kube }}"
                        {{ $bimbingan->id_kube == $k->id_kube ? 'selected' : '' }}>
                        {{ $k->nama_kube }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- JENIS --}}
        <div class="mb-3">
            <label class="block mb-1">Jenis Bimbingan</label>
            <select name="jenis_bimbingan" class="w-full border rounded p-2">
                <option value="Manajemen Usaha" {{ $bimbingan->jenis_bimbingan == 'Manajemen Usaha' ? 'selected' : '' }}>Manajemen Usaha</option>
                <option value="Pencatatan Keuangan" {{ $bimbingan->jenis_bimbingan == 'Pencatatan Keuangan' ? 'selected' : '' }}>Pencatatan Keuangan</option>
                <option value="Strategi Pemasaran" {{ $bimbingan->jenis_bimbingan == 'Strategi Pemasaran' ? 'selected' : '' }}>Strategi Pemasaran</option>
                <option value="Motivasi" {{ $bimbingan->jenis_bimbingan == 'Motivasi' ? 'selected' : '' }}>Motivasi</option>
                <option value="Mediasi" {{ $bimbingan->jenis_bimbingan == 'Mediasi' ? 'selected' : '' }}>Mediasi</option>
            </select>
        </div>

        {{-- MATERI --}}
        <div class="mb-3">
            <label class="block mb-1">Materi</label>
            <input type="text" name="materi_bimbingan"
                value="{{ $bimbingan->materi_bimbingan }}"
                class="w-full border rounded p-2">
        </div>

        {{-- TANGGAL --}}
        <div class="mb-3">
            <label class="block mb-1">Tanggal</label>
            <input type="date" name="tanggal_bimbingan"
                value="{{ $bimbingan->tanggal_bimbingan }}"
                class="w-full border rounded p-2">
        </div>

        {{-- STATUS --}}
        <div class="mb-3">
            <label class="block mb-1">Status</label>
            <select name="status_bimbingan" class="w-full border rounded p-2">
                <option value="Terlaksana" {{ $bimbingan->status_bimbingan == 'Terlaksana' ? 'selected' : '' }}>Terlaksana</option>
                <option value="Dijadwalkan" {{ $bimbingan->status_bimbingan == 'Dijadwalkan' ? 'selected' : '' }}>Dijadwalkan</option>
                <option value="Ditunda" {{ $bimbingan->status_bimbingan == 'Ditunda' ? 'selected' : '' }}>Ditunda</option>
            </select>
        </div>

        {{-- HASIL --}}
        <div class="mb-3">
            <label class="block mb-1">Hasil Bimbingan</label>
            <textarea name="hasil_bimbingan" class="w-full border rounded p-2">{{ $bimbingan->hasil_bimbingan }}</textarea>
        </div>

        {{-- TINDAK LANJUT --}}
        <div class="mb-3">
            <label class="block mb-1">Tindak Lanjut</label>
            <textarea name="tindak_lanjut" class="w-full border rounded p-2">{{ $bimbingan->tindak_lanjut }}</textarea>
        </div>

        {{-- LAMPIRAN --}}
        <div class="mb-3">
            <label class="block mb-1">Lampiran</label>

            @if($bimbingan->lampiran)
                <a href="{{ asset('storage/'.$bimbingan->lampiran) }}" target="_blank"
                    class="text-blue-500 underline block mb-2">
                    Lihat File
                </a>
            @endif

            <input type="file" name="lampiran" class="w-full">
        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-2 mt-4">
            <a href="{{ route('bimbingan.index') }}"
                class="bg-gray-400 text-white px-4 py-2 rounded">
                Kembali
            </a>

            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded">
                Update
            </button>
        </div>

    </form>

</div>

@endsection