<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard KUBE')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/flowbite@latest/dist/flowbite.js"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Custom Scrollbar untuk Sidebar & Content */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .bg-indigo-700 .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .bg-indigo-700 .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body class="bg-gray-50 flex h-screen overflow-hidden">

    <aside class="w-64 flex-shrink-0 bg-indigo-700 text-white flex flex-col shadow-xl h-full">
        <div class="p-6 flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="bg-white p-2 rounded-lg shadow-sm">
                    <i data-lucide="layout-dashboard" class="text-indigo-700 w-6 h-6"></i>
                </div>
                <h1 class="text-2xl font-bold tracking-wider">KUBE</h1>
            </div>
        </div>

        <div class="flex-1 overflow-y-auto custom-scrollbar px-4 space-y-2">
            @include('admin.sidebar')
        </div>

        <div class="p-4 border-t border-indigo-600 flex-shrink-0">
            <div class="bg-indigo-800/50 p-4 rounded-2xl">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-white text-indigo-700 flex items-center justify-center font-bold shadow-sm flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->nama ?? 'A', 0, 1)) }}
                    </div>
                    <div class="overflow-hidden text-sm font-semibold truncate">
                        {{ auth()->user()->nama ?? 'Admin User' }}
                    </div>
                </div>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">
                        <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="flex-1 flex flex-col min-w-0 h-full">

        <header class="h-16 flex-shrink-0 bg-white border-b border-gray-200 flex items-center justify-between px-8 shadow-sm z-10">
            <div class="text-gray-500 font-medium truncate">
                @yield('breadcrumb', 'Dashboard')
            </div>
            <div class="flex items-center gap-4 text-gray-400 flex-shrink-0">
                <i data-lucide="bell" class="w-5 h-5 cursor-pointer hover:text-indigo-600"></i>
                <div class="h-6 w-px bg-gray-200"></div>
                <span class="text-sm text-gray-600 font-medium">{{ now()->format('d M Y') }}</span>
            </div>
        </header>

        <section class="flex-1 overflow-y-auto p-8 custom-scrollbar bg-gray-50">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </section>

    </main>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    @stack('scripts')
    <script>
        // Inisialisasi Ikon Lucide
        lucide.createIcons();

        // Fungsi Dropdown Dinamis
        function toggleDropdown(menuId, iconId) {
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(iconId);

            // Akordion: Tutup semua menu lain kecuali yang diklik
            const allMenus = document.querySelectorAll('[id$="Menu"]');
            const allIcons = document.querySelectorAll('[id$="Icon"]');

            allMenus.forEach(m => {
                if (m.id !== menuId) m.classList.add('hidden');
            });
            allIcons.forEach(i => {
                if (i.id !== iconId) i.style.transform = 'rotate(0deg)';
            });

            // Toggle menu target
            const isHidden = menu.classList.toggle('hidden');
            icon.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
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


</body>

</html>