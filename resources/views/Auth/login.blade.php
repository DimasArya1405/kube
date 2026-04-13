<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - KUBE</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="flex flex-col md:flex-row w-full max-w-[900px] h-auto md:h-[500px] bg-white rounded-xl overflow-hidden shadow-xl">
            
            <div class="w-full md:w-1/2 bg-slate-100 p-10 flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-green-600 leading-tight">
                    Sistem<br>Kelompok Usaha Bersama
                </h2>
                <div class="mt-8 hidden md:block italic text-gray-400 text-sm">
                    Platform Kolaborasi & Digitalisasi KUBE
                </div>
            </div>

            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                <div class="text-center mb-8">
                    <h3 class="text-2xl font-semibold text-gray-800">Selamat Datang</h3>
                    <p class="text-gray-500 mt-2 text-sm">Silahkan masuk untuk melanjutkan</p>
                </div>

                @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center">
                    <i data-lucide="check-circle" class="h-5 w-5 mr-3"></i>
                    <p class="text-sm font-medium italic">{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-50 text-red-600 p-3 rounded-lg text-sm mb-4 border border-red-100 flex items-center">
                    <i data-lucide="alert-circle" class="h-4 w-4 mr-2"></i>
                    {{ session('error') }}
                </div>
                @endif

                <form method="POST" action="/login" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <i data-lucide="mail" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                            <input type="email" name="email" 
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm" 
                                placeholder="Masukkan email" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative">
                            <i data-lucide="lock" class="absolute left-3 top-3.5 w-4 h-4 text-gray-400"></i>
                            <input type="password" name="password" id="passwordField"
                                class="w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none transition-all text-sm" 
                                placeholder="Masukkan password" required>
                            
                            <button type="button" onclick="togglePassword()" class="absolute right-3 top-3 text-gray-400 hover:text-blue-600 transition-colors">
                                <i data-lucide="eye" id="eyeIcon" class="w-5 h-5"></i>
                                <i data-lucide="eye-off" id="eyeOffIcon" class="w-5 h-5 hidden"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors shadow-lg shadow-blue-200 flex justify-center items-center gap-2 mt-2">
                        <i data-lucide="log-in" class="w-5 h-5"></i>
                        Masuk
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-gray-600">
                    Belum punya akun? 
                    <a href="/register" class="text-blue-600 hover:underline font-medium">Buat Akun</a>
                </div>
            </div>

        </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePassword() {
            const passwordField = document.getElementById('passwordField');
            const eyeIcon = document.getElementById('eyeIcon');
            const eyeOffIcon = document.getElementById('eyeOffIcon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                passwordField.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }
    </script>
</body>
</html>