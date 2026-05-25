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

    <!-- @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
        <strong class="font-bold">Gagal Menyimpan Data!</strong>
        <ul class="list-disc ml-5 mt-1">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif -->

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <div class="relative w-full md:w-1/3">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                <i class="fas fa-search"></i>
            </span>
            <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 text-sm" placeholder="Cari nama KUBE...">
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('kube.export.pdf') }}" class="flex items-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600 transition shadow-sm">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>

            <a href="{{ route('kube.export.excel') }}" class="flex items-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition shadow-sm">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
            <button onclick="toggleModal('tambahKubeModal')" class="flex items-center px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded-lg hover:bg-purple-800 transition shadow-sm">
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
                            <a href="{{ route('kube.show', $k->id_kube) }}" class="text-gray-400 hover:text-purple-600 transition text-lg"><i class="far fa-eye"></i></a>

                            <button type="button" onclick="toggleModal('editKubeModal{{ $k->id_kube }}')" class="text-gray-400 hover:text-yellow-500 transition text-lg">
                                <i class="far fa-edit"></i>
                            </button>

                            <form action="{{ route('kube.destroy', $k->id_kube) }}" method="POST" class="inline" id="deleteForm-{{ $k->id_kube }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-gray-400 hover:text-red-500 transition text-lg" onclick="confirmDelete(event, '{{ $k->id_kube }}')">
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
</div> @foreach($kubes as $k)

<div id="editKubeModal{{ $k->id_kube }}" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl mx-4 overflow-hidden text-left relative">
        <button type="button" onclick="toggleModal('editKubeModal{{ $k->id_kube }}')" class="absolute top-5 right-6 text-gray-400 hover:text-gray-600 focus:outline-none">
            <i class="fas fa-times text-2xl"></i>
        </button>

        <div class="px-8 py-6">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Ubah Data KUBE</h3>

            <form action="{{ route('kube.update', $k->id_kube) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Nama KUBE</label>
                        <input type="text" name="nama_kube" value="{{ $k->nama_kube }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-gray-700" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kecamatan</label>
                        <input type="text"
                            value="{{ $k->desa->kecamatan->nama_kecamatan ?? '-' }}"
                            disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Desa/Kelurahan</label>
                        <select name="id_desa_kelurahan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-gray-700" required>
                            @foreach($desas as $desa)
                            <option value="{{ $desa->id_desa_kelurahan }}" {{ $k->id_desa_kelurahan == $desa->id_desa_kelurahan ? 'selected' : '' }}>{{ $desa->nama_desa_kelurahan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Kategori</label>
                        <input type="text"
                            value="{{ $k->clusterUsaha->kategori->nama_kategori ?? '-' }}"
                            disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Cluster</label>
                        <select name="id_cluster" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-gray-700" required>
                            @foreach($clusters as $cluster)
                            <option value="{{ $cluster->id_cluster }}" {{ $k->id_cluster == $cluster->id_cluster ? 'selected' : '' }}>{{ $cluster->nama_cluster }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Koordinator</label>
                        <input type="text"
                            value="{{ $k->pembagianPendampingAktif->pembagianKoordinator->koordinator->nama_koor ?? 'Belum ada Koordinator' }}"
                            disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Pendamping</label>
                        <input type="text"
                            value="{{ $k->pembagianPendampingAktif->pendamping->nama_pendamping ?? 'Belum ada Pendamping' }}"
                            disabled
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Tanggal Dibentuk</label>
                        <input type="date" name="tanggal_terbentuk" value="{{ $k->tanggal_terbentuk }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-gray-700" required>
                            <option value="Aktif" {{ $k->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ $k->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <div class="col-span-1 md:col-span-2 mt-2">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-gray-700" placeholder="Tambahkan keterangan jika ada...">{{ $k->keterangan }}</textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-center gap-4">
                    <button type="button" onclick="toggleModal('editKubeModal{{ $k->id_kube }}')" class="px-8 py-2 bg-gray-500 text-white font-bold rounded-lg hover:bg-gray-600 transition">Batal</button>
                    <button type="submit" class="px-8 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Akun Ketua KUBE</label>
                    <select name="id_user" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                        <option value="">-- Pilih Akun Pendaftar --</option>
                        @foreach($calonKetua as $ketua)
                        <option value="{{ $ketua->id_user }}">{{ $ketua->nama }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">*Pilih akun pengguna yang akan menjadi Ketua di KUBE ini.</p>
                </div>
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

    // 🔥 Tangkap 'event'-nya di sini
    function confirmDelete(event, id_kube) {

        // 🔥 INI REM TANGANNYA! Tahan form biar ga langsung ke-submit
        event.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data KUBE ini akan dihapus secara permanen!",
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