<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'KUBE - Dinas Sosial')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/flowbite@latest/dist/flowbite.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4f46e5;
            border-radius: 10px;
        }

        /* Animasi halus untuk hover kartu */
        .hover-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body class="bg-[#f8fafc] flex flex-col min-h-screen overflow-y-auto custom-scrollbar">

    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 px-4 md:px-8 py-4 flex justify-between items-center sticky top-0 z-50">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/logo-dinsos.png') }}" alt="Logo Dinas Sosial" class="h-10 md:h-12 w-auto">
            <div class="hidden sm:block">
                <h1 class="font-extrabold text-gray-900 leading-none text-sm md:text-base">DINAS SOSIAL</h1>
                <p class="text-[10px] md:text-xs text-indigo-600 font-bold uppercase tracking-widest">Kabupaten Cilacap</p>
            </div>
        </div>
        
        <a href="{{ route('login') }}" class="group bg-indigo-600 hover:bg-indigo-700 text-white px-4 md:px-6 py-2 md:py-2.5 rounded-full font-semibold transition-all shadow-md shadow-indigo-200 flex items-center gap-2 text-sm">
            <span>Masuk Sistem</span>
            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </nav>

    <main class="p-4 md:p-8 max-w-7xl mx-auto space-y-6 md:space-y-10">
        
        <section class="bg-white rounded-[2rem] border border-gray-100 p-6 md:p-12 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl"></div>
            
            <div class="flex flex-col items-center justify-center gap-4 mb-10 relative">
                <div class="p-3 bg-indigo-50 rounded-2xl">
                    <img src="{{ asset('img/logo-dinsos.png') }}" alt="Logo Dinas Sosial" class="h-16 w-auto drop-shadow-md">
                </div>
                <h2 class="text-2xl md:text-4xl font-black text-gray-900 text-center uppercase tracking-tight">Visi & Misi</h2>
                <div class="h-1.5 w-20 bg-indigo-600 rounded-full"></div>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-6 md:gap-10">
                <div class="bg-white rounded-3xl p-8 border-2 border-indigo-50 shadow-sm flex flex-col justify-center relative overflow-hidden hover-card">
                    <div class="absolute top-0 left-0 w-2 h-full bg-indigo-600"></div>
                    <i data-lucide="quote" class="absolute top-4 right-4 w-10 h-10 text-indigo-50"></i>
                    <h3 class="text-indigo-600 font-bold uppercase tracking-widest text-[10px] mb-4">Visi Utama</h3>
                    <p class="italic font-semibold text-gray-800 leading-relaxed text-lg md:text-2xl text-center">
                        "Cilacap Maju, Mandiri, Sejahtera dan Berdaya Saling Ditopang Pemerintahan Yang Bersih Dan Terbuka"
                    </p>
                </div>

                <div class="bg-gray-50 rounded-3xl p-6 md:p-8 border border-gray-100 shadow-inner">
                    <h3 class="text-gray-800 font-extrabold uppercase tracking-widest text-sm mb-6 flex items-center gap-2">
                        Misi Strategis
                    </h3>
                    <ul class="space-y-4">
                        @foreach ([
                            'Meningkatkan peran serta masyarakat melalui sumber kesejahteraan sosial (PSKS) yang mandiri.',
                            'Meningkatkan kesejahteraan sosial masyarakat melalui Pemberdayaan perlindungan, jaminan dan rehabilitasi sosial.',
                            'Meningkatkan kualitas hidup perempuan di bidang ekonomi, politik serta meningkatkan pemahaman tentang Pengarusutamaan gender kepada semua pemangku kepentingan.',
                            'Terlaporya kasus kekerasan, ekploitasi terhadap perempuan dan anak melalui peningkatan perlindungan hak-hak perempuan dan anak.'
                        ] as $index => $misi)
                        <li class="flex gap-4 p-3 bg-white rounded-xl border border-gray-50 hover-card shadow-sm">
                            <span class="flex-shrink-0 w-8 h-8 bg-indigo-100 text-indigo-700 rounded-lg flex items-center justify-center font-bold text-sm">{{ $index + 1 }}</span>
                            <p class="text-sm text-gray-600 leading-relaxed">{{ $misi }}</p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        <div class="grid md:grid-cols-2 gap-6 md:gap-8">
            <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm flex flex-col items-center hover-card">
                <span class="px-4 py-1 bg-indigo-100 text-indigo-700 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4">Informasi Program</span>
                <h2 class="text-xl md:text-2xl font-black text-gray-900 mb-2 uppercase text-center">Mengenal KUBE</h2>
                <p class="text-xs text-gray-400 mb-8 font-medium italic">Program Pemberdayaan Ekonomi Masyarakat Melalui Kube</p>
                
                <div class="relative mb-8">
                    <div class="absolute inset-0 bg-indigo-200 blur-2xl opacity-20 rounded-full"></div>
                    <img src="{{ asset('img/logo-dinsos.png') }}" alt="Logo KUBE" class="h-24 relative drop-shadow-sm">
                </div>

                <h3 class="text-lg font-bold text-gray-800 mb-3">Apa itu KUBE?</h3>
                <p class="text-gray-500 text-center text-sm leading-relaxed mb-8 px-2 md:px-6">
                    <span class="text-indigo-600 font-semibold">Kelompok Usaha Bersama (KUBE)</span> adalah wadah bagi masyarakat (kelompok keluarga miskin) untuk belajar berwirausaha secara berkelompok, untuk meningkatkan pendapatan dan keamdirian ekonomi melalui bimbingan sosial serta bantuan modal usaha.
                </p>

                <div class="grid grid-cols-2 gap-3 w-full">
                    @foreach ([
                        ['users', 'Berbasis masyarakat untuk meningkatkan ekonomi keluarga'],
                        ['trending-up', 'Mendorong kemandirian ekonomi melalui usaha bersama'],
                        ['award', 'Program Jangka Panjang untuk kesejahteraan'],
                        ['target', 'Fokus pada peningkatan taraf hidup masyarakat']
                    ] as $item)
                    <div class="p-4 bg-gray-50 rounded-2xl text-center border border-gray-100 group hover:bg-white hover:border-indigo-100 transition-all">
                        <i data-lucide="{{ $item[0] }}" class="w-5 h-5 text-indigo-500 mx-auto mb-2 group-hover:scale-110 transition-transform"></i>
                        <p class="text-[10px] font-bold text-gray-700  tracking-tight">{{ $item[1] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm hover-card">
                <div class="text-center mb-8">
                    <h2 class="text-xl md:text-2xl font-black text-gray-900 mb-2 uppercase">Tujuan KUBE</h2>
                    <p class="text-xs text-gray-400 font-medium italic">Pemberdayaan Masyarakat Berkelanjutan</p>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach ([
                        ['banknote', 'Meningkatkan Pendapatan', 'Memberikan akses bagi keluarga miskin untuk meningkatkan pendapatan melalui usaha produktif'],
                        ['briefcase', 'Kemandirian Usaha', 'Mengembangkan jiwa kewirausahaan dan kemampuan berusaha secara mandiri'],
                        ['graduation-cap', 'Pelatihan & Pendampingan', 'Memberikan pelatihan keterampilan dan pendampingan berkelanjutan untuk pengembangan usaha.'],
                        ['badge-check', 'Kesejahteraan Sosial', 'Meningkatkan taraf kesejahteraan sosial ekonomi keluarga secara berkelanjutan'],
                        ['baggage-claim', 'Jaringan Kemitraan', 'Memfasilitasi akses ke pasar, modal dan jaringan usahan yang lebih luas'],
                        ['handshake', 'Solidaritas Kelompok', 'Membangun kerjasama dan solidaritas antar anggota untuk saling mendukung']
                    ] as $tujuan)
                    <div class="p-5 border border-gray-50 bg-gray-50/50 rounded-2xl hover:bg-white hover:border-indigo-100 hover:shadow-md transition-all group">
                        <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center mb-4 group-hover:bg-indigo-600 transition-colors">
                            <i data-lucide="{{ $tujuan[0] }}" class="w-5 h-5 text-indigo-600 group-hover:text-white transition-colors"></i>
                        </div>
                        <h4 class="font-bold text-gray-800 text-sm mb-1">{{ $tujuan[1] }}</h4>
                        <p class="text-[11px] text-gray-500 leading-snug">{{ $tujuan[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <section class="bg-white rounded-[2rem] border border-gray-100 p-8 shadow-sm">
            <div class="flex flex-col items-center mb-8">
                <span class="px-4 py-1 bg-indigo-100 text-indigo-700 rounded-full text-[10px] font-bold uppercase tracking-widest mb-2">Dokumentasi</span>
                <h2 class="text-2xl md:text-3xl font-black text-gray-900 uppercase">Galeri Kegiatan</h2>
                <div class="h-1 w-12 bg-indigo-600 rounded-full mt-2"></div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ([
                    ['kegiatan1.png', 'Pelatihan Kewirausahaan', 'Pendampingan pembuatan produk lokal.'],
                    ['kegiatan2.png', 'Penyaluran Modal', 'Bantuan modal usaha produktif kelompok.'],
                    ['kegiatan3.png', 'Rapat Koordinasi', 'Evaluasi rutin perkembangan usaha KUBE.'],
                    ['kegiatan4.png', 'Bazar Produk KUBE', 'Pemasaran produk hasil usaha kelompok.'],
                    ['kegiatan5.png', 'Monitoring Wilayah', 'Kunjungan lapangan ke lokasi usaha.'],
                    ['kegiatan6.png', 'Pelatihan Digital', 'Edukasi pemasaran produk secara online.']
                ] as $galeri)
                <div class="group relative overflow-hidden rounded-2xl aspect-video bg-gray-100 hover-card">
                    <img src="{{ asset('img/galeri/' . $galeri[0]) }}" 
                        alt="{{ $galeri[1] }}" 
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-5">
                        <h4 class="text-white font-bold text-sm">{{ $galeri[1] }}</h4>
                        <p class="text-gray-300 text-[10px]">{{ $galeri[2] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-gray-100 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-400 text-xs font-medium">© 2026 SMATEC Politeknik Negeri Cilacap. Seluruh Hak Cipta Dilindungi.</p>
            <div class="flex gap-6">
                <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-gray-400 hover:text-indigo-600 transition-colors"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>