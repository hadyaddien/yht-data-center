@extends('app-layout')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Page Header --}}
    <div>
        <h1 class="text-xl font-bold text-gray-800">Profil Saya</h1>
        <p class="text-sm text-gray-500 mt-0.5">Kelola informasi akun dan keamanan Anda</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm" id="flash-success">
        <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-600">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    @if($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
        <svg class="w-5 h-5 flex-shrink-0 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Grid: Avatar Card + Info Summary --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Avatar Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col items-center gap-4">
            {{-- Avatar Display --}}
            <div class="relative group">
                <div class="w-28 h-28 rounded-full overflow-hidden ring-4 ring-[#162040]/10 flex items-center justify-center bg-[#162040]">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-full h-full object-cover" id="avatar-preview">
                    @else
                        <span class="text-white text-3xl font-bold" id="avatar-initials">{{ $user->initials }}</span>
                        <img src="" alt="Avatar" class="w-full h-full object-cover hidden" id="avatar-preview">
                    @endif
                </div>
                {{-- Upload Overlay --}}
                <label for="avatar-input"
                    class="absolute inset-0 rounded-full bg-black/50 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="text-white text-[10px] mt-1 font-medium">Ganti Foto</span>
                </label>
            </div>

            {{-- Hidden file input — auto-submit on change --}}
            <form method="POST" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" id="avatar-form">
                @csrf
                <input type="file" id="avatar-input" name="avatar" accept="image/jpeg,image/png,image/webp"
                    class="hidden" onchange="previewAndSubmit(this)">
            </form>

            {{-- Name & Role --}}
            <div class="text-center">
                <p class="text-base font-bold text-gray-800">{{ $user->name }}</p>
                <span class="inline-block mt-1 text-xs font-semibold px-2.5 py-1 rounded-full
                    @if($user->role === 'superadmin') bg-amber-100 text-amber-700
                    @elseif($user->role === 'admin_wilayah') bg-blue-100 text-blue-700
                    @else bg-green-100 text-green-700 @endif">
                    {{ $user->role_label }}
                </span>
            </div>

            {{-- Scope Info --}}
            <div class="w-full border-t border-gray-100 pt-4 space-y-2.5">
                @if($user->isSuperAdmin())
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                        </svg>
                        <span>Seluruh Indonesia</span>
                    </div>
                @elseif($user->isAdminWilayah() && $user->provinsi)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $user->provinsi->nama }}</span>
                    </div>
                @elseif($user->isKepalaSekolah() && $user->sekolah)
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h1v11H4V10zm14 0h1v11h-1V10zm-7 0h1v11h-1V10z"/>
                        </svg>
                        <div class="min-w-0">
                            <p class="font-medium text-gray-700 leading-tight">{{ $user->sekolah->nama }}</p>
                            @if($user->sekolah->kota)
                            <p class="text-xs text-gray-400">{{ $user->sekolah->kota->nama }}</p>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span>Bergabung {{ $user->created_at->translatedFormat('F Y') }}</span>
                </div>
            </div>

            {{-- Hapus Foto --}}
            @if($user->avatar)
            <form method="POST" action="{{ route('profile.avatar.remove') }}">
                @csrf
                @method('DELETE')
                <button type="submit"
                    onclick="return confirm('Hapus foto profil?')"
                    class="text-xs text-red-400 hover:text-red-600 transition-colors">
                    Hapus foto profil
                </button>
            </form>
            @endif
        </div>

        {{-- Info & Settings --}}
        <div class="md:col-span-2 space-y-5">

            {{-- Informasi Pribadi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-sm font-semibold text-gray-700 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-[#162040]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Pribadi
                </h2>

                <form method="POST" action="{{ route('profile.update') }}" id="profile-form">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Nama --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5" for="name">Nama Lengkap</label>
                            <input type="text" id="name" name="name"
                                value="{{ old('name', $user->name) }}"
                                class="w-full px-3.5 py-2.5 rounded-lg border text-sm text-gray-800
                                    @error('name') border-red-400 bg-red-50 @else border-gray-200 bg-gray-50 @enderror
                                    focus:outline-none focus:border-[#162040] focus:ring-2 focus:ring-[#162040]/10 transition-colors"
                                placeholder="Nama lengkap">
                            @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Email --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-medium text-gray-500 mb-1.5" for="email">Alamat Email</label>
                            <input type="email" id="email" name="email"
                                value="{{ old('email', $user->email) }}"
                                class="w-full px-3.5 py-2.5 rounded-lg border text-sm text-gray-800
                                    @error('email') border-red-400 bg-red-50 @else border-gray-200 bg-gray-50 @enderror
                                    focus:outline-none focus:border-[#162040] focus:ring-2 focus:ring-[#162040]/10 transition-colors"
                                placeholder="email@domain.com">
                            @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Role (read only) --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Peran</label>
                            <div class="w-full px-3.5 py-2.5 rounded-lg border border-gray-100 bg-gray-100 text-sm text-gray-500 cursor-not-allowed flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full
                                    @if($user->role === 'superadmin') bg-amber-400
                                    @elseif($user->role === 'admin_wilayah') bg-blue-400
                                    @else bg-green-400 @endif"></span>
                                {{ $user->role_label }}
                            </div>
                        </div>

                        {{-- Lingkup (read only) --}}
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1.5">Lingkup Tugas</label>
                            <div class="w-full px-3.5 py-2.5 rounded-lg border border-gray-100 bg-gray-100 text-sm text-gray-500 cursor-not-allowed truncate">
                                @if($user->isSuperAdmin())
                                    Seluruh Indonesia
                                @elseif($user->isAdminWilayah() && $user->provinsi)
                                    {{ $user->provinsi->nama }}
                                @elseif($user->isKepalaSekolah() && $user->sekolah)
                                    {{ $user->sekolah->nama }}
                                @else
                                    —
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 flex justify-end">
                        <button type="submit"
                            class="px-5 py-2.5 bg-[#162040] text-white text-sm font-semibold rounded-lg hover:bg-[#1e2f5a] transition-colors focus:outline-none focus:ring-2 focus:ring-[#162040]/30">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            {{-- Keamanan Akun --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <button onclick="togglePasswordSection()" class="w-full flex items-center justify-between text-sm font-semibold text-gray-700 group" id="password-toggle-btn">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#162040]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Keamanan Akun
                    </span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" id="password-toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div id="password-section" class="{{ $errors->has('password') || $errors->has('current_password') ? '' : 'hidden' }} mt-5 border-t border-gray-100 pt-5">
                    <p class="text-xs text-gray-400 mb-4">Kosongkan field password jika tidak ingin mengubah password.</p>
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        {{-- Preserve name & email from current values --}}
                        <input type="hidden" name="name" value="{{ $user->name }}">
                        <input type="hidden" name="email" value="{{ $user->email }}">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1.5" for="current_password">Password Saat Ini</label>
                                <input type="password" id="current_password" name="current_password"
                                    class="w-full px-3.5 py-2.5 rounded-lg border text-sm
                                        @error('current_password') border-red-400 bg-red-50 @else border-gray-200 bg-gray-50 @enderror
                                        focus:outline-none focus:border-[#162040] focus:ring-2 focus:ring-[#162040]/10 transition-colors"
                                    autocomplete="current-password"
                                    placeholder="••••••••">
                                @error('current_password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5" for="password">Password Baru</label>
                                    <input type="password" id="password" name="password"
                                        class="w-full px-3.5 py-2.5 rounded-lg border text-sm
                                            @error('password') border-red-400 bg-red-50 @else border-gray-200 bg-gray-50 @enderror
                                            focus:outline-none focus:border-[#162040] focus:ring-2 focus:ring-[#162040]/10 transition-colors"
                                        autocomplete="new-password"
                                        placeholder="Min. 8 karakter">
                                    @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500 mb-1.5" for="password_confirmation">Konfirmasi Password</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="w-full px-3.5 py-2.5 rounded-lg border border-gray-200 bg-gray-50 text-sm
                                            focus:outline-none focus:border-[#162040] focus:ring-2 focus:ring-[#162040]/10 transition-colors"
                                        autocomplete="new-password"
                                        placeholder="Ulangi password baru">
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 flex justify-end">
                            <button type="submit"
                                class="px-5 py-2.5 bg-[#162040] text-white text-sm font-semibold rounded-lg hover:bg-[#1e2f5a] transition-colors focus:outline-none focus:ring-2 focus:ring-[#162040]/30">
                                Perbarui Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Toggle password section
    function togglePasswordSection() {
        const section = document.getElementById('password-section');
        const icon    = document.getElementById('password-toggle-icon');
        section.classList.toggle('hidden');
        icon.style.transform = section.classList.contains('hidden') ? '' : 'rotate(180deg)';
    }

    // Preview avatar before upload & auto-submit
    function previewAndSubmit(input) {
        if (!input.files || !input.files[0]) return;

        const file = input.files[0];
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran gambar maksimal 2MB.');
            input.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const preview  = document.getElementById('avatar-preview');
            const initials = document.getElementById('avatar-initials');
            preview.src = e.target.result;
            preview.classList.remove('hidden');
            if (initials) initials.classList.add('hidden');
        };
        reader.readAsDataURL(file);

        // Submit the form after a brief preview
        setTimeout(() => document.getElementById('avatar-form').submit(), 300);
    }

    // Auto-dismiss success flash after 4s
    setTimeout(() => {
        const flash = document.getElementById('flash-success');
        if (flash) flash.remove();
    }, 4000);
</script>
@endsection
