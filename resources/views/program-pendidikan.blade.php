@extends('app-layout')

@section('title', 'Program Pendidikan')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#162040]">Program Pendidikan</h1>
        <p class="text-sm text-gray-500 mt-1">Data program pendidikan per sekolah</p>
    </div>

    {{-- Search & Filter --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" placeholder="Cari sekolah..."
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] bg-white shadow-sm">
        </div>
        <div class="relative">
            <select
                class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2.5 pr-9 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] cursor-pointer">
                <option>Semua Jenjang</option>
                <option>KB</option>
                <option>TK</option>
                <option>SD</option>
                <option>SMP</option>
                <option>SMA</option>
                <option>SMK</option>
            </select>
            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    {{-- School List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-50">
        @foreach ($sekolahList as $sekolah)
            @php
                $badgeConfig = [
                    'KB' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                    'TK' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                    'SD' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                    'SMP' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                    'SMA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                    'SMK' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                ][$sekolah['jenjang']] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
            @endphp
            <a href="#"
                class="flex items-center justify-between px-5 py-4 hover:bg-gray-50/70 transition-colors cursor-pointer group">
                <div class="flex items-center gap-4">
                    <div
                        class="w-10 h-10 rounded-xl {{ $badgeConfig['bg'] }} flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-bold {{ $badgeConfig['text'] }}">{{ $sekolah['jenjang'] }}</span>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#162040] group-hover:text-amber-600 transition-colors">
                            {{ $sekolah['name'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $sekolah['kota'] }}, {{ $sekolah['provinsi'] }}</p>
                    </div>
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-amber-500 transition-colors flex-shrink-0" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        @endforeach
    </div>
@endsection
