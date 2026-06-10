@extends('admin.layout')

@section('breadcrumb')
Dashboard / Data Master / <span class="text-gray-800 font-semibold">Detail KUBE</span>
@stop

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    {{-- HEADER --}}
    <div class="flex items-center mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <a href="{{ route('kube.index') }}" class="text-gray-400 hover:text-purple-700 transition mr-4 p-2 rounded-full hover:bg-purple-50">
            <i class="fa fa-arrow-left text-xl" aria-hidden="true"></i>
        </a>
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">KUBE <span class="text-purple-700 uppercase">{{ $kube->nama_kube }}</span></h2>
            <p class="text-gray-500 text-sm mt-1">Detail informasi Kelompok Usaha Bersama dan daftar anggotanya.</p>
        </div>
    </div>

    {{-- LAYOUT GRID: KIRI (INFO) & KANAN (ANGGOTA) --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        {{-- KOLOM KIRI: INFO KUBE (1 Kolom) --}}
        <div class="xl:col-span-1 space-y-6">
            
            {{-- Card Info Dasar --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-400 mb-4 uppercase tracking-wider flex items-center">
                    <i class="fas fa-info-circle mr-2"></i> Informasi Dasar
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-gray-500 text-sm">Nama KUBE</span>
                        <span class="font-semibold text-gray-800 uppercase text-right">{{ $kube->nama_kube }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-gray-500 text-sm">Kategori</span>
                        <span class="font-medium text-gray-800 text-right">{{ $kube->clusterUsaha->kategori->nama_kategori ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-gray-500 text-sm">Cluster</span>
                        <span class="font-medium text-gray-800 text-right">{{ $kube->clusterUsaha->nama_cluster ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-gray-500 text-sm">Kecamatan</span>
                        <span class="font-medium text-gray-800 text-right">{{ $kube->desa->kecamatan->nama_kecamatan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-gray-500 text-sm">Desa/Kelurahan</span>
                        <span class="font-medium text-gray-800 text-right">{{ $kube->desa->nama_desa_kelurahan ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-50 pb-2">
                        <span class="text-gray-500 text-sm">Status</span>
                        @if(strtolower($kube->status) == 'aktif')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">{{ $kube->status }}</span>
                        @else
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">{{ $kube->status }}</span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500 text-sm">Tgl Terbentuk</span>
                        <span class="font-medium text-gray-800 text-right">{{ $kube->tanggal_terbentuk ? \Carbon\Carbon::parse($kube->tanggal_terbentuk)->format('d M Y') : '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Card Pengelola --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-400 mb-4 uppercase tracking-wider flex items-center">
                    <i class="fas fa-users-cog mr-2"></i> Pengelola KUBE
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center p-3 bg-purple-50 rounded-lg border border-purple-100">
                        <div class="bg-purple-200 text-purple-700 w-10 h-10 rounded-full flex items-center justify-center mr-3 shrink-0">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Pendamping</p>
                            <p class="text-sm font-bold text-gray-800">{{ $kube->pembagianPendampingAktif->pendamping->nama_pendamping ?? 'Belum ada Pendamping' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-3 bg-blue-50 rounded-lg border border-blue-100">
                        <div class="bg-blue-200 text-blue-700 w-10 h-10 rounded-full flex items-center justify-center mr-3 shrink-0">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 font-medium">Koordinator</p>
                            <p class="text-sm font-bold text-gray-800">{{ $kube->pembagianPendampingAktif->pembagianKoordinator->koordinator->nama_koor ?? 'Belum ada Koordinator' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card Keterangan --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-sm font-bold text-gray-400 mb-3 uppercase tracking-wider flex items-center">
                    <i class="fas fa-clipboard-list mr-2"></i> Keterangan
                </h3>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 text-sm text-gray-600 leading-relaxed italic">
                    {{ $kube->keterangan ?? 'Tidak ada keterangan tambahan untuk KUBE ini.' }}
                </div>
            </div>

        </div>

        {{-- KOLOM KANAN: DAFTAR ANGGOTA (2 Kolom di layar besar) --}}
        <div class="xl:col-span-2">
            <div class="bg-white shadow-sm rounded-xl border border-gray-100 h-full flex flex-col">
                {{-- Table Header & Actions --}}
                <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Daftar Anggota</h3>
                        <p class="text-sm text-gray-500">Total: {{ $kube->anggota->count() }} Anggota</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                        <div class="relative w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-search"></i></span>
                            <input type="text" class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none text-sm bg-gray-50 transition" placeholder="Cari anggota...">
                        </div>
                        <button onclick="toggleModal('tambahAnggotaModal')" class="px-4 py-2 bg-purple-700 text-white text-sm font-bold rounded-lg hover:bg-purple-800 transition shadow-sm flex items-center justify-center shrink-0">
                            <i class="fas fa-plus mr-2"></i> Tambah Anggota
                        </button>
                    </div>
                </div>

                {{-- Table Body --}}
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 border-b border-gray-200 text-xs uppercase text-gray-500 font-semibold">
                            <tr>
                                <th class="py-4 px-5 text-center w-12">No</th>
                                <th class="py-4 px-5">Nama Anggota & NIK</th>
                                <th class="py-4 px-5">Jabatan</th>
                                <th class="py-4 px-5">Kontak & Alamat</th>
                                <th class="py-4 px-5 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            @forelse($kube->anggota as $index => $anggota)
                            <tr class="hover:bg-purple-50/50 transition">
                                <td class="py-3 px-5 text-gray-800 text-center font-medium">{{ $index + 1 }}</td>
                                <td class="py-3 px-5">
                                    <div class="font-bold text-gray-800">{{ $anggota->nama_anggota }}</div>
                                    <div class="text-xs text-gray-500 mt-0.5">{{ $anggota->nik }}</div>
                                </td>
                                <td class="py-3 px-5">
                                    @if($anggota->jabatan == 'Ketua')
                                        <span class="bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full text-xs font-bold">{{ $anggota->jabatan }}</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">{{ $anggota->jabatan }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-5">
                                    <div class="text-gray-800"><i class="fas fa-phone-alt text-gray-400 mr-1 text-xs"></i> {{ $anggota->no_hp }}</div>
                                    <div class="text-xs text-gray-500 mt-1 truncate max-w-[200px]" title="{{ $anggota->alamat }}">{{ $anggota->alamat }}</div>
                                </td>
                                <td class="py-3 px-5 text-center">
                                    <div class="flex justify-center space-x-2">
                                        <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="w-8 h-8 rounded bg-yellow-100 text-yellow-600 hover:bg-yellow-200 transition flex items-center justify-center" title="Edit Data">
                                            <i class="far fa-edit"></i>
                                        </button>

                                        @if($anggota->nik !== Auth::user()->nik)
                                        <form action="{{ route('anggota_kube.destroy', $anggota->id_anggota) }}" method="POST" class="inline" id="deleteAnggotaForm-{{ $anggota->id_anggota }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="w-8 h-8 rounded bg-red-100 text-red-600 hover:bg-red-200 transition flex items-center justify-center" onclick="confirmDeleteAnggota(event, '{{ $anggota->id_anggota }}')" title="Keluarkan Anggota">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                        @else
                                        <div class="w-8 h-8 rounded bg-gray-100 text-gray-400 flex items-center justify-center cursor-not-allowed" title="Anda tidak bisa menghapus diri sendiri">
                                            <i class="fas fa-ban"></i>
                                        </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400">
                                        <i class="fas fa-users-slash text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-base font-medium text-gray-500">Belum ada anggota di KUBE ini.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT ANGGOTA --}}
@foreach($kube->anggota as $anggota)
<div id="editAnggotaModal{{ $anggota->id_anggota }}" class="fixed inset-0 z-[99] hidden bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-extrabold text-gray-800">Ubah Data Anggota</h3>
            <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="text-gray-400 hover:text-red-500 transition focus:outline-none"><i class="fas fa-times text-xl"></i></button>
        </div>

        <form action="{{ route('anggota_kube.update', $anggota->id_anggota) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">NIK</label>
                    <input type="text" name="nik" value="{{ $anggota->nik }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Anggota</label>
                    <input type="text" name="nama_anggota" value="{{ $anggota->nama_anggota }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jabatan</label>
                    @if($anggota->jabatan == 'Ketua')
                    <input type="text" value="Ketua (Tidak bisa diubah manual)" disabled class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-500 font-bold cursor-not-allowed">
                    <input type="hidden" name="jabatan" value="Ketua">
                    @else
                    <select name="jabatan" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" required>
                        <option value="Sekretaris" {{ $anggota->jabatan == 'Sekretaris' ? 'selected' : '' }}>Sekretaris</option>
                        <option value="Bendahara" {{ $anggota->jabatan == 'Bendahara' ? 'selected' : '' }}>Bendahara</option>
                        <option value="Anggota" {{ $anggota->jabatan == 'Anggota' ? 'selected' : '' }}>Anggota</option>
                    </select>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">No. HP</label>
                    <input type="text" name="no_hp" value="{{ $anggota->no_hp }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition resize-none" required>{{ $anggota->alamat }}</textarea>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                <button type="button" onclick="toggleModal('editAnggotaModal{{ $anggota->id_anggota }}')" class="px-5 py-2.5 bg-white border border-gray-300 rounded-xl hover:bg-gray-100 text-sm font-bold text-gray-700 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-purple-700 text-white rounded-xl hover:bg-purple-800 text-sm font-bold shadow-md transition">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- MODAL TAMBAH ANGGOTA --}}
<div id="tambahAnggotaModal" class="fixed inset-0 z-[99] hidden bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full flex items-center justify-center transition-all duration-300">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-all">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-extrabold text-gray-800">Tambah Data Anggota</h3>
            <button type="button" onclick="toggleModal('tambahAnggotaModal')" class="text-gray-400 hover:text-red-500 transition focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form action="{{ route('anggota_kube.store') }}" method="POST">
            @csrf
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Asal KUBE</label>
                    <input type="text" value="{{ $kube->nama_kube }}" disabled class="w-full px-4 py-2.5 border border-gray-200 rounded-lg bg-gray-50 text-gray-500 font-bold cursor-not-allowed">
                    <input type="hidden" name="id_kube" value="{{ $kube->id_kube }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">NIK</label>
                    <input type="text" name="nik" maxlength="16" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Anggota</label>
                    <input type="text" name="nama_anggota" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Jabatan</label>
                    <select name="jabatan" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" required>
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="Sekretaris">Sekretaris</option>
                        <option value="Bendahara">Bendahara</option>
                        <option value="Anggota">Anggota</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">No. HP</label>
                    <input type="text" name="no_hp" maxlength="15" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition" required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 outline-none transition resize-none" required></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
                <button type="button" onclick="toggleModal('tambahAnggotaModal')" class="px-5 py-2.5 bg-white border border-gray-300 rounded-xl hover:bg-gray-100 text-sm font-bold text-gray-700 transition">Batal</button>
                <button type="submit" class="px-5 py-2.5 bg-purple-700 text-white rounded-xl hover:bg-purple-800 text-sm font-bold shadow-md transition">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

<script>
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