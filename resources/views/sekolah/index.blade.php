@extends('app-layout')

@section('title', 'Data Sekolah')

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div class="flex items-center gap-3 mb-5 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm" id="flash-success">
    <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <span>{{ session('success') }}</span>
    <button onclick="this.parentElement.remove()" class="ml-auto text-green-400 hover:text-green-600">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>
@endif

<div class="flex items-start justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-[#162040]">Data Sekolah</h1>
        <p class="text-sm text-gray-500 mt-1">{{ $total }} sekolah terdaftar</p>
    </div>
    <a href="{{ route('sekolah.create') }}"
        class="flex items-center gap-2 bg-[#162040] hover:bg-[#1e2f5a] text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Sekolah
    </a>
</div>

{{-- Search & Filter --}}
<form method="GET" action="{{ route('sekolah.index') }}" class="flex flex-col sm:flex-row gap-3 mb-5" id="filter-form">
    <div class="relative flex-1">
        <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Cari nama sekolah, NPSN, atau kota..."
            class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] bg-white shadow-sm">
    </div>
    <div class="relative">
        <select name="jenjang" onchange="this.form.submit()"
            class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2.5 pr-9 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] cursor-pointer">
            <option value="">Semua Jenjang</option>
            @foreach(['KB','TK','SD','SMP','SMA','SMK'] as $j)
            <option value="{{ $j }}" {{ request('jenjang') === $j ? 'selected' : '' }}>{{ $j }}</option>
            @endforeach
        </select>
        <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
    <button type="submit" class="px-4 py-2.5 bg-[#162040] text-white text-sm font-semibold rounded-lg hover:bg-[#1e2f5a] transition-colors shadow-sm">
        Cari
    </button>
    @if(request('search') || request('jenjang'))
    <a href="{{ route('sekolah.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">
        Reset
    </a>
    @endif
</form>

{{-- School List --}}
@if($sekolahList->isEmpty())
<div class="bg-white rounded-xl shadow-sm border border-gray-100 py-16 text-center">
    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h1v11H4V10zm14 0h1v11h-1V10zm-7 0h1v11h-1V10z"/>
    </svg>
    <p class="text-gray-400 text-sm">Tidak ada sekolah yang ditemukan.</p>
</div>
@else
<div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-50">
    @foreach($sekolahList as $sekolah)
    @php
        $badgeConfig = [
            'KB'  => ['bg' => 'bg-gray-100',   'text' => 'text-gray-600'],
            'TK'  => ['bg' => 'bg-amber-100',  'text' => 'text-amber-700'],
            'SD'  => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700'],
            'SMP' => ['bg' => 'bg-green-100',  'text' => 'text-green-700'],
            'SMA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
            'SMK' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
        ][$sekolah->jenjang] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
    @endphp
    <div class="flex items-center justify-between px-5 py-4 hover:bg-gray-50/70 transition-colors">
        <div class="flex-1 min-w-0">
            <div class="flex flex-wrap items-center gap-2 mb-1">
                <span class="text-sm font-semibold text-gray-800">{{ $sekolah->nama }}</span>
                <span class="text-xs font-bold px-2 py-0.5 rounded-md {{ $badgeConfig['bg'] }} {{ $badgeConfig['text'] }}">
                    {{ $sekolah->jenjang }}
                </span>
                @if($sekolah->akreditasi_label)
                <span class="text-xs font-medium px-2 py-0.5 rounded-md bg-teal-50 text-teal-700 border border-teal-100">
                    Akreditasi: {{ $sekolah->akreditasi_label }}
                </span>
                @endif
            </div>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-gray-400">
                <span class="flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/>
                    </svg>
                    NPSN: {{ $sekolah->npsn }}
                </span>
                <span class="flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ $sekolah->kota?->nama ?? '-' }}, {{ $sekolah->provinsi?->nama ?? '-' }}
                </span>
                @if($sekolah->telepon)
                <span class="flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    {{ $sekolah->telepon }}
                </span>
                @endif
                @if($sekolah->email)
                <span class="flex items-center gap-1">
                    <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ $sekolah->email }}
                </span>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-1 ml-4 flex-shrink-0">
            <a href="{{ route('sekolah.show', $sekolah) }}"
                title="Lihat Detail"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-[#162040] hover:bg-gray-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </a>
            <a href="{{ route('sekolah.edit', $sekolah) }}"
                title="Edit"
                class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-amber-600 hover:bg-amber-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>
            <form method="POST" action="{{ route('sekolah.destroy', $sekolah) }}"
                onsubmit="return confirm('Hapus sekolah {{ addslashes($sekolah->nama) }}? Data terkait juga akan dihapus.')">
                @csrf
                @method('DELETE')
                <button type="submit" title="Hapus"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
    @endforeach
</div>
@endif

<script>
    setTimeout(() => { document.getElementById('flash-success')?.remove(); }, 4000);
</script>
@endsection
