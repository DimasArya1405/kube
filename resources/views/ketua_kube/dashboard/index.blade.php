@extends('ketua_kube.layout')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-4 mt-8">
        <h3 class="text-xl font-bold text-gray-800 uppercase">Daftar Anggota</h3>
        <button onclick="toggleModal('tambahAnggotaModal')" class="px-4 py-2 bg-purple-700 text-white text-sm font-bold rounded-lg hover:bg-purple-800 transition">
            <i class="fas fa-plus mr-1"></i> Tambah Anggota
        </button>
    </div>

    </div>

<div id="tambahAnggotaModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Tambah Anggota Tim</h3>
            <button type="button" onclick="toggleModal('tambahAnggotaModal')" class="text-gray-400 hover:text-gray-600 focus:outline-none"><i class="fas fa-times text-lg"></i></button>
        </div>

        <form action="{{ route('anggota_kube.store') }}" method="POST">
            @csrf
            
            <input type="hidden" name="id_kube" value="{{ $kube->id_kube }}">

            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asal KUBE</label>
                    <input type="text" value="{{ $kube->nama_kube }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 font-bold cursor-not-allowed">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                    <input type="text" name="nik" maxlength="16" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anggota</label>
                    <input type="text" name="nama_anggota" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                    <select name="jabatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="Sekretaris">Sekretaris</option>
                        <option value="Bendahara">Bendahara</option>
                        <option value="Anggota">Anggota Biasa</option>
                    </select>
                </div>
                
                </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3 bg-gray-50">
                <button type="button" onclick="toggleModal('tambahAnggotaModal')" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-purple-700 text-white font-bold rounded-lg hover:bg-purple-800">Simpan Anggota</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle('hidden');
    }
</script>
@endsection