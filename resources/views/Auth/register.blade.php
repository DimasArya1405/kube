<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - KUBE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="flex flex-col md:flex-row w-full max-w-[1000px] bg-white rounded-xl overflow-hidden shadow-xl">
            
            <div class="w-full md:w-5/12 bg-slate-100 p-10 flex flex-col justify-center border-b md:border-b-0 border-gray-200">
                <h2 class="text-3xl font-bold text-green-600 leading-tight">
                    Sistem<br>Kelompok Usaha Bersama
                </h2>
                <div class="mt-8 hidden md:block italic text-gray-400 text-sm">
                    Platform Kolaborasi & Digitalisasi KUBE
                </div>
            </div>

            <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-800">Daftar Akun</h3>
                    <p class="text-gray-500 mt-1 text-sm">Lengkapi data pendaftaran Anda</p>
                </div>

                @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4 border border-red-100">
                    <ul class="list-disc ml-4">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form method="POST" action="/register" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        
                        <div class="relative">
                            <i data-lucide="user" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                            <input type="text" name="nama" id="inputNama" placeholder="Nama Lengkap" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all">
                        </div>
                        
                        <div class="relative">
                            <i data-lucide="credit-card" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                            <input type="text" name="nik" placeholder="NIK" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all">
                        </div>
                        
                        <div class="relative">
                            <i data-lucide="mail" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                            <input type="email" name="email" placeholder="Email" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all">
                        </div>
                        
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                            <input type="password" name="password" id="inputPassword" placeholder="Password" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all">
                        </div>
                        
                        <div class="relative md:col-span-2">
                            <i data-lucide="phone" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                            <input type="text" name="no_hp" placeholder="Nomor WhatsApp" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm transition-all">
                        </div>

                        <div class="relative md:col-span-2">
                            <i data-lucide="map-pin" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                            <textarea name="alamat" placeholder="Alamat Domisili" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm resize-none h-20 transition-all"></textarea>
                        </div>

                        <div class="relative">
                            <i data-lucide="map" class="absolute left-3 top-3 w-4 h-4 text-gray-400 z-10"></i>
                            <select name="id_kecamatan" required class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white text-sm text-gray-600 appearance-none relative z-0">
                                <option value="">Kecamatan</option>
                                @foreach($kecamatan as $kec)
                                    <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative">
                            <i data-lucide="home" class="absolute left-3 top-3 w-4 h-4 text-gray-400 z-10"></i>
                            <select name="id_desa_kelurahan" required class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white text-sm text-gray-600 appearance-none">
                                <option value="">Desa/Kelurahan</option>
                                @foreach($desa as $d)
                                    <option value="{{ $d->id_desa_kelurahan }}">{{ $d->nama_desa_kelurahan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative md:col-span-2">
                            <i data-lucide="users" class="absolute left-3 top-3 w-4 h-4 text-gray-400 z-10"></i>
                            <select name="role" required class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none bg-white text-sm text-gray-600 appearance-none">
                                <option value="">Pilih Role User</option>
                                <option value="ketua_kube">Ketua KUBE</option>
                                <option value="pendamping">Pendamping</option>
                                <option value="koordinator">Koordinator</option>
                                <option value="ketua_tim_kube">Ketua Tim KUBE</option>
                                <option value="kepala_dinas">Kepala Dinas</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors shadow-lg shadow-blue-200 mt-2 flex justify-center items-center gap-2">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                        Daftar Akun
                    </button>
                </form>

                <div class="mt-4 text-center text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="/login" class="text-blue-600 hover:underline font-medium">Masuk</a>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Inisialisasi ikon Lucide
        lucide.createIcons();

        const inputNama = document.getElementById('inputNama');
        const inputPassword = document.getElementById('inputPassword');
        inputNama.addEventListener('input', function() {
            inputPassword.value = this.value;
        });
    </script>
</body>
</html>