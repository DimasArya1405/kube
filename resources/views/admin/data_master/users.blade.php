@extends('admin.layout')

@section('title', 'Data User - KUBE')

@section('breadcrumb')
    Dashboard / <span class="text-gray-800">Data User</span>
@stop

@section('content')

<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data User</h2>
        <p class="text-gray-500 mt-1">Kelola seluruh akun pengguna KUBE.</p>
    </div>

    <button 
        data-modal-target="modal-tambah-user"
        data-modal-toggle="modal-tambah-user"
        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">
        Tambah User Baru
    </button>
</div>

<div class="bg-white mb-6 rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-xs uppercase text-gray-700">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">No HP</th>
                    <th class="px-6 py-3">Alamat</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $index => $user)
                    <tr class="border-b hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">{{ $users->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $user->nama }}</td>
                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->no_hp }}</td>
                        <td class="px-6 py-4">{{ $user->alamat }}</td>
                        <td class="px-6 py-4">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium 
                                {{ $user->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="detailUser('{{ $user->id_user }}')" 
                                    class="w-9 h-9 flex items-center justify-center rounded-lg text-blue-500 hover:bg-blue-50 transition-colors"
                                    title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </button>

                                <button onclick="editUser('{{ $user->id_user }}')" 
                                    class="w-9 h-9 flex items-center justify-center rounded-lg text-yellow-500 hover:bg-yellow-50 transition-colors"
                                    title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>

                                <form action="{{ route('admin.users.delete', $user->id_user) }}" 
                                    method="POST" 
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')" 
                                    class="inline-block m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors" title="Hapus">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-10 text-gray-500 italic">Belum ada data user</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>

<div id="modal-tambah-user" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm transition-opacity p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden transform transition-all flex flex-col max-h-[90vh]">
        
        <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-b flex-shrink-0">
            <h3 class="text-xl font-bold text-gray-800">Tambah User Baru</h3>
            <button data-modal-toggle="modal-tambah-user" class="text-gray-400 hover:text-red-500 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="flex flex-col overflow-hidden">
            @csrf
            
            <div class="p-6 overflow-y-auto space-y-5 custom-scrollbar flex-grow">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" placeholder="Budi Santoso" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none border" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">NIK</label>
                        <input type="text" name="nik" placeholder="16 digit NIK" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none border">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">No. HP</label>
                        <input type="text" name="no_hp" placeholder="0812..." class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none border">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label>
                        <input type="email" name="email" placeholder="email@domain.com" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none border" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Password</label>
                        <input type="password" name="password" placeholder="••••••••" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none border" required>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kecamatan</label>
                            <select name="id_kecamatan" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none border bg-white">
                                <option value="">Pilih Kecamatan</option>
                                @foreach ($kecamatan as $kec)
                                    <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Desa/Kelurahan</label>
                            <select name="id_desa_kelurahan" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none border bg-white">
                                <option value="">Pilih Desa</option>
                                @foreach ($desa as $d)
                                    <option value="{{ $d->id_desa_kelurahan }}">{{ $d->nama_desa_kelurahan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" placeholder="Jl. Merdeka No. 123..." class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none border resize-none"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Role Pengguna</label>
                        <select name="role" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 outline-none border bg-white">
                            <option value="admin">Admin</option>
                            <option value="ketua_kube">Ketua KUBE</option>
                            <option value="pendamping">Pendamping</option>
                            <option value="koordinator">Koordinator</option>
                            <option value="ketua_tim_kube">Ketua Tim Kube</option>
                            <option value="kepala_dinas">Kepala Dinas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status Akun</label>
                        <div class="flex items-center h-[46px] gap-2 px-3 bg-green-50 border border-green-100 rounded-lg">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            <span class="text-sm font-bold text-green-700 uppercase tracking-wider">Aktif</span>
                        </div>
                        <input type="hidden" name="status" value="aktif">
                    </div>
                </div>
            </div>

            <div class="p-6 bg-gray-50 border-t flex gap-3 flex-shrink-0">
                <button type="button" data-modal-toggle="modal-tambah-user" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-100 transition-all font-semibold">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 shadow-lg shadow-blue-200 transition-all font-semibold">Simpan User</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-edit-user" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
        <div class="flex justify-between items-center px-6 py-4 bg-amber-50 border-b border-amber-100">
            <div class="flex items-center gap-2">
                <div class="p-1.5 bg-amber-500 rounded-md text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-amber-900">Edit Pengguna</h3>
            </div>
            <button onclick="closeEditModal()" class="text-amber-400 hover:text-amber-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <form id="form-edit" method="POST" class="p-6">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" id="edit_nama" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-amber-500 outline-none border transition-all">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">NIK</label>
                        <input type="text" name="nik" id="edit_nik" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-amber-500 outline-none border transition-all">
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">No. HP</label>
                        <input type="text" name="no_hp" id="edit_no_hp" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-amber-500 outline-none border transition-all">
                    </div>
                </div>

                <div class="col-span-2">
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Email</label>
                    <input type="email" name="email" id="edit_email" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-amber-500 outline-none border transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase mb-1">Alamat Lengkap</label>
                    <textarea name="alamat" id="edit_alamat" rows="2" class="w-full border-gray-300 rounded-lg p-2.5 focus:ring-2 focus:ring-amber-500 outline-none border transition-all"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <select name="id_kecamatan" id="edit_kecamatan" class="border rounded-lg p-2.5 outline-none transition-all focus:ring-2 focus:ring-amber-500 bg-white">
                        @foreach ($kecamatan as $kec)
                            <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                        @endforeach
                    </select>
                    <select name="id_desa_kelurahan" id="edit_desa" class="border rounded-lg p-2.5 outline-none transition-all focus:ring-2 focus:ring-amber-500 bg-white">
                        @foreach ($desa as $d)
                            <option value="{{ $d->id_desa_kelurahan }}">{{ $d->nama_desa_kelurahan }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4 pt-2">
                    <select name="role" id="edit_role" class="border rounded-lg p-2.5 outline-none transition-all focus:ring-2 focus:ring-amber-500 bg-white">
                        <option value="admin">Admin</option>
                        <option value="ketua_kube">Ketua KUBE</option>
                        <option value="pendamping">Pendamping</option>
                        <option value="koordinator">Koordinator</option>
                        <option value="ketua_tim_kube">Ketua Tim Kube</option>
                        <option value="kepala_dinas">Kepala Dinas</option>
                    </select>
                    <select name="status" id="edit_status" class="border rounded-lg p-2.5 outline-none transition-all focus:ring-2 focus:ring-amber-500 bg-white">
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex gap-3">
                <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition-all font-medium">Batal</button>
                <button type="submit" class="flex-1 px-4 py-2.5 bg-amber-500 text-white rounded-lg hover:bg-amber-600 shadow-lg shadow-amber-200 transition-all font-medium">Update Data</button>
            </div>
        </form>
    </div>
</div>

<div id="modal-detail-user" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden transform transition-all">
        <div class="relative h-24 bg-gradient-to-r from-blue-600 to-indigo-700">
            <button onclick="closeDetailModal()" class="absolute top-4 right-4 text-white/80 hover:text-white transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="px-6 pb-6">
            <div class="relative -mt-12 mb-4">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full border-4 border-white shadow-lg text-blue-600 text-3xl font-bold uppercase" id="detail_initial">
                    U
                </div>
            </div>

            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-800" id="detail_nama">Nama User</h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize" id="detail_role">Role</span>
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <div class="text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">NIK</p>
                        <p class="text-sm font-semibold text-gray-700" id="detail_nik">-</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Email</p>
                        <p class="text-sm font-semibold text-gray-700 truncate" id="detail_email">-</p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">No. HP</p>
                        <p class="text-sm font-semibold text-gray-700" id="detail_no_hp">-</p>
                    </div>
                </div>

                <div class="p-3 bg-gray-50 rounded-xl">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Wilayah</p>
                    <p class="text-sm font-semibold text-gray-700">
                        <span id="detail_desa">Desa</span>, <span id="detail_kecamatan">Kecamatan</span>
                    </p>
                </div>

                <div class="p-3 bg-gray-50 rounded-xl">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Alamat Lengkap</p>
                    <p class="text-sm font-semibold text-gray-700 italic" id="detail_alamat">-</p>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-between px-1">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-3 w-3">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" id="detail_status_ping"></span>
                        <span class="relative inline-flex rounded-full h-3 w-3" id="detail_status_dot"></span>
                    </span>
                    <span class="text-sm font-bold text-gray-600 uppercase tracking-widest" id="detail_status">Aktif</span>
                </div>
                <button onclick="closeDetailModal()" class="text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    const modalEdit = document.getElementById('modal-edit-user');
    const modalDetail = document.getElementById('modal-detail-user');

    // -- EDIT USER --
    function editUser(id) {
        fetch('/admin/users/edit/' + id)
            .then(res => res.json())
            .then(data => {
                document.getElementById('edit_nama').value = data.nama;
                document.getElementById('edit_nik').value = data.nik;
                document.getElementById('edit_email').value = data.email;
                document.getElementById('edit_no_hp').value = data.no_hp;
                document.getElementById('edit_alamat').value = data.alamat;
                document.getElementById('edit_role').value = data.role;
                document.getElementById('edit_status').value = data.status;
                document.getElementById('edit_kecamatan').value = data.id_kecamatan;
                document.getElementById('edit_desa').value = data.id_desa_kelurahan;
                document.getElementById('form-edit').action = '/admin/users/update/' + id;
                modalEdit.classList.remove('hidden');
            });
    }

    function closeEditModal() {
        modalEdit.classList.add('hidden');
    }

    // -- DETAIL USER --
    function detailUser(id) {
        fetch('/admin/users/edit/' + id)
            .then(res => res.json())
            .then(data => {
                // Populate Text
                document.getElementById('detail_nama').innerText = data.nama || '-';
                document.getElementById('detail_email').innerText = data.email || '-';
                document.getElementById('detail_nik').innerText = data.nik || '-';
                document.getElementById('detail_no_hp').innerText = data.no_hp || '-';
                document.getElementById('detail_alamat').innerText = data.alamat || '-';
                
                // 
                const namaKecamatan = data.kecamatan?.nama_kecamatan || '-';
                const namaDesa = data.desa?.nama_desa_kelurahan || '-';

                // Populate Relations
                document.getElementById('detail_kecamatan').innerText = "Kecamatan " + namaKecamatan;
                document.getElementById('detail_desa').innerText = "Desa/Kelurahan " + namaDesa;
                
                // Format Role
                const roleElement = document.getElementById('detail_role');
                roleElement.innerText = data.role.replace('_', ' ');

                // Avatar Initial
                const initial = data.nama ? data.nama.charAt(0).toUpperCase() : 'U';
                document.getElementById('detail_initial').innerText = initial;

                // Status Styling
                const statusPing = document.getElementById('detail_status_ping');
                const statusDot = document.getElementById('detail_status_dot');
                const statusText = document.getElementById('detail_status');

                statusText.innerText = data.status;

                if(data.status.toLowerCase() === 'aktif') {
                    statusText.className = "text-sm font-bold text-green-600 uppercase tracking-widest";
                    statusPing.className = "animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75";
                    statusDot.className = "relative inline-flex rounded-full h-3 w-3 bg-green-500";
                } else {
                    statusText.className = "text-sm font-bold text-red-600 uppercase tracking-widest";
                    statusPing.className = "";
                    statusDot.className = "relative inline-flex rounded-full h-3 w-3 bg-red-500";
                }

                modalDetail.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengambil data user.');
            });
    }

    function closeDetailModal() {
        modalDetail.classList.add('hidden');
    }

    // -- GLOBAL EVENT --
    window.onclick = function(event) {
        if (event.target == modalEdit) closeEditModal();
        if (event.target == modalDetail) closeDetailModal();
    }
</script>

@endsection