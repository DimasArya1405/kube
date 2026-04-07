@extends('admin.layout')

@section('title', 'Tambah Pengajuan KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Tambah Pengajuan KUBE</span>
@stop

@section('content')

<div class="bg-white p-6 rounded-2xl shadow-md">
    
    <h2 class="text-xl font-semibold mb-6">Tambah Pengajuan</h2>
    <p class="text-gray-500 mt-1">Ajukan permohonan bantuan baru</p>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('pengajuan.store') }}" method="POST" class="space-y-5">
        @csrf

        {{-- Nama KUBE --}}
        <div>
            <label class="block text-sm font-medium mb-1">Nama KUBE *</label>
            <select name="id_kube"
                class="w-full border rounded-lg px-3 py-2 bg-white text-black">
                
                <option value="">Pilih Nama KUBE</option>

                @foreach($kube as $k)
                    <option value="{{ $k->id_kube }}">
                        {{ $k->nama_kube }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Jenis Bantuan --}}
        <div>
            <label class="block text-sm font-medium mb-1">Jenis Bantuan *</label>
            <select name="id_jenis_bantuan" id="jenis_bantuan"
                class="w-full border rounded-lg px-3 py-2 bg-white text-black">
                
                <option value="">Pilih Jenis Bantuan</option>

                @foreach($jenisBantuan as $jb)
                    <option value="{{ $jb->id_jenis_bantuan }}">
                        {{ $jb->jenis_bantuan }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Jumlah Bantuan --}}
        <div>
            <label class="block text-sm font-medium mb-1">Jumlah Bantuan</label>
            <div class="flex">
                <span id="satuan" class="px-3 py-2 bg-gray-100 border rounded-l-lg">
                    Rp
                </span>
                <input type="number" name="jumlah_bantuan"
                    class="w-full border px-3 py-2 rounded-r-lg"
                    placeholder="Masukkan jumlah bantuan">
            </div>
        </div>

        {{-- Tujuan --}}
        <div>
            <label class="block text-sm font-medium mb-1">Tujuan Pengajuan *</label>
            <textarea name="tujuan_pengajuan"
                class="w-full border rounded-lg px-3 py-2"
                placeholder="Masukkan tujuan pengajuan"></textarea>
        </div>

        {{-- Tanggal --}}
        <div>
            <label class="block text-sm font-medium mb-1">Tanggal Pengajuan *</label>
            <input type="date" name="tanggal_pengajuan"
                class="w-full border rounded-lg px-3 py-2">
        </div>

        {{-- BUTTON --}}
        <div class="flex justify-end gap-3 pt-4">
            <button type="reset"
                class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400">
                Batal
            </button>

            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Ajukan Pengajuan
            </button>
        </div>

    </form>
</div>

@endsection


@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const jenis = document.getElementById('jenis_bantuan');
    const satuan = document.getElementById('satuan');

    jenis.addEventListener('change', function () {
        const value = this.value;

        if (value == 1) {
            satuan.innerText = 'Rp';
        } else if (value == 2) {
            satuan.innerText = 'Unit';
        } else if (value == 3) {
            satuan.innerText = 'Peserta';
        } else {
            satuan.innerText = '';
        }
    });
});
</script>
@endpush