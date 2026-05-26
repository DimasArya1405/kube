@extends('admin.layout')

@section('title', 'Edit Monitoring')

@section('content')

<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow">

    <h2 class="text-2xl font-bold mb-4">Edit Monitoring</h2>

    <form action="{{ route('monitoring.update',$data->id_monitoring) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- JENIS BANTUAN --}}
        <div class="mb-3">
            <label class="block mb-1">Jenis Bantuan</label>
            <select name="id_jenis_bantuan" class="w-full border rounded p-2">
                @foreach($jenis as $j)
                    <option value="{{ $j->id_jenis_bantuan }}"
                        {{ $data->id_jenis_bantuan == $j->id_jenis_bantuan ? 'selected' : '' }}>
                        {{ $j->jenis_bantuan }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- KUBE --}}
        <div class="mb-3">
            <label class="block mb-1">KUBE</label>
            <select name="id_kube" class="w-full border rounded p-2">
                @foreach($kube as $k)
                    <option value="{{ $k->id_kube }}"
                        {{ $data->id_kube == $k->id_kube ? 'selected' : '' }}>
                        {{ $k->nama_kube }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- PENDAMPING --}}
        <div class="mb-3">
            <label class="block mb-1">Pendamping</label>
            <select name="id_pendamping" class="w-full border rounded p-2">
                @foreach($pendamping as $p)
                    <option value="{{ $p->id_pendamping }}"
                        {{ $data->id_pendamping == $p->id_pendamping ? 'selected' : '' }}>
                        {{ $p->nama_pendamping }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- TANGGAL --}}
        <div class="mb-3">
            <label class="block mb-1">Tanggal</label>
            <input type="date" name="tanggal_monitoring"
                value="{{ $data->tanggal_monitoring }}"
                class="w-full border rounded p-2">
        </div>

        {{-- KESESUAIAN --}}
        <div class="mb-3">
            <label class="block mb-1">Kesesuaian</label>
            <select name="kesesuaian" class="w-full border rounded p-2">
                <option value="sesuai" {{ $data->kesesuaian == 'sesuai' ? 'selected' : '' }}>
                    Sesuai
                </option>
                <option value="tidak sesuai" {{ $data->kesesuaian == 'tidak sesuai' ? 'selected' : '' }}>
                    Tidak Sesuai
                </option>
            </select>
        </div>

        {{-- CATATAN --}}
        <div class="mb-3">
            <label class="block mb-1">Catatan</label>
            <textarea name="catatan" class="w-full border rounded p-2">{{ $data->catatan }}</textarea>
        </div>

        {{-- FOTO --}}
        <div class="mb-3">
            <label class="block mb-1">Foto</label>

            @if($data->foto_monitoring)
                <img src="{{ asset('storage/'.$data->foto_monitoring) }}"
                    class="mb-2 rounded"
                    style="width:100px">
            @endif

            <input type="file" name="foto_monitoring" class="w-full">
        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-2 mt-4">
            <a href="/monitoring"
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

@stop