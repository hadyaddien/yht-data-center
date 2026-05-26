@extends('app-layout')

@section('title', 'Manajemen User')

@section('content')

    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="flex items-center gap-3 mb-5 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm"
            id="flash-success">
            <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="flex items-center gap-3 mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <svg class="w-5 h-5 flex-shrink-0 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#162040]">Manajemen User</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $total }} user terdaftar</p>
        </div>
        @if ($canCreateUser)
            <a href="{{ route('users.create') }}"
                class="flex items-center gap-2 bg-[#162040] hover:bg-[#1e2f5a] text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah User
            </a>
        @endif
    </div>

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('users.index') }}" class="flex flex-col sm:flex-row gap-3 mb-5">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau email..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] bg-white shadow-sm">
        </div>
        <div class="relative">
            <select name="role" onchange="this.form.submit()"
                class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2.5 pr-9 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] cursor-pointer">
                <option value="">Semua Role</option>
                <option value="superadmin" {{ request('role') === 'superadmin' ? 'selected' : '' }}>Super Admin
                </option>
                <option value="admin_wilayah" {{ request('role') === 'admin_wilayah' ? 'selected' : '' }}>Admin Wilayah
                </option>
                <option value="kepala_sekolah" {{ request('role') === 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah
                </option>
            </select>
            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <button type="submit"
            class="px-4 py-2.5 bg-[#162040] text-white text-sm font-semibold rounded-lg hover:bg-[#1e2f5a] transition-colors shadow-sm">
            Cari
        </button>
        @if (request('search') || request('role'))
            <a href="{{ route('users.index') }}"
                class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                Reset
            </a>
        @endif
    </form>

    {{-- User Table --}}
    @php
        $roleBadge = [
            'superadmin' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'label' => 'Super Admin'],
            'admin_wilayah' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'Admin Wilayah'],
            'kepala_sekolah' => ['bg' => 'bg-teal-100', 'text' => 'text-teal-700', 'label' => 'Kepala Sekolah'],
        ];
    @endphp

    @if ($users->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 py-16 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <p class="text-gray-400 text-sm">Tidak ada user yang ditemukan.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/60">
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">User
                        </th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Role
                        </th>
                        <th
                            class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">
                            Wilayah / Sekolah</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Status
                        </th>
                        <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($users as $user)
                        @php $badge = $roleBadge[$user->role] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'label' => $user->role]; @endphp
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    {{-- Avatar --}}
                                    <div
                                        class="w-9 h-9 rounded-full flex-shrink-0 overflow-hidden bg-[#162040] flex items-center justify-center">
                                        @if ($user->avatar_url)
                                            <img src="{{ $user->avatar_url }}" alt=""
                                                class="w-full h-full object-cover">
                                        @else
                                            <span class="text-white text-xs font-bold">{{ $user->initials }}</span>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-800 truncate flex items-center gap-1.5">
                                            {{ $user->name }}
                                            @if ($user->id === auth()->id())
                                                <span
                                                    class="text-[10px] font-medium text-amber-600 bg-amber-100 px-1.5 py-0.5 rounded-full">Anda</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400 truncate">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $badge['bg'] }} {{ $badge['text'] }}">
                                    {{ $badge['label'] }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 hidden md:table-cell">
                                @if ($user->role === 'admin_wilayah' && $user->provinsi)
                                    <span class="text-gray-600 text-xs">{{ $user->provinsi->nama }}</span>
                                @elseif($user->role === 'kepala_sekolah' && $user->sekolah)
                                    <span class="text-gray-600 text-xs">{{ $user->sekolah->nama }}</span>
                                @else
                                    <span class="text-gray-300 text-xs">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                @if ($user->is_active)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        Aktif
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-end gap-1">
                                    @php
                                        $canManageTarget =
                                            auth()->user()->isSuperAdmin() ||
                                            ($actorIsAdminWilayah &&
                                                $user->role === 'kepala_sekolah' &&
                                                optional($user->sekolah)->provinsi_id === auth()->user()->provinsi_id);
                                    @endphp

                                    @if ($canManageTarget)
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="p-2 rounded-lg text-gray-400 hover:text-[#162040] hover:bg-gray-100 transition-colors"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.toggle-active', $user) }}"
                                                onsubmit="return confirm('{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan kembali' }} akun \"{{ addslashes($user->name) }}\"?')">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="p-2 rounded-lg {{ $user->is_active ? 'text-gray-400 hover:text-amber-600 hover:bg-amber-50' : 'text-gray-400 hover:text-emerald-600 hover:bg-emerald-50' }} transition-colors"
                                                    title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    @if ($user->is_active)
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M18.364 5.636l-1.414 1.414M7.05 16.95l-1.414 1.414M12 7v5l3 3m6 0A9 9 0 1112 3a9 9 0 019 9z" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m5 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    @endif
                                                </button>
                                            </form>
                                        @endif
                                    @endif

                                    @if (auth()->user()->isSuperAdmin())
                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.destroy', $user) }}"
                                                onsubmit="return confirm('Hapus user \"{{ addslashes($user->name) }}\"? Tindakan ini tidak dapat dibatalkan.')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition-colors"
                                                    title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="p-2 text-gray-200" title="Tidak dapat menghapus akun sendiri">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <script>
        // Auto-dismiss flash message
        setTimeout(() => {
            const el = document.getElementById('flash-success');
            if (el) el.remove();
        }, 4000);
    </script>
@endsection
