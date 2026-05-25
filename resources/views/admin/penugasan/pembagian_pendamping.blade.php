@extends('admin.layout')

@section('breadcrumb')
Penugasan / <span class="text-gray-800">Data Pembagian Pendamping</span>
@stop

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Pembagian Pendamping</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola data pembagian pendamping untuk setiap Kelompok Usaha Bersama.</p>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Oops!</strong>
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="relative w-full md:w-1/3">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 text-sm" placeholder="Cari pembagian...">
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('pembagian_pendamping.export.excel') }}" class="flex items-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition shadow-sm">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
            <button onclick="toggleModal('tambahPembagianModal')" class="flex items-center px-4 py-2 bg-purple-700 text-white text-sm font-medium rounded-lg hover:bg-purple-800 transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Pembagian
            </button>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">No.</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Nama KUBE</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Nama Pendamping</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Tgl Pembagian</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Status</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($pembagians as $index => $p)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="py-3 px-5 text-gray-800 text-center font-medium">{{ $index + 1 }}.</td>
                    <td class="py-3 px-5 text-gray-800 text-center">{{ $p->kube->nama_kube ?? 'KUBE Dihapus' }}</td>

                    <td class="py-3 px-5 text-gray-800 text-center">{{ $p->pendamping->nama_pendamping ?? 'Pendamping Dihapus' }}</td>

                    <td class="py-3 px-5 text-gray-600 text-center">{{ \Carbon\Carbon::parse($p->tgl_pembagian)->format('d M Y') }}</td>

                    <td class="py-3 px-5 text-center">
                        @if(strtolower($p->status) == 'aktif')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold tracking-wide">Aktif</span>
                        @else
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold tracking-wide">Selesai</span>
                        @endif
                    </td>

                    <td class="py-3 px-5 text-center">
                        <div class="flex justify-center space-x-3">
                            <form action="{{ route('pembagian_pendamping.destroy', $p->id_pembagian) }}" method="POST" class="inline" id="deleteForm-{{ $p->id_pembagian }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-gray-400 hover:text-red-500 transition text-lg" onclick="confirmDelete(event, '{{ $p->id_pembagian }}')">
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

<div id="tambahPembagianModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Tambah Pembagian</h3>
            <button type="button" onclick="toggleModal('tambahPembagianModal')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form action="{{ route('pembagian_pendamping.store') }}" method="POST">
            @csrf
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih KUBE</label>
                    <select name="id_kube" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                        <option value="">-- Pilih KUBE --</option>
                        @foreach($kubes as $kube)
                        <option value="{{ $kube->id_kube }}">{{ $kube->nama_kube }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Pendamping</label>
                    <select name="id_pendamping" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                        <option value="">-- Pilih Pendamping --</option>
                        @foreach($pendampings as $pendamping)
                        <option value="{{ $pendamping->id_pendamping }}">{{ $pendamping->nama_pendamping }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembagian</label>
                    <input type="date" name="tgl_pembagian" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status Pembagian</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3 bg-gray-50">
                <button type="button" onclick="toggleModal('tambahPembagianModal')" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-purple-700 text-white text-sm font-medium rounded-lg hover:bg-purple-800 transition shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleModal(modalID) {
        document.getElementById(modalID).classList.toggle('hidden');
    }

    // 🔥 Tangkap 'event'-nya di sini
    function confirmDelete(event, id_kube) {

        // 🔥 INI REM TANGANNYA! Tahan form biar ga langsung ke-submit
        event.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data Pembagian Pendamping ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Data!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Kalau user udah beneran ngeklik "Ya", baru kita lepas remnya dan kirim formnya
                document.getElementById('deleteForm-' + id_kube).submit();
            }
        });
    }
</script>
@endsection