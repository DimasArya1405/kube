@extends('admin.layout')

@section('breadcrumb')
Data Master / <span class="text-gray-800">Data KUBE</span>
@stop

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Data KUBE</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola informasi Kelompok Usaha Bersama, status, dan pembagian pendamping.</p>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="relative w-full md:w-1/3">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 text-sm" placeholder="Cari nama KUBE...">
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <button class="flex items-center px-4 py-2 bg-orange-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600 transition shadow-sm">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </button>
            <button class="flex items-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition shadow-sm">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </button>
            <button onclick="toggleModal('tambahKubeModal')" class="flex items-center px-4 py-2 bg-purple-700 text-white text-sm font-medium rounded-lg hover:bg-purple-800 transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah KUBE
            </button>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm">No.</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm">Nama KUBE</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm">Lokasi</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Cluster Usaha</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Status</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($kubes as $index => $k)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="py-3 px-5 text-gray-800 font-medium">{{ $index + 1 }}.</td>
                    <td class="py-3 px-5 text-gray-800">{{ $k->nama_kube }}</td>
                    <td class="py-3 px-5 text-gray-600">{{ $k->desa->nama_desa_kelurahan ?? '-' }}</td>
                    <td class="py-3 px-5 text-gray-600 text-center">{{ $k->clusterUsaha->nama_cluster ?? '-' }}</td>

                    <td class="py-3 px-5 text-center">
                        @if($k->status == 'Aktif')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold tracking-wide">Aktif</span>
                        @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold tracking-wide">Tidak Aktif</span>
                        @endif
                    </td>

                    <td class="py-3 px-5 text-center">
                        <div class="flex justify-center space-x-3">
                            <a href="#" class="text-gray-400 hover:text-purple-600 transition text-lg"><i class="far fa-eye"></i></a>
                            <a href="#" class="text-gray-400 hover:text-yellow-500 transition text-lg"><i class="far fa-edit"></i></a>

                            <form action="{{ route('kube.destroy', $k->id_kube) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition text-lg" onclick="return confirm('Yakin ingin menghapus KUBE ini?')">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="tambahKubeModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Tambah Data KUBE</h3>
            <button type="button" onclick="toggleModal('tambahKubeModal')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form action="{{ route('kube.store') }}" method="POST">
            @csrf
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama KUBE</label>
                    <input type="text" name="nama_kube" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asal Desa/Kelurahan</label>
                    <select name="id_desa_kelurahan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                        <option value="">-- Pilih Desa --</option>
                        @foreach($desas as $desa)
                        <option value="{{ $desa->id_desa_kelurahan }}">{{ $desa->nama_desa_kelurahan }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cluster Usaha</label>
                    <select name="id_cluster" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                        <option value="">-- Pilih Cluster --</option>
                        @foreach($clusters as $cluster)
                        <option value="{{ $cluster->id_cluster }}">{{ $cluster->nama_cluster }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3 bg-gray-50">
                <button type="button" onclick="toggleModal('tambahKubeModal')" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-purple-700 text-white text-sm font-medium rounded-lg hover:bg-purple-800 transition shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        modal.classList.toggle('hidden');
    }
</script>
@endsection