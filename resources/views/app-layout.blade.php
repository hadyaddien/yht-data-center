<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'YHT Data Center') }} – @yield('title', 'Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-item.active { background-color: #f59e0b; color: #162040; }
        .sidebar-item.active svg { color: #162040; }
        .sidebar-item:not(.active):hover { background-color: rgba(255,255,255,0.08); }
        #user-dropdown { display: none; }
        #user-dropdown.open { display: block; }
    </style>
</head>
<body class="h-full bg-[#f1f4f8]">

{{-- Sidebar --}}
<aside id="sidebar" class="fixed left-0 top-0 h-full w-[200px] bg-[#162040] flex flex-col z-30 transition-all duration-300">

    {{-- Logo Area --}}
    <div class="flex items-center justify-between px-4 py-4 border-b border-[#1e3558]">
        <div class="flex items-center gap-3 min-w-0">
            <div class="w-9 h-9 bg-amber-500 rounded-full flex items-center justify-center flex-shrink-0">
                {{-- Anchor Icon --}}
                <svg class="w-5 h-5 text-[#162040]" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0 4a1 1 0 1 1 0-2 1 1 0 0 1 0 2zm7.93 5H13v-1a1 1 0 0 0-2 0v1H4.07A1 1 0 0 0 3 12a9 9 0 0 0 8 8.94V22a1 1 0 0 0 2 0v-1.06A9 9 0 0 0 21 12a1 1 0 0 0-1.07-1zM12 19a7 7 0 0 1-6.92-6h2.04a5 5 0 0 0 9.76 0h2.04A7 7 0 0 1 12 19z"/>
                </svg>
            </div>
            <div class="overflow-hidden sidebar-text">
                <p class="text-white font-bold text-xs leading-tight truncate">YAYASAN HANG TUAH</p>
                <p class="text-[#7a9bbf] text-[10px] leading-tight">Pendataan Satdik 2026</p>
            </div>
        </div>
        <button onclick="toggleSidebar()" class="text-[#7a9bbf] hover:text-white transition-colors ml-1 flex-shrink-0" title="Toggle Sidebar">
            <svg id="sidebar-toggle-icon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 py-3 overflow-y-auto">
        @php $currentRoute = request()->routeIs('dashboard*') ? 'dashboard' :
            (request()->routeIs('sekolah*') ? 'sekolah' :
            (request()->routeIs('program*') ? 'program' :
            (request()->routeIs('teknologi*') ? 'teknologi' :
            (request()->routeIs('sarpras*') ? 'sarpras' :
            (request()->routeIs('sdm*') ? 'sdm' :
            (request()->routeIs('rekap*') ? 'rekap' : ''))))));
        @endphp

        <a href="{{ route('dashboard') }}" class="sidebar-item flex items-center gap-3 px-4 py-2.5 text-[#c5d5e8] text-sm font-medium transition-all mx-2 rounded-lg mb-0.5 {{ $currentRoute === 'dashboard' ? 'active' : '' }}">
            <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-3a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1h-4a1 1 0 01-1-1v-7z"/>
            </svg>
            <span class="sidebar-text">Dashboard</span>
        </a>

        <a href="{{ route('sekolah.index') }}" class="sidebar-item flex items-center gap-3 px-4 py-2.5 text-[#c5d5e8] text-sm font-medium transition-all mx-2 rounded-lg mb-0.5 {{ $currentRoute === 'sekolah' ? 'active' : '' }}">
            <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h1v11H4V10zm14 0h1v11h-1V10zm-7 0h1v11h-1V10z"/>
            </svg>
            <span class="sidebar-text">Data Sekolah</span>
        </a>

        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-2.5 text-[#c5d5e8] text-sm font-medium transition-all mx-2 rounded-lg mb-0.5 {{ $currentRoute === 'program' ? 'active' : '' }}">
            <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span class="sidebar-text">Program Pendidikan</span>
        </a>

        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-2.5 text-[#c5d5e8] text-sm font-medium transition-all mx-2 rounded-lg mb-0.5 {{ $currentRoute === 'teknologi' ? 'active' : '' }}">
            <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="sidebar-text">Teknologi</span>
        </a>

        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-2.5 text-[#c5d5e8] text-sm font-medium transition-all mx-2 rounded-lg mb-0.5 {{ $currentRoute === 'sarpras' ? 'active' : '' }}">
            <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            <span class="sidebar-text">Sarana Prasarana</span>
        </a>

        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-2.5 text-[#c5d5e8] text-sm font-medium transition-all mx-2 rounded-lg mb-0.5 {{ $currentRoute === 'sdm' ? 'active' : '' }}">
            <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="sidebar-text">SDM</span>
        </a>

        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-2.5 text-[#c5d5e8] text-sm font-medium transition-all mx-2 rounded-lg mb-0.5 {{ $currentRoute === 'rekap' ? 'active' : '' }}">
            <svg class="w-4.5 h-4.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <span class="sidebar-text">Rekap & Analisis</span>
        </a>
    </nav>

    {{-- Footer --}}
    <div class="px-4 py-3 border-t border-[#1e3558]">
        <p class="text-[#4a6a8a] text-[10px] sidebar-text">© 2026 Yayasan Hang Tuah</p>
    </div>
</aside>

{{-- Main Content Wrapper --}}
<div id="main-content" class="ml-[200px] min-h-screen flex flex-col transition-all duration-300">

    {{-- Top Navbar --}}
    <header class="bg-white border-b border-gray-200 h-14 flex items-center justify-between px-6 sticky top-0 z-20 shadow-sm">
        <div class="flex items-center gap-3">
            {{-- Breadcrumb / Page Title --}}
            <button onclick="toggleSidebar()" class="text-gray-400 hover:text-gray-600 transition-colors lg:hidden">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <span class="text-sm text-gray-400 hidden sm:block">@yield('title', 'Dashboard')</span>
        </div>

        {{-- User Dropdown --}}
        <div class="relative">
            <button onclick="toggleDropdown()" class="flex items-center gap-2.5 hover:bg-gray-50 rounded-lg px-3 py-2 transition-colors focus:outline-none" id="user-btn">
                {{-- Avatar --}}
                <div class="w-8 h-8 rounded-full bg-[#162040] overflow-hidden flex items-center justify-center flex-shrink-0">
                    @if(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        <span class="text-white text-xs font-semibold">{{ auth()->user()->initials }}</span>
                    @endif
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-semibold text-gray-700 leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 leading-tight">{{ auth()->user()->role_label }}</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Dropdown Menu --}}
            <div id="user-dropdown" class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-100 py-2 z-50">
                {{-- User Info --}}
                <div class="px-4 py-3 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#162040] overflow-hidden flex items-center justify-center flex-shrink-0">
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                            @else
                                <span class="text-white text-sm font-semibold">{{ auth()->user()->initials }}</span>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            <span class="inline-block mt-0.5 text-[10px] font-semibold px-1.5 py-0.5 rounded bg-amber-100 text-amber-700">
                                {{ auth()->user()->role_label }}
                            </span>
                        </div>
                    </div>
                </div>
                {{-- Menu Items --}}
                <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-gray-50 hover:text-gray-800 transition-colors">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Saya
                </a>
                <div class="border-t border-gray-100 mt-1 pt-1">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-500 hover:bg-red-50 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="flex-1 p-6">
        @yield('content')
    </main>
</div>

{{-- Click outside to close dropdown --}}
<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('user-dropdown');
        const arrow = document.getElementById('dropdown-arrow');
        dropdown.classList.toggle('open');
        arrow.style.transform = dropdown.classList.contains('open') ? 'rotate(180deg)' : '';
    }

    document.addEventListener('click', function(e) {
        const btn = document.getElementById('user-btn');
        const dropdown = document.getElementById('user-dropdown');
        if (!btn.contains(e.target) && !dropdown.contains(e.target)) {
            dropdown.classList.remove('open');
            document.getElementById('dropdown-arrow').style.transform = '';
        }
    });

    let sidebarCollapsed = false;
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        const icon = document.getElementById('sidebar-toggle-icon');
        const texts = document.querySelectorAll('.sidebar-text');

        sidebarCollapsed = !sidebarCollapsed;
        if (sidebarCollapsed) {
            sidebar.style.width = '64px';
            main.style.marginLeft = '64px';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>';
            texts.forEach(t => t.style.display = 'none');
        } else {
            sidebar.style.width = '200px';
            main.style.marginLeft = '200px';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>';
            texts.forEach(t => t.style.display = '');
        }
    }
</script>

@stack('scripts')
</body>
</html>
