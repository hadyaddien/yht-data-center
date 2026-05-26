<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - YHT Data Center</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-yht-tab.png') }}?v=20260526b">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('images/logo-yht-tab.png') }}?v=20260526b">
    <link rel="shortcut icon" href="{{ asset('images/logo-yht-tab.png') }}?v=20260526b">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-yht-tab.png') }}?v=20260526b">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="h-full bg-white flex items-center justify-center p-4 md:p-8">

    <div class="w-full max-w-md">

        <div class="text-center mb-7">
            <div
                class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-4 overflow-hidden bg-white border-4 border-[#162040]/15 shadow-sm">
                <img src="{{ asset('images/logo-yht.png') }}" alt="Logo Yayasan Hang Tuah"
                    class="w-full h-full object-cover"
                    onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                <svg class="w-9 h-9 text-[#162040] hidden" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm7.93 5H13v-1a1 1 0 0 0-2 0v1H4.07A1 1 0 0 0 3 12a9 9 0 0 0 8 8.94V22a1 1 0 0 0 2 0v-1.06A9 9 0 0 0 21 12a1 1 0 0 0-1.07-1zM12 19a7 7 0 0 1-6.92-6h2.04a5 5 0 0 0 9.76 0h2.04A7 7 0 0 1 12 19z" />
                </svg>
            </div>
            <h1 class="text-[#162040] text-[29px] font-extrabold tracking-tight leading-tight">YAYASAN HANG TUAH</h1>
            <p class="text-[#6c7f9f] text-sm mt-1.5">Sistem Pendataan Satuan Pendidikan</p>
        </div>

        <div class="bg-[#162040] rounded-2xl shadow-[0_18px_40px_rgba(22,32,64,0.2)] p-8 border border-[#1f3760]">

            <div class="mb-6">
                <h2 class="text-white text-xl font-bold">Selamat Datang</h2>
                <p class="text-[#b8c9e2] text-sm mt-1">Silakan masuk ke akun Anda</p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-3.5 bg-red-50 border border-red-200 rounded-lg flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-red-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                </div>
            @endif

            @if (session('status'))
                <div class="mb-4 p-3.5 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-sm text-green-600">{{ session('status') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-[#d7e3f5] mb-1.5">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            placeholder="nama@yayasan.ac.id" autocomplete="email"
                            class="w-full pl-10 pr-4 py-2.5 text-sm border @error('email') border-red-300 bg-red-50 @else border-[#294673] bg-white @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/35 focus:border-amber-300 transition-colors text-[#162040] placeholder-gray-400">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-[#d7e3f5] mb-1.5">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" placeholder="••••••••"
                            autocomplete="current-password"
                            class="w-full pl-10 pr-10 py-2.5 text-sm border @error('password') border-red-300 bg-red-50 @else border-[#294673] bg-white @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-300/35 focus:border-amber-300 transition-colors text-[#162040] placeholder-gray-400">
                        <button type="button" onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600">
                            <svg id="eye-icon" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-300">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember"
                            class="w-4 h-4 rounded border-[#406297] bg-[#163055] text-amber-400 focus:ring-amber-300/40 focus:ring-offset-0">
                        <span class="text-sm text-[#d7e3f5]">Ingat saya</span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full bg-[#f59e0b] hover:bg-[#f2ab2c] text-[#162040] font-bold py-2.5 px-4 rounded-lg transition-colors duration-200 text-sm shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-300/55">
                    Masuk
                </button>
            </form>
        </div>

        <p class="text-center text-[#6c7f9f] text-xs mt-6">
            © 2026 Yayasan Hang Tuah. Semua hak dilindungi.
        </p>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>';
            } else {
                input.type = 'password';
                icon.innerHTML =
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>
</body>

</html>
