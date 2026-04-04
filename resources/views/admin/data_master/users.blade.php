@extends('admin.layout')

@section('title', 'Data User - KUBE')

@section('breadcrumb')
    Dashboard / <span class="text-gray-800">Data User</span>
@stop

@section('content')

<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Manajemen User</h2>
        <p class="text-gray-500 mt-1">
            Pantau dan kelola seluruh akun pengguna KUBE.
        </p>
    </div>

    <button 
        data-modal-target="modal-tambah-user"
        data-modal-toggle="modal-tambah-user"
        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition"
    >
        Tambah User Baru
    </button>
</div>

<div class="bg-white mb-6 rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">

            <!-- HEADER -->
            <thead class="bg-gray-100 text-xs uppercase">
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

            <!-- BODY -->
            <tbody>
                @forelse($users as $index => $user)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $index + 1 }}</td>

                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $user->nama }}
                        </td>

                        <td class="px-6 py-4">{{ $user->email }}</td>
                        <td class="px-6 py-4">{{ $user->no_hp }}</td>
                        <td class="px-6 py-4">{{ $user->alamat }}</td>

                        <td class="px-6 py-4">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </td>

                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $user->status == 'aktif' 
                                    ? 'bg-green-100 text-green-700' 
                                    : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <button class="text-blue-600 hover:underline">Edit</button>
                            <button class="text-red-600 hover:underline ml-2">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-6 text-gray-500">
                            Belum ada data user
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

<!-- MODAL TAMBAH USER -->
<div 
    id="modal-tambah-user" 
    tabindex="-1"
    class="hidden fixed top-0 left-0 right-0 z-50 w-full h-full flex justify-center items-center bg-black/50"
>
    <div class="bg-white rounded-lg shadow w-full max-w-md">

        <!-- HEADER -->
        <div class="flex justify-between items-center p-4 border-b">
            <h3 class="text-lg font-semibold">Tambah User</h3>
            <button 
                data-modal-toggle="modal-tambah-user" 
                class="text-gray-400 hover:text-gray-700"
            >
            </button>
        </div>

        <!-- FORM -->
        <form method="POST" action="{{ route('admin.users.store') }}" class="p-4">
            @csrf
            <div class="grid grid-cols-2 gap-3">
                <!-- Nama -->
                <input 
                    type="text" 
                    name="nama" 
                    placeholder="Nama Lengkap"
                    class="col-span-2 border rounded p-2" 
                    required
                >

                <!-- NIK -->
                <input 
                    type="text" 
                    name="nik" 
                    placeholder="NIK"
                    class="col-span-2 border rounded p-2"
                >

                <!-- Email -->
                <input 
                    type="email" 
                    name="email" 
                    placeholder="Email"
                    class="col-span-2 border rounded p-2" 
                    required
                >

                <!-- Password -->
                <input 
                    type="password" 
                    name="password" 
                    placeholder="Password"
                    class="col-span-2 border rounded p-2" 
                    required
                >

                <!-- No HP -->
                <input 
                    type="text" 
                    name="no_hp" 
                    placeholder="No HP"
                    class="col-span-2 border rounded p-2"
                >

                <!-- Alamat -->
                <textarea 
                    name="alamat" 
                    placeholder="Alamat"
                    class="col-span-2 border rounded p-2"
                ></textarea>

                <!-- Kecamatan -->
                <select name="id_kecamatan" class="border rounded p-2 col-span-1">
                    <option value="">Pilih Kecamatan</option>
                    @foreach($kecamatan as $kec)
                        <option value="{{ $kec->id_kecamatan }}">
                            {{ $kec->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>

                <!-- Desa -->
                <select name="id_desa_kelurahan" class="border rounded p-2 col-span-1">
                    <option value="">Pilih Desa/Kelurahan</option>
                    @foreach($desa as $d)
                        <option value="{{ $d->id_desa_kelurahan }}">
                            {{ $d->nama_desa_kelurahan }}
                        </option>
                    @endforeach
                </select>

                <!-- Role -->
                <select name="role" class="col-span-2 border rounded p-2">
                    <option value="">Pilih Role</option>
                    <option value="admin">Admin</option>
                    <option value="ketua_kube">Ketua KUBE</option>
                    <option value="pendamping">Pendamping</option>
                    <option value="koordinator">Koordinator</option>
                    <option value="ketua_tim_kube">Ketua Tim Kube</option>
                    <option value="kepala_dinas">Kepala Dinas</option>
                </select>

                <!-- Status -->
                <select name="status" class="col-span-2 border rounded p-2">
                    <option value="aktif">Aktif</option>
                    <option value="nonaktif">Nonaktif</option>
                </select>

            </div>

            <button 
                type="submit"
                class="w-full mt-4 bg-blue-600 text-white py-2 rounded hover:bg-blue-700"
            >
                Simpan
            </button>
        </form>
    </div>
</div>

@endsection