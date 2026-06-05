@extends('admin.layout')

@section('content')

<div class="bg-white p-6 rounded shadow-md max-w-xl mx-auto">

    <h2 class="text-xl font-bold mb-4">Detail Monitoring</h2>

    <p><b>Jenis Bantuan:</b> {{ $data->jenisBantuan->jenis_bantuan }}</p>
    <p><b>KUBE:</b> {{ $data->kube->nama_kube }}</p>
    <p><b>Pendamping:</b> {{ $data->pendamping->nama_pendamping }}</p>
    <p><b>Tanggal:</b> {{ $data->tanggal_monitoring }}</p>
    <p><b>Kesesuaian:</b> {{ $data->kesesuaian }}</p>
    <p><b>Catatan:</b> {{ $data->catatan }}</p>

    <div class="mt-4">
        <b>Foto:</b><br>

        @if($data->foto_monitoring)
            <img src="{{ asset('storage/' . $data->foto_monitoring) }}"
                class="mt-2 rounded shadow"
                style="width:100%; max-height:400px; object-fit:cover;">
        @else
            <p>-</p>
        @endif
    </div>

    <a href="/monitoring" class="mt-4 inline-block bg-gray-500 text-white px-4 py-2 rounded">
        Kembali
    </a>

</div>

@stop