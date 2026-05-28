<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GarasiBMW</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            /* Background gelap untuk menonjolkan tampilan mobile */
            background-color: #374151; 
        }
    </style>
</head>
<body class="flex justify-center min-h-screen">

    @php
        // Simulasi status login untuk melihat perubahan UI.
        // Ubah menjadi true untuk melihat tampilan pesan error "Email atau password salah".
        $login_salah = false; 
    @endphp

    <div class="w-full max-w-md bg-gradient-to-b from-[#c6deff] via-[#eaf2ff] to-[#ffffff] min-h-screen shadow-2xl relative flex flex-col p-8">
        
        <div class="mt-10 flex flex-col items-center">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-sm border-4 border-white mb-6">
                <div class="text-[#5b86e5] font-black text-4xl italic flex items-center gap-1">
                    <i class="fa-solid fa-gear text-3xl"></i>
                </div>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-800 tracking-wide">Selamat Datang</h1>
            <p class="text-xs text-gray-500 mt-2 font-medium">Silahkan masuk untuk konfirmasi kehadiran</p>
        </div>

        <form action="#" method="POST" class="mt-10 flex flex-col gap-5 flex-1">
            <div>
                <label for="email" class="block text-[11px] font-bold text-gray-700 mb-1.5 ml-1">Email</label>
                <div class="relative">
                    <i class="fa-regular fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-blue-500 text-sm"></i>
                    <input type="email" id="email" name="email" placeholder="edselgacor@gmail.com" 
                        class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-blue-300 bg-transparent focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm transition-all shadow-sm placeholder-gray-400">
                </div>
            </div>

            <div>
                <label for="password" class="block text-[11px] font-bold text-gray-700 mb-1.5 ml-1">Password</label>
                <div class="relative">
                    <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-blue-500 text-sm"></i>
                    <input type="password" id="password" name="password" placeholder="••••••••" 
                        class="w-full pl-11 pr-12 py-3.5 rounded-xl border border-blue-300 bg-transparent focus:bg-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm transition-all shadow-sm placeholder-gray-400">
                    
                    <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                        <i id="eye-icon" class="fa-regular fa-eye"></i>
                    </button>
                </div>
            </div>

            @if($login_salah)
                <p class="text-[#e65c5c] text-[11px] text-center font-medium mt-1">Email atau password salah</p>
            @endif

            <div class="flex items-center gap-2 mt-2 ml-1">
                <input type="checkbox" id="remember" name="remember" 
                    class="w-4 h-4 text-blue-500 border-blue-300 rounded focus:ring-blue-500 bg-transparent cursor-pointer">
                <label for="remember" class="text-xs text-gray-600 font-medium cursor-pointer">Ingat Saya</label>
            </div>

            <div class="mt-4">
                <button type="submit" 
                    class="w-full bg-[#567af3] hover:bg-[#4565d6] text-white rounded-xl py-3.5 font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-blue-500/30 transition-all active:scale-[0.98]">
                    Masuk
                    <i class="fa-solid fa-arrow-right-to-bracket"></i>
                </button>
            </div>
        </form>

    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </