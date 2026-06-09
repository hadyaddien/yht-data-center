@extends('app-layout')
@section('title', 'Sarana Prasarana')
@section('content')
    @if (session('success'))
        <div
            class="flex items-center gap-3 mb-5 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
            <svg class="w-5 h-5 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('success') }}</span><button onclick="this.parentElement.remove()"
                class="ml-auto text-green-400 hover:text-green-600">&times;</button>
        </div>
    @endif
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#162040]">Sarana Prasarana</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $total }} sekolah</p>
        </div>
    </div>
    <form method="GET" action="{{ route('sarpras.index') }}" class="flex flex-col sm:flex-row gap-3 mb-5">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Cari nama sekolah atau NPSN..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] bg-white shadow-sm">
        </div>
        <div class="relative">
            <select name="jenjang" onchange="this.form.submit()"
                class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2.5 pr-9 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] cursor-pointer">
                <option value="">Semua Jenjang</option>
                @foreach (['KB', 'TK', 'SD', 'SMP', 'SMA', 'SMK'] as $j)
                    <option value="{{ $j }}" {{ request('jenjang') === $j ? 'selected' : '' }}>
                        {{ $j }}
                    </option>
                @endforeach
            </select>
            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div class="relative">
            <select name="lokasi" onchange="this.form.submit()"
                class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2.5 pr-9 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] cursor-pointer">
                <option value="">Semua Lokasi</option>
                @foreach ($provinsiList as $prov)
                    <option value="{{ $prov->id }}" {{ request('lokasi') == $prov->id ? 'selected' : '' }}>
                        {{ $prov->nama }}</option>
                @endforeach
            </select>
            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <div class="relative">
            <select name="akreditasi" onchange="this.form.submit()"
                class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2.5 pr-9 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] cursor-pointer">
                <option value="">Semua Akreditasi</option>
                <option value="unggul" {{ request('akreditasi') === 'unggul' ? 'selected' : '' }}>UNGGUL</option>
                <option value="baik_sekali" {{ request('akreditasi') === 'baik_sekali' ? 'selected' : '' }}>BAIK SEKALI
                </option>
                <option value="baik" {{ request('akreditasi') === 'baik' ? 'selected' : '' }}>BAIK</option>
                <option value="cukup" {{ request('akreditasi') === 'cukup' ? 'selected' : '' }}>CUKUP</option>
                <option value="belum" {{ request('akreditasi') === 'belum' ? 'selected' : '' }}>Belum Terakreditasi
                </option>
            </select>
            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
        <button type="submit"
            class="px-4 py-2.5 bg-[#162040] text-white text-sm font-semibold rounded-lg hover:bg-[#1e2f5a] transition-colors shadow-sm">Cari</button>
        @if (request('search') || request('jenjang') || request('lokasi') || request('akreditasi'))
            <a href="{{ route('sarpras.index') }}"
                class="px-4 py-2.5 bg-gray-100 text-gray-600 text-sm font-semibold rounded-lg hover:bg-gray-200 transition-colors">Reset</a>
        @endif
    </form>
    @if ($sekolahList->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 py-16 text-center">
            <p class="text-gray-400 text-sm">Tidak ada sekolah ditemukan.</p>
        </div>
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">@include('sekolah.partials.module-table', [
                'sekolahList' => $sekolahList,
                'routePrefix' => 'sarpras',
            ])</div>
        </div>
    @endif
@endsection
