@extends('admin.layout')

@section('title', 'Template Laporan')

@section('content')

<div class="mb-8">
    <h2 class="text-3xl font-bold">
        Template Laporan KUBE
    </h2>

    <p class="text-gray-500">
        Download template laporan yang tersedia.
    </p>
</div>

<div class="grid md:grid-cols-2 gap-6">

    <div class="bg-white rounded-xl shadow p-6">

        <h3 class="text-xl font-semibold mb-3">
            Template Laporan Bulanan
        </h3>

        <p class="text-gray-500 mb-4">
            Format laporan kegiatan bulanan KUBE.
        </p>

        <a href="{{ asset('template/Template_Laporan_Bulanan_KUBE.docx') }}" download
            class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded-lg">
            Download Word
        </a>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

        <h3 class="text-xl font-semibold mb-3">
            Template Laporan Tahunan
        </h3>

        <p class="text-gray-500 mb-4">
            Format laporan tahunan KUBE.
        </p>

        <a href="{{ asset('template/Template_Laporan_Tahunan_KUBE.xlsx') }}" download
            class="bg-green-500 hover:bg-green-600 text-white px-5 py-2 rounded-lg">
            Download Excel
        </a>

    </div>

</div>

@endsection