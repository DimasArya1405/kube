@extends('admin.layout')

@section('title', 'Detail Galeri')

@section('content')

<div class="bg-white rounded-2xl shadow p-8">

    <div class="mb-6">
    <a href="{{ url('/admin/dashboard') }}"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
        Kembali
    </a>
</div>

    <img src="{{ asset('images/' . $galeri->gambar) }}"
        class="w-full h-[500px] object-cover rounded-xl mb-6">

    <h1 class="text-4xl font-bold mb-3">
        {{ $galeri->judul }}
    </h1>

    <p class="text-gray-500 mb-4">
        {{ \Carbon\Carbon::parse($galeri->tanggal)->format('d F Y') }}
    </p>

    <p class="text-lg leading-8 text-gray-700">
        {{ $galeri->deskripsi }}
    </p>

</div>

@endsection