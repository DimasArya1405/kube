@extends('ketua_kube.layout') @section('content')
    <div class="p-6 max-w-3xl mx-auto">
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800">Pencairan Bantuan</h2>
            <p class="text-gray-500 mt-2">
                Halaman ini menampilkan informasi pencairan bantuan yang telah disetujui untuk KUBE Anda.
                Setelah dana bantuan diterima, Ketua KUBE dapat melakukan konfirmasi penerimaan sehingga
                status pencairan berubah menjadi <span class="font-medium">Cair</span>.
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-md p-8 border border-gray-100">
            @forelse($pencairanBantuan as $pencairan)
                <div class="border rounded-lg p-4 mb-4">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="font-semibold text-lg">
                            {{ $pencairan->pengajuan_kube->jenisBantuan->jenis_bantuan ?? '-' }}
                        </h3>

                        <span
    class="px-3 py-1 rounded-full text-sm font-medium
    @if($pencairan->status_pencairan == 'cair')
        bg-green-100 text-green-700
    @elseif($pencairan->status_pencairan == 'disetujui')
        bg-blue-100 text-blue-700
    @elseif($pencairan->status_pencairan == 'menunggu')
        bg-yellow-100 text-yellow-700
    @elseif($pencairan->status_pencairan == 'ditolak')
        bg-red-100 text-red-700
    @endif">
    
    {{ ucfirst($pencairan->status_pencairan) }}

</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Tahap</p>
                            <p class="font-medium">{{ $pencairan->tahap }}</p>
                        </div>

                        <div>
                            <p class="text-gray-500">Nilai Bantuan</p>
                            <p class="font-medium">
                                Rp {{ number_format($pencairan->pengajuan_kube->jumlah_bantuan, 0, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Tanggal Pengajuan</p>
                            <p class="font-medium">
                                {{ \Carbon\Carbon::parse($pencairan->tanggal_pengajuan)->format('d M Y') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500">Tanggal Cair</p>
                            <p class="font-medium">
                                {{ $pencairan->tanggal_cair ? \Carbon\Carbon::parse($pencairan->tanggal_cair)->format('d M Y') : '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500 text-sm">Tujuan Pengajuan</p>
                            <p>
                                {{ $pencairan->pengajuan_kube->tujuan_pengajuan }}
                            </p>
                        </div>
                    </div>

                    <hr class="my-3">
                    <div class="flex justify-end mt-4">
                        @if ($pencairan->status_pencairan == 'disetujui')
                            <form action="{{ route('ketua_kube.pencairan_bantuan.konfirmasi', $pencairan->id_pencairan) }}"
                                method="POST" id="form-konfirmasi-{{ $pencairan->id_pencairan }}">
                                @csrf
                                @method('get')

                                <button type="submit" onclick="konfirmasiPencairan({{ $pencairan->id_pencairan }})"
                                    class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                                    <i class="fas fa-check-circle mr-2"></i>
                                    Konfirmasi Dana Sudah Diterima
                                </button>
                            </form>
                        @elseif ($pencairan->status_pencairan == 'cair')
                            <span class="px-4 py-2 bg-green-100 text-green-700 rounded-lg font-medium">
                                <i class="fas fa-check-circle mr-2"></i>
                                Dana Telah Dikonfirmasi
                            </span>
                        @elseif ($pencairan->status_pencairan == 'ditolak')
                            <span class="px-4 py-2 bg-red-100 text-red-700 rounded-lg font-medium"></span>
                                <i class="fas fa-times-circle mr-2"></i>
                                Dana Telah Ditolak
                            </span>
                        @elseif ($pencairan->status_pencairan == 'menunggu')
                            <span class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-lg font-medium">
                                <i class="fas fa-clock mr-2"></i>
                                Menunggu Konfirmasi
                            </span>
                        @endif
                    </div>


                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    Belum ada data pencairan bantuan.
                </div>
            @endforelse
        </div>
    </div>
@endsection
