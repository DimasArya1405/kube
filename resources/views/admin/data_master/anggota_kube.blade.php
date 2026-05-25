@extends('admin.layout')

@section('breadcrumb')
Data Master / <span class="text-gray-800">Data Anggota KUBE</span>
@stop

@section('content')
<div class="p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Data Anggota</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola informasi seluruh anggota KUBE yang terdaftar, termasuk NIK, jabatan, dan kontak.</p>
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
            <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 bg-gray-50 text-sm" placeholder="Cari nama Anggota...">
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <a href="{{ route('anggota.export.pdf') }}" class="flex items-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg hover:bg-orange-600 transition shadow-sm">
                <i class="fas fa-file-pdf mr-2"></i> Export PDF
            </a>

            <a href="{{ route('anggota.export.excel') }}" class="flex items-center px-4 py-2 bg-green-500 text-white text-sm font-medium rounded-lg hover:bg-green-600 transition shadow-sm">
                <i class="fas fa-file-excel mr-2"></i> Export Excel
            </a>
            
            <button onclick="toggleModal('tambahAnggotaModal')" class="flex items-center px-4 py-2 bg-blue-700 text-white text-sm font-medium rounded-lg hover:bg-purple-800 transition shadow-sm">
                <i class="fas fa-plus mr-2"></i> Tambah Anggota
            </button>
        </div>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b border-gray-200">
                <tr>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">No.</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">NIK</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Nama Anggota</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Asal KUBE</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Jabatan</th>
                    <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @foreach($anggotas as $index => $anggota)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="py-3 px-5 text-gray-800 text-center">{{ $index + 1 }}.</td>
                    <td class="py-3 px-5 text-gray-800 text-center">{{ $anggota->nik }}</td>
                    <td class="py-3 px-5 text-gray-800 text-center">{{ $anggota->nama_anggota }}</td>
                    <td class="py-3 px-5 text-gray-600 text-center">{{ $anggota->kube->nama_kube ?? '-' }}</td>

                    <td class="py-3 px-5 text-center">
                        @php
                        $jabatan = strtolower($anggota->jabatan);
                        @endphp

                        @if($jabatan == 'ketua')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Ketua
                        </span>

                        @elseif($jabatan == 'sekretaris')
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Sekretaris
                        </span>

                        @elseif($jabatan == 'bendahara')
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Bendahara
                        </span>

                        @else
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                            Anggota
                        </span>
                        @endif
                    </td>

                    <td class="py-3 px-5 text-center">
                        <div class="flex justify-center space-x-3">
                            <button type="button" onclick="toggleModal('detailAnggotaModal{{ $anggota->id_anggota }}')" class="text-gray-400 hover:text-purple-600 transition text-lg"><i class="far fa-eye"></i></button>

                            <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="text-gray-400 hover:text-yellow-500 transition text-lg"><i class="far fa-edit"></i></button>

                            <form action="{{ route('anggota_kube.destroy', $anggota->id_anggota) }}" method="POST" class="inline" id="deleteForm-{{ $anggota->id_anggota }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="text-gray-400 hover:text-red-500 transition text-lg" onclick="confirmDelete(event, '{{ $anggota->id_anggota }}')">
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
</div> @foreach($anggotas as $index => $anggota)

<div id="detailAnggotaModal{{ $anggota->id_anggota }}" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden text-left relative">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Detail Anggota</h3>
            <button type="button" onclick="toggleModal('detailAnggotaModal{{ $anggota->id_anggota }}')" class="text-gray-400 hover:text-gray-600 focus:outline-none"><i class="fas fa-times text-lg"></i></button>
        </div>
        <div class="px-6 py-4 space-y-3">
            <div class="border-b pb-2">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Nama Anggota</p>
                <p class="text-gray-800 font-medium text-lg">{{ $anggota->nama_anggota }}</p>
            </div>
            <div class="border-b pb-2">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">NIK</p>
                <p class="text-gray-800 font-medium">{{ $anggota->nik }}</p>
            </div>
            <div class="border-b pb-2">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Asal KUBE</p>
                <p class="text-gray-800 font-medium">{{ $anggota->kube->nama_kube ?? '-' }}</p>
            </div>
            <div class="border-b pb-2">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Jabatan</p>
                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold">{{ $anggota->jabatan }}</span>
            </div>
            <div class="border-b pb-2">
                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">No. HP</p>
                <p class="text-gray-800 font-medium">{{ $anggota->no_hp }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-bold">Alamat Lengkap</p>
                <p class="text-gray-800 text-sm mt-1 bg-gray-50 p-3 rounded-lg border">{{ $anggota->alamat }}</p>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end bg-gray-50">
            <button type="button" onclick="toggleModal('detailAnggotaModal{{ $anggota->id_anggota }}')" class="px-6 py-2 bg-purple-700 text-white font-medium rounded-lg hover:bg-purple-800 transition shadow-sm">Tutup</button>
        </div>
    </div>
</div>

<div id="editAnggotaModal{{ $anggota->id_anggota }}" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden text-left relative">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Ubah Data Anggota</h3>
            <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="text-gray-400 hover:text-gray-600 focus:outline-none"><i class="fas fa-times text-lg"></i></button>
        </div>

        <form action="{{ route('anggota_kube.update', $anggota->id_anggota) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="px-6 py-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asal KUBE</label>
                    <select name="id_kube" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 bg-white text-sm transition" required>
                        @foreach($kubes as $kube)
                        <option value="{{ $kube->id_kube }}" {{ $anggota->id_kube == $kube->id_kube ? 'selected' : '' }}>{{ $kube->nama_kube }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                    <input type="text" name="nik" value="{{ $anggota->nik }}" maxlength="16" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 bg-white text-sm transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anggota</label>
                    <input type="text" name="nama_anggota" value="{{ $anggota->nama_anggota }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 bg-white text-sm transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                    <select name="jabatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 bg-white text-sm transition" required>
                        <option value="Ketua" {{ $anggota->jabatan == 'Ketua' ? 'selected' : '' }}>Ketua</option>
                        <option value="Sekretaris" {{ $anggota->jabatan == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                        <option value="Bendahara" {{ $anggota->jabatan == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                        <option value="Anggota" {{ $anggota->jabatan == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                    </select>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                    <input type="text" name="no_hp" value="{{ $anggota->no_hp }}" maxlength="15" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 bg-white text-sm transition" required>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 bg-white text-sm transition" required>{{ $anggota->alamat }}</textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3 bg-gray-50">
                <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-yellow-500 text-white text-sm font-medium rounded-lg hover:bg-yellow-600 transition shadow-sm">Update Data</button>
            </div>
        </form>
    </div>
</div>
@endforeach


<div id="tambahAnggotaModal" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Tambah Data Anggota</h3>
            <button type="button" onclick="toggleModal('tambahAnggotaModal')" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form action="{{ route('anggota_kube.store') }}" method="POST">
            @csrf
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Asal KUBE</label>
                    <select name="id_kube" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                        <option value="">-- Pilih KUBE --</option>
                        @foreach($kubes as $kube)
                        <option value="{{ $kube->id_kube }}">{{ $kube->nama_kube }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                    <input type="text" name="nik" maxlength="16" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anggota</label>
                    <input type="text" name="nama_anggota" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                    <select name="jabatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="Ketua">Ketua</option>
                        <option value="Sekretaris">Sekretaris</option>
                        <option value="Bendahara">Bendahara</option>
                        <option value="Anggota">Anggota</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                    <input type="text" name="no_hp" maxlength="15" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 bg-white text-sm transition" required></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3 bg-gray-50">
                <button type="button" onclick="toggleModal('tambahAnggotaModal')" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">Batal</button>
                <button type="submit" class="px-4 py-2 bg-purple-700 text-white text-sm font-medium rounded-lg hover:bg-purple-800 transition shadow-sm">Simpan Data</button>
            </div>
        </form>
    </div>
</div>


<script>
    // Memastikan function toggleModal dideklarasikan agar bisa dipanggil dari tombol
    function toggleModal(modalID) {
        const modal = document.getElementById(modalID);
        modal.classList.toggle('hidden');
    }

    // 🔥 Tangkap 'event'-nya di sini
    function confirmDelete(event, id_anggota) {

        // 🔥 INI REM TANGANNYA! Tahan form biar ga langsung ke-submit
        event.preventDefault();

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data Anggota ini akan dihapus secara permanen!",
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
                document.getElementById('deleteForm-' + id_anggota).submit();
            }
        });
    }
</script>
@endsection