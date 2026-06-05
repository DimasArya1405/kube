@extends('kepala_dinas.layout')

@section('breadcrumb')
Dashboard / Data Master / <span class="text-gray-800">Detail KUBE</span>
@stop

@section('content')
<div class="p-6">
    <div class="flex items-center mb-6 border-b pb-4">
        <a href="{{ route('kube.index') }}" class="text-gray-600 hover:text-purple-700 transition mr-4 text-2xl">
            <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
        </a>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Detail KUBE {{ $kube->nama_kube }}</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola informasi Kelompok Usaha Bersama, status, dan pembagian pendamping.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4 uppercase tracking-wide">Informasi Dasar KUBE</h3>

            <div class="space-y-3 text-gray-700 font-medium">
                <p>Nama KUBE : <span class="uppercase">{{ $kube->nama_kube }}</span></p>
                <p>Kategori : <span>{{ $kube->clusterUsaha->kategori->nama_kategori ?? '-' }}</span></p>
                <p>Cluster : <span>{{ $kube->clusterUsaha->nama_cluster ?? '-' }}</span></p>

                <p>Kecamatan : <span>{{ $kube->desa->kecamatan->nama_kecamatan ?? '-' }}</span></p>
                <p>Desa/Kelurahan : <span>{{ $kube->desa->nama_desa_kelurahan ?? '-' }}</span></p>
                <p>Status : <span>{{ $kube->status }}</span></p>

                <p>Tanggal Dibentuk : <span>{{ $kube->tanggal_terbentuk ? \Carbon\Carbon::parse($kube->tanggal_terbentuk)->format('d F Y') : '-' }}</span></p>


            </div>
        </div>

        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-2 uppercase tracking-wide">Pengelola KUBE</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-gray-700 font-medium mb-1">Pendamping</p>
                    <div class="flex justify-between items-center bg-gray-300 rounded-full px-6 py-2 shadow-inner">
                        <span class="font-bold text-gray-800 mx-auto">{{ $kube->pembagianPendampingAktif->pendamping->nama_pendamping ?? 'Belum ada Pendamping' }}</span>
                    </div>
                </div>
                <div>
                    <p class="text-gray-700 font-medium mb-1">Koordinator</p>
                    <div class="flex justify-between items-center bg-gray-300 rounded-full px-6 py-2 shadow-inner">
                        <span class="font-bold text-gray-800 mx-auto">{{ $kube->pembagianPendampingAktif->pembagianKoordinator->koordinator->nama_koor ?? 'Belum ada Koordinator' }}</span>
                    </div>
                </div>

            </div>

            <h3 class="text-lg font-bold text-gray-800 mt-5 mb-2 uppercase tracking-wide">Keterangan KUBE</h3>
            <div>
                <div class="bg-gray-50 rounded-lg px-4 py-3 border border-gray-200 shadow-sm">
                    <p class="text-gray-800 text-sm leading-relaxed">
                        {{ $kube->keterangan ?? 'Tidak ada keterangan.' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">Daftar Anggota KUBE</h3>
            <div class="flex gap-2">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-search"></i></span>
                    <input type="text" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 bg-gray-50 text-sm" placeholder="Cari nama Anggota...">
                </div>
                <button onclick="toggleModal('tambahAnggotaModal')" class="px-4 py-2 bg-blue-700 flex items-center text-white text-sm font-medium rounded-lg hover:bg-purple-800 transition shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Tambah Anggota
                </button>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-100 border-b border-gray-200">
                    <tr>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">No.</th>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Nama Anggota</th>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">NIK</th>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Jabatan</th>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">No. HP</th>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Alamat</th>
                        <th class="py-3 px-5 text-gray-700 font-semibold text-sm text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($kube->anggota as $index => $anggota)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                        <td class="py-3 px-5 text-gray-800 text-center">{{ $index + 1 }}.</td>
                        <td class="py-3 px-5 text-gray-800 text-center">{{ $anggota->nama_anggota }}</td>
                        <td class="py-3 px-5 text-gray-800 text-center">{{ $anggota->nik }}</td>
                        <td class="py-3 px-5 text-gray-800 text-center">{{ $anggota->jabatan }}</td>
                        <td class="py-3 px-5 text-gray-800 text-center">{{ $anggota->no_hp }}</td>
                        <td class="py-3 px-5 text-gray-800 text-center">{{ $anggota->alamat }}</td>
                        <td class="py-3 px-5 text-center">
                            <div class="flex justify-center space-x-3">
                                <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="text-gray-400 hover:text-yellow-500 transition text-lg">
                                    <i class="far fa-edit"></i>
                                </button>

                                @if($anggota->nik !== Auth::user()->nik)
                                <form action="{{ route('anggota_kube.destroy', $anggota->id_anggota) }}" method="POST" class="inline" id="deleteAnggotaForm-{{ $anggota->id_anggota }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="text-gray-400 hover:text-red-500 transition text-lg" onclick="confirmDeleteAnggota(event, '{{ $anggota->id_anggota }}')">
                                        <i class="far fa-trash-alt"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-gray-300 cursor-not-allowed text-lg" title="Anda tidak bisa menghapus diri sendiri"><i class="fas fa-ban"></i></span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-gray-500">Belum ada anggota di KUBE ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@foreach($kube->anggota as $anggota)
<div id="editAnggotaModal{{ $anggota->id_anggota }}" class="fixed inset-0 z-50 hidden bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-bold text-gray-800">Ubah Data Anggota</h3>
            <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="text-gray-400 hover:text-gray-600 focus:outline-none"><i class="fas fa-times text-lg"></i></button>
        </div>

        <form action="{{ route('anggota_kube.update', $anggota->id_anggota) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                    <input type="text" name="nik" value="{{ $anggota->nik }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anggota</label>
                    <input type="text" name="nama_anggota" value="{{ $anggota->nama_anggota }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jabatan</label>
                    @if($anggota->jabatan == 'Ketua')
                    <input type="text" value="Ketua (Tidak bisa diubah)" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 font-bold cursor-not-allowed">
                    <input type="hidden" name="jabatan" value="Ketua">
                    @else
                    <select name="jabatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                        <option value="Sekretaris" {{ $anggota->jabatan == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                        <option value="Bendahara" {{ $anggota->jabatan == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                        <option value="Anggota" {{ $anggota->jabatan == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                    </select>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. HP</label>
                    <input type="text" name="no_hp" value="{{ $anggota->no_hp }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" required>{{ $anggota->alamat }}</textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3 bg-gray-50">
                <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-sm font-medium">Batal</button>
                <button type="submit" class="px-4 py-2 bg-purple-700 text-white rounded-lg hover:bg-purple-800 text-sm font-bold">Simpan Perubahan</button>
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
                    <input type="text" value="{{ $kube->nama_kube }}" disabled class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 font-bold cursor-not-allowed">
                    <input type="hidden" name="id_kube" value="{{ $kube->id_kube }}">
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

    function confirmDeleteAnggota(event, id_anggota) {
        event.preventDefault();
        Swal.fire({
            title: 'Keluarkan Anggota?',
            text: "Data anggota ini akan dihapus permanen dari KUBE!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Keluarkan!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteAnggotaForm-' + id_anggota).submit();
            }
        });
    }
</script>

@endsection