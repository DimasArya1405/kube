@extends('admin.layout')

@section('title', 'Data User - KUBE')

@section('breadcrumb')
Dashboard / <span class="text-gray-800">Data User</span>
@stop

@section('content')

<!-- HEADER -->
<div class="mb-8 flex justify-between items-end">
    <div>
        <h2 class="text-3xl font-bold text-gray-800">Data User</h2>
        <p class="text-gray-500 mt-1">
            Kelola seluruh akun pengguna KUBE.
        </p>
    </div>

    <button
        data-modal-target="modal-tambah-user"
        data-modal-toggle="modal-tambah-user"
        class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition">
        Tambah User Baru
    </button>
</div>

<!-- TABLE -->
<div class="bg-white mb-6 rounded-lg shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-600">

            <!-- HEADER TABLE -->
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

            <!-- BODY TABLE -->
            <tbody>
                @forelse ($users as $index => $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4">
                        {{ $users->firstItem() + $index }}
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        {{ $user->nama }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $user->email }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $user->no_hp }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $user->alamat }}
                    </td>

                    <td class="px-6 py-4">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </td>

                    <td class="px-6 py-4">
                        <span
                            class="px-3 py-1 rounded-full text-xs font-medium
                                {{ $user->status == 'aktif'
                                    ? 'bg-green-100 text-green-700'
                                    : 'bg-red-100 text-red-700' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-3">

                            <!-- DETAIL -->
                            <button
                                onclick="detailUser('{{ $user->id_user }}')"
                                class="text-blue-500 hover:text-blue-700 transition flex items-center justify-center"
                                title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 flex-shrink-0"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5
                                               c4.478 0 8.268 2.943 9.542 7
                                               -1.274 4.057-5.064 7-9.542 7
                                               -4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>

                            <!-- EDIT -->
                            <button
                                onclick="editUser('{{ $user->id_user }}')"
                                class="text-yellow-500 hover:text-yellow-700 transition flex items-center justify-center"
                                title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-4 w-4 flex-shrink-0"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5
                                               m-1.414-9.414a2 2 0 112.828 2.828
                                               L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </button>

                            <!-- DELETE -->
                            <form
                                action="{{ route('admin.users.delete', $user->id_user) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus data ini?')"
                                class="flex items-center">
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-red-500 hover:text-red-700 transition flex items-center justify-center"
                                    title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-4 w-4 flex-shrink-0"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7
                                                   m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
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

        <!-- PAGINATION -->
        <div class="px-6 py-4 border-t bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-3">
            <div>
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
</div>

@endsection