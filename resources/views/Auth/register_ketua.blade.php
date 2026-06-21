<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Ketua KUBE - KUBE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex items-center justify-center p-4 relative">
        <a href="/" class="absolute top-6 left-6 flex items-center gap-2 text-slate-600 hover:text-blue-600 font-medium text-sm bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200 transition-all hover:-translate-x-1">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            <span>Kembali ke Beranda</span>
        </a>
        <div class="flex flex-col md:flex-row w-full max-w-[1000px] bg-white rounded-xl overflow-hidden shadow-xl">
            <div class="relative w-full md:w-5/12 bg-gradient-to-br from-slate-100 to-slate-200 p-10 flex flex-col justify-center overflow-hidden border-b md:border-b-0 border-gray-200">
                <div class="absolute -top-10 -left-10 w-40 h-40 bg-emerald-200/30 rounded-full blur-3xl"></div>
                <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-blue-200/30 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl font-extrabold text-slate-800 leading-tight">
                        Daftar <span class="text-emerald-600">Ketua KUBE</span>
                    </h2>
                    <p class="text-xs text-slate-400 mt-1 font-medium">
                        Lengkapi data akun untuk mulai mengelola kelompok usaha Anda.
                    </p>
                    <div class="w-12 h-1 bg-emerald-500 rounded-full mt-4 mb-6"></div>

                    <div class="grid grid-cols-2 gap-4 max-w-sm">
                        <div class="flex flex-col gap-2 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-slate-200/50 shadow-sm">
                            <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="user-check" class="w-4 h-4 text-emerald-600"></i>
                            </div>
                            <div><p class="text-xs font-bold text-slate-700">Validasi NIK</p></div>
                        </div>
                        <div class="flex flex-col gap-2 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-slate-200/50 shadow-sm">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="shield-check" class="w-4 h-4 text-blue-600"></i>
                            </div>
                            <div><p class="text-xs font-bold text-slate-700">Verifikasi Akun</p></div>
                        </div>
                        <div class="flex flex-col gap-2 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-slate-200/50 shadow-sm">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="users" class="w-4 h-4 text-purple-600"></i>
                            </div>
                            <div><p class="text-xs font-bold text-slate-700">Akses Ketua</p></div>
                        </div>
                        <div class="flex flex-col gap-2 p-3 bg-white/60 backdrop-blur-sm rounded-xl border border-slate-200/50 shadow-sm">
                            <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="clock" class="w-4 h-4 text-amber-600"></i>
                            </div>
                            <div><p class="text-xs font-bold text-slate-700">Proses Validasi</p></div>
                        </div>
                    </div>
                </div>
                <div class="absolute bottom-6 left-10 flex items-center gap-2 text-slate-400">
                    <i data-lucide="map-pin" class="w-3 h-3"></i>
                    <span class="text-[10px] font-medium tracking-wide text-slate-400 uppercase">Dinsos Cilacap</span>
                </div>
            </div>
            <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center">
                <div class="text-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-800">Daftar Akun Ketua KUBE</h3>
                    <p class="text-gray-500 mt-1 text-sm">Lengkapi data pendaftaran internal kelompok Anda</p>
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
                <form method="POST" action="{{ route('register.ketua.store') }}" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="relative">
                            <i data-lucide="user" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                            <input type="text" name="nama" id="inputNama" placeholder="Nama Lengkap" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                        </div>
                        <div class="relative">
                            <i data-lucide="credit-card" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                            <input type="text" name="nik" placeholder="NIK" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                        </div>
                        
                        <div class="relative">
                            <i data-lucide="mail" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                            <input type="email" name="email" placeholder="Email" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                        </div>
                        
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <input type="password" name="password" id="inputPassword" placeholder="Password" required
                                class="w-full pl-10 pr-12 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                            <button type="button" onclick="togglePassword()" 
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-600 transition-colors flex items-center justify-center">
                                <i data-lucide="eye-off" id="eyeOffIcon" class="w-5 h-5"></i>
                                <i data-lucide="eye" id="eyeIcon" class="w-5 h-5 hidden"></i>
                            </button>
                        </div>
                        
                        <div class="relative md:col-span-2">
                            <i data-lucide="phone" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                            <input type="text" name="no_hp" placeholder="Nomor Handphone" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                        </div>

                        <div class="relative md:col-span-2">
                            <i data-lucide="map-pin" class="absolute left-3 top-3 w-4 h-4 text-gray-400"></i>
                            <textarea name="alamat" placeholder="Alamat Domisili" required
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none text-sm h-20 resize-none"></textarea>
                        </div>

                        <div class="relative">
                            <i data-lucide="map" class="absolute left-3 top-3 w-4 h-4 text-gray-400 z-10"></i>
                            <select name="id_kecamatan" id="selectKecamatan" required 
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none bg-white text-sm text-gray-600 appearance-none">
                                <option value="">Pilih Kecamatan</option>
                                @foreach($kecamatan as $kec)
                                    <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative">
                            <i data-lucide="home" class="absolute left-3 top-3 w-4 h-4 text-gray-400 z-10"></i>
                            <select name="id_desa_kelurahan" id="selectDesa" required disabled
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none bg-gray-50 text-sm text-gray-600 appearance-none disabled:bg-gray-100 disabled:cursor-not-allowed">
                                <option value="">Pilih Desa/Kelurahan</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-medium py-3 rounded-lg transition-colors shadow-lg shadow-emerald-200 mt-2 flex justify-center items-center gap-2">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                        Daftar Sebagai Ketua KUBE
                    </button>
                </form>

                <div class="mt-4 text-center text-sm text-gray-600">
                    Sudah punya akun? 
                    <a href="/login" class="text-emerald-600 hover:underline font-medium">Masuk</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        const inputNama = document.getElementById('inputNama');
        const inputPassword = document.getElementById('inputPassword');
        inputNama.addEventListener('input', function() {
            inputPassword.value = this.value;
        });

        const selectKecamatan = document.getElementById('selectKecamatan');
        const selectDesa = document.getElementById('selectDesa');

        selectKecamatan.addEventListener('change', function() {
            const idKecamatan = this.value;
            selectDesa.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
            
            if (idKecamatan) {
                selectDesa.disabled = false;
                selectDesa.classList.remove('bg-gray-100');
                selectDesa.classList.add('bg-white');
                selectDesa.innerHTML = '<option value="">Memuat...</option>';

                fetch(`/get-desa/${idKecamatan}`)
                    .then(response => response.json())
                    .then(data => {
                        selectDesa.innerHTML = '<option value="">Pilih Desa/Kelurahan</option>';
                        data.forEach(desa => {
                            const option = document.createElement('option');
                            option.value = desa.id_desa_kelurahan;
                            option.textContent = desa.nama_desa_kelurahan;
                            selectDesa.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Gagal mengambil data desa');
                    });
            } else {
                selectDesa.disabled = true;
                selectDesa.classList.add('bg-gray-100');
            }
        });

        function togglePassword() {
            const passwordField = document.getElementById('inputPassword'); 
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }
    </script>
</body>
</html>