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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            scroll-behavior: smooth;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: #e0f2fe;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #0891b2;
            border-radius: 10px;
        }

        .hover-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 25px -5px rgba(6, 182, 212, 0.15), 0 10px 10px -5px rgba(6, 182, 212, 0.05);
        }
        
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        /* Menyembunyikan scrollbar di IE, Edge, dan Firefox */
        .no-scrollbar {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
    </style>
</head>

<body class="bg-sky-200 flex flex-col min-h-screen overflow-y-auto custom-scrollbar">

    <nav class="bg-white/80 backdrop-blur-md border-b border-cyan-200/50 px-6 md:px-12 py-5 flex justify-between items-center sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-4">
            <img src="{{ asset('img/logo-dinsos.png') }}" alt="Logo Dinas Sosial" class="h-12 md:h-14 w-auto drop-shadow-sm">
            <div>
                <h1 class="font-black text-slate-900 leading-none text-base md:text-lg tracking-tight">DINAS SOSIAL</h1>
                <p class="text-xs text-cyan-700 font-extrabold uppercase tracking-widest mt-1">Kabupaten Cilacap</p>
            </div>
        </div>
        
        <a href="{{ route('login') }}" class="group bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-3 rounded-full font-bold transition-all shadow-lg shadow-cyan-600/30 flex items-center gap-2 text-sm md:text-base">
            <span>Masuk Sistem</span>
            <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </nav>

    <main class="p-6 md:p-12 max-w-7xl mx-auto space-y-12 md:space-y-16 w-full flex-grow">
        
        <section class="text-center space-y-8 py-4">
            <div class="max-w-3xl mx-auto space-y-4">
                <span class="px-4 py-1.5 bg-cyan-600/10 text-cyan-800 border border-cyan-200 rounded-full text-xs font-bold uppercase tracking-widest">Sistem Informasi KUBE</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-950 tracking-tight leading-tight">
                    Selamat Datang di Portal Pemberdayaan Ekonomi Masyarakat
                </h2>
                <p class="text-slate-600 text-base md:text-xl leading-relaxed">
                    Silahkan pilih hak akses login Anda di bawah ini untuk masuk ke dalam dashboard management Kelompok Usaha Bersama.
                </p>
            </div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl mx-auto pt-4">
    <!-- Kartu Ketua KUBE -> Mengarah ke Register Ketua -->
    <a href="{{ route('register.ketua') }}" class="bg-white border-2 border-cyan-100 p-6 rounded-2xl shadow-sm text-left hover-card flex flex-col justify-between group border-b-4 border-b-emerald-500">
        <div>
            <div class="w-12 h-12 bg-emerald-100 text-emerald-700 rounded-xl flex items-center justify-center mb-5 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                <i data-lucide="users" class="w-6 h-6"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Ketua KUBE</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Akses khusus Ketua Kelompok untuk mengelola data anggota dan laporan keuangan internal KUBE.</p>
        </div>
        <div class="mt-6 flex items-center gap-2 font-bold text-emerald-600 text-sm group-hover:gap-4 transition-all">
            <span>Daftar Ketua KUBE</span>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </div>
    </a>

    <!-- Kartu Pendamping KUBE -> Mengarah ke Register Pendamping -->
    <a href="{{ route('register.pendamping') }}" class="bg-white border-2 border-cyan-100 p-6 rounded-2xl shadow-sm text-left hover-card flex flex-col justify-between group border-b-4 border-b-cyan-500">
        <div>
            <div class="w-12 h-12 bg-cyan-100 text-cyan-700 rounded-xl flex items-center justify-center mb-5 group-hover:bg-cyan-500 group-hover:text-white transition-colors">
                <i data-lucide="graduation-cap" class="w-6 h-6"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Pendamping KUBE</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Akses untuk tim pendamping lapangan guna melakukan monitoring, bimbingan, and laporan kunjungan.</p>
        </div>
        <div class="mt-6 flex items-center gap-2 font-bold text-cyan-600 text-sm group-hover:gap-4 transition-all">
            <span>Daftar Pendamping</span>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </div>
    </a>

    <!-- Kartu Koordinator -> Mengarah ke Register Koordinator -->
    <a href="{{ route('register.koordinator') }}" class="bg-white border-2 border-cyan-100 p-6 rounded-2xl shadow-sm text-left hover-card flex flex-col justify-between group border-b-4 border-b-amber-500">
        <div>
            <div class="w-12 h-12 bg-amber-100 text-amber-700 rounded-xl flex items-center justify-center mb-5 group-hover:bg-amber-500 group-hover:text-white transition-colors">
                <i data-lucide="shield-check" class="w-6 h-6"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Koordinator</h3>
            <p class="text-sm text-slate-500 leading-relaxed">Akses tingkat wilayah untuk memantau kinerja seluruh pendamping dan distribusi program bantuan.</p>
        </div>
        <div class="mt-6 flex items-center gap-2 font-bold text-amber-600 text-sm group-hover:gap-4 transition-all">
            <span>Daftar Koordinator</span>
            <i data-lucide="chevron-right" class="w-4 h-4"></i>
        </div>
    </a>
</div>
        </section>

        <section class="bg-white border border-cyan-200/60 p-6 md:p-10 shadow-sm rounded-3xl">
            <div class="flex flex-col items-center mb-8 text-center relative">
                <span class="px-4 py-1 bg-cyan-100 text-cyan-700 rounded-full text-xs font-bold uppercase tracking-widest mb-2">Dokumentasi</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 uppercase tracking-tight">Galeri Kegiatan</h2>
                <div class="h-1.5 w-16 bg-cyan-600 rounded-full mt-3"></div>

                <div class="flex gap-2 mt-4 md:absolute md:right-0 md:bottom-0">
                    <button onclick="scrollCarousel('left')" class="p-2.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-full shadow-sm hover:bg-cyan-600 hover:text-white hover:border-cyan-600 transition duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button onclick="scrollCarousel('right')" class="p-2.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-full shadow-sm hover:bg-cyan-600 hover:text-white hover:border-cyan-600 transition duration-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div id="galeriCarousel" class="flex gap-6 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 no-scrollbar select-none">
                @foreach ([
                    ['kegiatan1.png', 'Pelatihan Kewirausahaan', 'Pendampingan pembuatan produk lokal.'],
                    ['kegiatan2.png', 'Penyaluran Modal', 'Bantuan modal usaha produktif kelompok.'],
                    ['kegiatan3.png', 'Rapat Koordinasi', 'Evaluasi rutin perkembangan usaha KUBE.'],
                    ['kegiatan4.png', 'Bazar Produk KUBE', 'Pemasaran produk hasil usaha kelompok.'],
                    ['kegiatan5.png', 'Monitoring Wilayah', 'Kunjungan lapangan ke lokasi usaha.'],
                    ['kegiatan6.png', 'Pelatihan Digital', 'Edukasi pemasaran produk secara online.']
                ] as $galeri)
                <div class="group relative overflow-hidden rounded-2xl aspect-video bg-slate-200 hover-card w-[85%] sm:w-[calc(50%-12px)] lg:w-[calc(33.333%-16px)] shrink-0 snap-start">
                    <img src="{{ asset('img/galeri/' . $galeri[0]) }}" 
                        alt="{{ $galeri[1] }}" 
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                        draggable="false">
                    
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-5">
                        <h4 class="text-white font-bold text-base">{{ $galeri[1] }}</h4>
                        <p class="text-slate-300 text-xs mt-1">{{ $galeri[2] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>

        <section class="bg-white rounded-3xl border border-cyan-200/60 p-6 md:p-12 shadow-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-cyan-100/50 rounded-full blur-3xl"></div>
            
            <div class="flex flex-col items-center justify-center gap-4 mb-10 relative">
                <div class="p-4 bg-cyan-50 rounded-2xl shadow-inner border border-cyan-100">
                    <img src="{{ asset('img/logo-dinsos.png') }}" alt="Logo Dinas Sosial" class="h-16 w-auto drop-shadow-md">
                </div>
                <h2 class="text-2xl md:text-4xl font-black text-slate-900 text-center uppercase tracking-tight">Visi & Misi</h2>
                <div class="h-1.5 w-20 bg-cyan-600 rounded-full"></div>
            </div>
            
            <div class="grid lg:grid-cols-2 gap-8 md:gap-12 relative z-10">
                <div class="bg-gradient-to-br from-slate-50 to-cyan-50 rounded-3xl p-8 border-2 border-cyan-200/60 shadow-sm flex flex-col justify-center relative overflow-hidden hover-card">
                    <div class="absolute top-0 left-0 w-2.5 h-full bg-cyan-600"></div>
                    <i data-lucide="quote" class="absolute top-6 right-6 w-12 h-12 text-cyan-200/40"></i>
                    <h3 class="text-cyan-700 font-extrabold uppercase tracking-widest text-xs mb-4">Visi Utama</h3>
                    <p class="italic font-bold text-slate-800 leading-relaxed text-xl md:text-2xl text-center">
                        "Cilacap... Maju, Mandiri, Sejahtera dan Berdaya Saing Ditopang Pemerintahan Yang Bersih Dan Terbuka"
                    </p>
                </div>

                <div class="bg-slate-50 rounded-3xl p-6 md:p-8 border border-slate-200 shadow-inner">
                    <h3 class="text-slate-900 font-black uppercase tracking-widest text-sm mb-6 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 bg-cyan-600 rounded-full"></span> Misi Strategis
                    </h3>
                    <ul class="space-y-4">
                        @foreach ([
                            'Meningkatkan peran serta masyarakat melalui sumber kesejahteraan sosial (PSKS) yang mandiri.',
                            'Meningkatkan kesejahteraan sosial masyarakat melalui Pemberdayaan perlindungan, jaminan dan rehabilitasi sosial.',
                            'Meningkatkan kualitas hidup perempuan di bidang ekonomi, politik serta meningkatkan pemahaman tentang Pengarusutamaan gender kepada semua pemangku kepentingan.',
                            'Terlaporya kasus kekerasan, ekploitasi terhadap perempuan dan anak melalui peningkatan perlindungan hak-hak perempuan dan anak.'
                        ] as $index => $misi)
                        <li class="flex gap-4 p-4 bg-white rounded-xl border border-cyan-100 hover-card shadow-sm">
                            <span class="shrink-0 w-9 h-9 bg-cyan-600 text-white rounded-lg flex items-center justify-center font-black text-base shadow-md shadow-cyan-600/20">{{ $index + 1 }}</span>
                            <p class="text-sm md:text-base text-slate-700 font-medium leading-relaxed my-auto">{{ $misi }}</p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>

        <div class="grid md:grid-cols-2 gap-8 md:gap-12">
            <div class="bg-white rounded-3xl border border-cyan-200/60 p-8 shadow-sm flex flex-col items-center hover-card">
                <span class="px-4 py-1 bg-cyan-100 text-cyan-700 rounded-full text-xs font-bold uppercase tracking-widest mb-4">Informasi Program</span>
                <h2 class="text-2xl font-black text-slate-900 mb-2 uppercase text-center tracking-tight">Mengenal KUBE</h2>
                <p class="text-xs md:text-sm text-slate-400 mb-8 font-medium italic text-center">Program Pemberdayaan Ekonomi Masyarakat Melalui Kube</p>
                
                <div class="relative mb-8">
                    <div class="absolute inset-0 bg-cyan-200 blur-3xl opacity-30 rounded-full"></div>
                    <img src="{{ asset('img/logo-dinsos.png') }}" alt="Logo KUBE" class="h-28 relative drop-shadow-md">
                </div>

                <h3 class="text-xl font-extrabold text-slate-900 mb-3">Apa itu KUBE?</h3>
                <p class="text-slate-600 text-center text-sm md:text-base font-medium leading-relaxed mb-8 px-2 md:px-6">
                    <span class="text-cyan-600 font-bold">Kelompok Usaha Bersama (KUBE)</span> adalah wadah bagi masyarakat (kelompok keluarga miskin) untuk belajar berwirausaha secara berkelompok, untuk meningkatkan pendapatan dan kemandirian ekonomi melalui bimbingan sosial serta bantuan modal usaha.
                </p>

                <div class="grid grid-cols-2 gap-4 w-full mt-auto">
                    @foreach ([
                        ['users', 'Berbasis masyarakat untuk meningkatkan ekonomi keluarga'],
                        ['trending-up', 'Mendorong kemandirian ekonomi melalui usaha bersama'],
                        ['award', 'Program Jangka Panjang untuk kesejahteraan'],
                        ['target', 'Fokus pada peningkatan taraf hidup masyarakat']
                    ] as $item)
                    <div class="p-4 bg-slate-50 rounded-2xl text-center border border-slate-200 group hover:bg-cyan-50/50 hover:border-cyan-300 transition-all shadow-sm">
                        <i data-lucide="{{ $item[0] }}" class="w-6 h-6 text-cyan-600 mx-auto mb-2 group-hover:scale-110 transition-transform"></i>
                        <p class="text-xs font-bold text-slate-700 tracking-tight leading-snug">{{ $item[1] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-cyan-200/60 p-8 shadow-sm hover-card">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-black text-slate-900 mb-2 uppercase tracking-tight">Tujuan KUBE</h2>
                    <p class="text-xs md:text-sm text-slate-400 font-medium italic">Pemberdayaan Masyarakat Berkelanjutan</p>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach ([
                        ['banknote', 'Meningkatkan Pendapatan', 'Memberikan akses bagi keluarga miskin untuk meningkatkan pendapatan melalui usaha produktif'],
                        ['briefcase', 'Kemandirian Usaha', 'Mengembangkan jiwa kewirausahaan dan kemampuan berusaha secara mandiri'],
                        ['graduation-cap', 'Pelatihan & Pendampingan', 'Memberikan pelatihan keterampilan dan pendampingan berkelanjutan untuk pengembangan usaha.'],
                        ['badge-check', 'Kesejahteraan Sosial', 'Meningkatkan taraf kesejahteraan sosial ekonomi keluarga secara berkelanjutan'],
                        ['baggage-claim', 'Jaringan Kemitraan', 'Memfasilitasi akses ke pasar, modal dan jaringan usaha yang lebih luas'],
                        ['handshake', 'Solidaritas Kelompok', 'Membangun kerjasama dan solidaritas antar anggota untuk saling mendukung']
                    ] as $tujuan)
                    <div class="p-4 border border-slate-200 bg-slate-50 rounded-2xl hover:bg-cyan-50/50 hover:border-cyan-300 hover:shadow-md transition-all group">
                        <div class="w-10 h-10 bg-white border border-slate-200 rounded-xl shadow-sm flex items-center justify-center mb-4 group-hover:bg-cyan-600 transition-colors">
                            <i data-lucide="{{ $tujuan[0] }}" class="w-5 h-5 text-cyan-600 group-hover:text-white transition-colors"></i>
                        </div>
                        <h4 class="font-extrabold text-slate-900 text-sm md:text-base mb-1">{{ $tujuan[1] }}</h4>
                        <p class="text-xs text-slate-500 leading-snug font-medium">{{ $tujuan[2] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-white/60 backdrop-blur-sm border-t border-cyan-200/60 py-8 mt-auto shadow-inner">
        <div class="max-w-7xl mx-auto px-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-slate-600 text-sm font-bold">© 2026 SMATEC Politeknik Negeri Cilacap. Seluruh Hak Cipta Dilindungi.</p>
            <div class="flex gap-6 text-lg">
                <a href="#" class="text-slate-500 hover:text-cyan-600 transition-colors"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-slate-500 hover:text-cyan-600 transition-colors"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-slate-500 hover:text-cyan-600 transition-colors"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();
        function scrollCarousel(direction) {
            const carousel = document.getElementById('galeriCarousel');
            const cardWidth = carousel.querySelector('.group').offsetWidth + 24; // 24 adalah nilai gap-6
            
            if (direction === 'left') {
                carousel.scrollLeft -= cardWidth;
            } else {
                carousel.scrollLeft += cardWidth;
            }
        }
    </script>
</body>

</html>