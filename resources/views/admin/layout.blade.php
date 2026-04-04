<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard KUBE')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
        <style>
    /* Custom Scrollbar untuk Sidebar */
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    </style>
</head>

<body class="bg-gray-50 flex min-h-screen">

    <aside class="w-64 bg-indigo-700 text-white flex flex-col shadow-xl">
        <div class="p-6">
            <div class="flex items-center gap-3">
                <div class="bg-white p-2 rounded-lg">
                    <i data-lucide="layout-dashboard" class="text-indigo-700 w-6 h-6"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-wider">KUBE</h1>
            </div>
        </div>

        @include('admin.sidebar')

        <div class="p-4 border-t border-indigo-600">
            <div class="bg-indigo-800/50 p-4 rounded-2xl mb-4">
                <div class="flex items-center gap-3 mb-4">
                    <div
                        class="w-10 h-10 rounded-full bg-white text-indigo-700 flex items-center justify-center font-bold shadow-sm">
                        {{ strtoupper(substr(auth()->user()->nama, 0, 1)) }}
                    </div>
                    <div class="overflow-hidden text-sm font-semibold truncate">
                        {{ auth()->user()->nama }}
                    </div>
                </div>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 shadow-sm">
            <div class="text-gray-500 font-medium">@yield('breadcrumb')</div>
            <div class="flex items-center gap-4 text-gray-400">
                <i data-lucide="bell" class="w-5 h-5 cursor-pointer hover:text-indigo-600"></i>
                <div class="h-6 w-px bg-gray-200"></div>
                <span class="text-sm text-gray-600 font-medium">{{ now()->format('d M Y') }}</span>
            </div>
        </header>

        <section class="p-8 overflow-y-auto">
            @yield('content')
        </section>
    </main>

    <script>
        // Inisialisasi Ikon Lucide
        lucide.createIcons();

        /**
         * Fungsi Dropdown Dinamis
         * @param {string} menuId - ID dari elemen div menu
         * @param {string} iconId - ID dari elemen ikon chevron
         */
        function toggleDropdown(menuId, iconId) {
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(iconId);

            // Tutup semua dropdown lain (opsional - jika ingin mode akordion)
            // Jika tidak ingin menu lain tertutup otomatis, hapus blok ini
            
            const allMenus = document.querySelectorAll('[id$="Menu"]');
            const allIcons = document.querySelectorAll('[id$="Icon"]');
            allMenus.forEach(m => { if(m.id !== menuId) m.classList.add('hidden'); });
            allIcons.forEach(i => { if(i.id !== iconId) i.style.transform = 'rotate(0deg)'; });
            

            // Toggle menu yang diklik
            menu.classList.toggle('hidden');

            // Animasi Rotasi Ikon
            if (menu.classList.contains('hidden')) {
                icon.style.transform = 'rotate(0deg)';
            } else {
                icon.style.transform = 'rotate(180deg)';
            }
        }
    </script>

    @if (session('success'))
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#4f46e5',
            });
        </script>
    @endif

    @stack('scripts')
</body>

</html>
