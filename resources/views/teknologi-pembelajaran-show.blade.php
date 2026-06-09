@extends('app-layout')
@section('title', $sekolah->nama . ' — Teknologi Pembelajaran')
@section('content')
    @php $display = fn($v, $f='-') => filled($v) ? $v : $f; @endphp
    <div class="max-w-5xl mx-auto space-y-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <a href="{{ route('teknologi.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-[#162040] mb-3"><svg
                        class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>Kembali</a>
                <h1 class="text-xl font-bold text-[#162040]">{{ $sekolah->nama }}</h1>
                <p class="text-sm text-gray-400">NPSN: {{ $sekolah->npsn }} · {{ $sekolah->kota?->nama }},
                    {{ $sekolah->provinsi?->nama }}</p>
            </div>
            <a href="{{ route('teknologi.edit', $sekolah) }}"
                class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-[#162040] hover:bg-[#1e2f5a] text-white text-xs font-semibold rounded-md">Edit</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Pemanfaatan Teknologi</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach (['software_aplikasi_pembelajaran_status' => 'Software Pembelajaran', 'lms_kemendikdasmen_status' => 'LMS Kemendikdasmen', 'aplikasi_smart_classroom_status' => 'Smart Classroom', 'koleksi_ebook_status' => 'Koleksi E-Book', 'website_sekolah_status' => 'Website Sekolah', 'server_pembelajaran_status' => 'Server Pembelajaran', 'tenaga_khusus_it_status' => 'Tenaga IT'] as $f => $l)
                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="text-xs text-gray-400">{{ $l }}</p>
                        <p class="text-sm font-semibold">{{ $display($tp?->{$f}) }}</p>
                    </div>
                @endforeach
            </div>
            @if ($tp?->nama_lms)
                <div class="mt-3 rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Nama LMS</p>
                    <p class="text-sm font-semibold">{{ $tp->nama_lms }}</p>
                </div>
            @endif
            @if ($tp?->catatan)
                <div class="mt-3 rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Catatan</p>
                    <p class="text-sm">{{ $tp->catatan }}</p>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Platform & Media</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach (['media_sosial' => 'Media Sosial', 'platform_lms' => 'Platform LMS', 'platform_pendidikan' => 'Platform Pendidikan', 'alat_interaktif' => 'Alat Interaktif', 'platform_komunikasi' => 'Platform Komunikasi', 'aplikasi_manajemen' => 'Aplikasi Manajemen'] as $f => $l)
                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="text-xs text-gray-400">{{ $l }}</p>
                        <p class="text-sm font-semibold">{{ is_array($tp?->{$f}) ? implode(', ', $tp->{$f}) : '-' }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Perangkat Keras & Infrastruktur</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Lab Komputer</p>
                    <p class="text-sm font-semibold">{{ $tp?->memiliki_lab_komputer ? 'Ada' : 'Tidak' }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Komputer Lab</p>
                    <p class="text-sm font-semibold">{{ $display($tp?->jumlah_komputer_lab) }} unit</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Komputer Admin</p>
                    <p class="text-sm font-semibold">{{ $display($tp?->jumlah_komputer_admin) }} unit</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Laptop Guru</p>
                    <p class="text-sm font-semibold">{{ $display($tp?->jumlah_laptop_guru) }} unit</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Proyektor</p>
                    <p class="text-sm font-semibold">
                        {{ $tp?->memiliki_proyektor ? 'Ada (' . $tp->jumlah_proyektor . ' unit)' : 'Tidak' }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Internet</p>
                    <p class="text-sm font-semibold">
                        {{ $tp?->memiliki_internet ? ($tp->jenis_internet ?: 'Ada') . ' · ' . $tp->bandwidth_mbps . ' Mbps' : 'Tidak' }}
                    </p>
                </div>
            </div>
        </div>

        @php $dokumenList = $sekolah->dokumen->where('kategori', 'teknologi'); @endphp
        @if ($dokumenList->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h2 class="text-sm font-bold text-[#162040] mb-4">Dokumen</h2>
                <div class="space-y-2">
                    @foreach ($dokumenList as $doc)
                        <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2">
                            <x-file-icon :mime="$doc->mime_type" class="w-4 h-4 flex-shrink-0 text-gray-400" />
                            <a href="{{ Storage::url($doc->path) }}" target="_blank"
                                class="hover:text-[#162040] underline truncate flex-1">{{ $doc->nama }}</a>
                            <span class="text-[10px] text-gray-400 flex-shrink-0">{{ round($doc->ukuran_bytes / 1024, 1) }}
                                KB</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection
