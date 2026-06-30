@extends('ketua_kube.layout') @section('content')
<div class="p-6 max-w-3xl mx-auto">
    <div class="mb-6 text-center">
        <h2 class="text-3xl font-bold text-gray-800">Pengajuan KUBE Baru</h2>
        <p class="text-gray-500 mt-2">Anda belum memiliki KUBE. Silakan isi formulir di bawah ini untuk mengajukan Kelompok Usaha Bersama Anda.</p>
    </div>

    <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100">
        <form action="{{ route('kube.store') }}" method="POST">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Nama KUBE</label>
                    <input type="text" name="nama_kube" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Desa/Kelurahan</label>
                        <select name="id_desa_kelurahan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- Pilih Desa --</option>
                            @foreach($desas as $desa)
                                <option value="{{ $desa->id_desa_kelurahan }}">{{ $desa->nama_desa_kelurahan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Cluster Usaha</label>
                        <select name="id_cluster" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                            <option value="">-- Pilih Cluster --</option>
                            @foreach($clusters as $cluster)
                                <option value="{{ $cluster->id_cluster }}">{{ $cluster->nama_cluster }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" required placeholder="Jelaskan secara singkat usaha KUBE Anda..."></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-3 bg-blue-700 text-white font-bold rounded-lg hover:bg-blue-800 transition">Ajukan KUBE Sekarang</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection