@extends('app-layout')

@section('title', $sekolah->nama)

@section('content')
    @php
        $badgeConfig = [
            'KB' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'ring' => 'ring-gray-200'],
            'TK' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'ring' => 'ring-amber-200'],
            'SD' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'ring' => 'ring-blue-200'],
            'SMP' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'ring' => 'ring-green-200'],
            'SMA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700', 'ring' => 'ring-purple-200'],
            'SMK' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'ring' => 'ring-orange-200'],
        ][$sekolah->jenjang] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'ring' => 'ring-gray-200'];

        $display = fn($value, $fallback = '-') => filled($value) ? $value : $fallback;

        $websiteUrl = null;
        if (filled($sekolah->website)) {
            $websiteUrl = \Illuminate\Support\Str::startsWith($sekolah->website, ['http://', 'https://'])
                ? $sekolah->website
                : 'https://' . $sekolah->website;
        }

        $alamatLengkap = collect([
            $sekolah->alamat,
            filled($sekolah->kelurahan) ? 'Kel. ' . $sekolah->kelurahan : null,
            filled($sekolah->kecamatan) ? 'Kec. ' . $sekolah->kecamatan : null,
            $sekolah->kota?->nama,
            $sekolah->provinsi?->nama,
            $sekolah->kode_pos,
        ])
            ->filter()
            ->implode(', ');
    @endphp

    <div class="max-w-5xl mx-auto space-y-5">

        {{-- Page Header --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <a href="{{ route('sekolah.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-[#162040] transition-colors mb-3">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Data Sekolah
                </a>

                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-xl font-bold text-[#162040] leading-tight">
                        {{ $sekolah->nama }}
                    </h1>

                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 {{ $badgeConfig['bg'] }} {{ $badgeConfig['text'] }} {{ $badgeConfig['ring'] }}">
                        {{ $sekolah->jenjang }}
                    </span>

                    @if (filled($sekolah->akreditasi_nilai))
                        <span
                            class="inline-flex items-center rounded-full bg-white px-2.5 py-1 text-[11px] font-semibold text-gray-600 ring-1 ring-gray-200">
                            Akreditasi: {{ $sekolah->akreditasi_nilai }}
                            @if (filled($sekolah->akreditasi_predikat))
                                ({{ $sekolah->akreditasi_predikat }})
                            @endif
                        </span>
                    @endif
                </div>

                <p class="text-sm text-gray-400 mt-1">
                    Detail informasi satuan pendidikan Yayasan Hang Tuah
                </p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('sekolah.edit', $sekolah) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-[#162040] hover:bg-[#1e2f5a] text-white text-xs font-semibold rounded-md transition-colors shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                    Edit Data
                </a>
            </div>
        </div>


        {{-- Hero Summary --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">
                <div class="flex items-start gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-[#eef3f9] flex items-center justify-center shrink-0">
                        <svg class="w-7 h-7 text-[#162040]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6M9 10h.01M12 10h.01M15 10h.01" />
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">
                            Profil Sekolah
                        </p>
                        <h2 class="text-lg font-bold text-[#162040]">
                            {{ $sekolah->nama }}
                        </h2>

                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-sm text-gray-500">
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6M7 4h10a2 2 0 012 2v14l-4-2-3 2-3-2-4 2V6a2 2 0 012-2z" />
                                </svg>
                                NPSN: {{ $display($sekolah->npsn) }}
                            </span>

                            @if (filled($sekolah->kota?->nama) || filled($sekolah->provinsi?->nama))
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ collect([$sekolah->kota?->nama, $sekolah->provinsi?->nama])->filter()->implode(', ') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 md:min-w-[240px]">
                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="text-[11px] text-gray-400 font-medium">Jenjang</p>
                        <p class="text-sm font-bold text-[#162040] mt-0.5">{{ $display($sekolah->jenjang) }}</p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="text-[11px] text-gray-400 font-medium">Tahun Berdiri</p>
                        <p class="text-sm font-bold text-[#162040] mt-0.5">{{ $display($sekolah->tahun_berdiri) }}</p>
                    </div>
                </div>
            </div>
        </div>


        {{-- Detail Sections --}}
        <div class="space-y-5">

            {{-- Akreditasi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="form-section-title mb-4">
                    <span class="form-section-bar"></span>
                    <span class="form-section-label">Akreditasi</span>
                </div>

                @if (filled($sekolah->akreditasi_nilai))
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="rounded-xl border border-gray-100 bg-[#eef3f9] px-4 py-4">
                            <p class="text-xs text-gray-500 mb-1">Nilai Akreditasi</p>
                            <p class="text-2xl font-bold text-[#162040]">
                                {{ $sekolah->akreditasi_nilai }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-4">
                            <p class="text-xs text-gray-400 mb-1">Predikat</p>
                            <p class="text-sm font-bold text-[#162040]">
                                {{ $display($sekolah->akreditasi_predikat) }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-4">
                            <p class="text-xs text-gray-400 mb-1">Tahun Akreditasi</p>
                            <p class="text-sm font-medium text-gray-700">
                                {{ $display($sekolah->akreditasi_tahun) }}
                            </p>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-4 sm:col-span-3">
                            <p class="text-xs text-gray-400 mb-1">No. SK Akreditasi</p>
                            <p class="text-sm font-medium text-gray-700 leading-relaxed">
                                {{ $display($sekolah->no_sk_akreditasi) }}
                            </p>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-gray-400">
                        Data akreditasi belum tersedia.
                    </p>
                @endif
            </div>


            {{-- Alamat --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="form-section-title mb-4">
                    <span class="form-section-bar"></span>
                    <span class="form-section-label">Alamat</span>
                </div>

                <div class="flex items-start gap-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-[#162040] leading-relaxed">
                            {{ filled($alamatLengkap) ? $alamatLengkap : 'Alamat belum tersedia.' }}
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                            <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                                <p class="text-xs text-gray-400 mb-1">Kelurahan / Desa</p>
                                <p class="text-sm font-medium text-gray-700">
                                    {{ $display($sekolah->kelurahan) }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                                <p class="text-xs text-gray-400 mb-1">Kecamatan</p>
                                <p class="text-sm font-medium text-gray-700">
                                    {{ $display($sekolah->kecamatan) }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                                <p class="text-xs text-gray-400 mb-1">Kota / Kabupaten</p>
                                <p class="text-sm font-medium text-gray-700">
                                    {{ $display($sekolah->kota?->nama) }}
                                </p>
                            </div>

                            <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                                <p class="text-xs text-gray-400 mb-1">Provinsi</p>
                                <p class="text-sm font-medium text-gray-700">
                                    {{ $display($sekolah->provinsi?->nama) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Kontak --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="form-section-title mb-4">
                    <span class="form-section-bar"></span>
                    <span class="form-section-label">Kontak Sekolah</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="text-xs text-gray-400 mb-1">Telepon</p>
                        <p class="text-sm font-medium text-gray-700">
                            {{ $display($sekolah->telepon) }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="text-xs text-gray-400 mb-1">Email</p>
                        @if (filled($sekolah->email))
                            <a href="mailto:{{ $sekolah->email }}"
                                class="text-sm font-medium text-[#162040] hover:underline break-all">
                                {{ $sekolah->email }}
                            </a>
                        @else
                            <p class="text-sm font-medium text-gray-700">-</p>
                        @endif
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="text-xs text-gray-400 mb-1">Website</p>
                        @if ($websiteUrl)
                            <a href="{{ $websiteUrl }}" target="_blank"
                                class="text-sm font-medium text-[#162040] hover:underline break-all">
                                {{ $sekolah->website }}
                            </a>
                        @else
                            <p class="text-sm font-medium text-gray-700">-</p>
                        @endif
                    </div>
                </div>
            </div>


            {{-- Pimpinan & Operator --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="form-section-title mb-4">
                    <span class="form-section-bar"></span>
                    <span class="form-section-label">Pimpinan &amp; Operator</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-4">
                        <p class="text-xs text-gray-400 mb-1">Kepala Sekolah</p>
                        <p class="text-sm font-bold text-[#162040]">
                            {{ $display($sekolah->kepala_sekolah_nama) }}
                        </p>

                        <div class="mt-3 space-y-1">
                            <p class="text-xs text-gray-500">
                                NIP: {{ $display($sekolah->kepala_sekolah_nip) }}
                            </p>
                            <p class="text-xs text-gray-500">
                                HP: {{ $display($sekolah->kepala_sekolah_hp) }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-4">
                        <p class="text-xs text-gray-400 mb-1">Operator Sekolah</p>
                        <p class="text-sm font-bold text-[#162040]">
                            {{ $display($sekolah->operator_nama) }}
                        </p>

                        <div class="mt-3 space-y-1">
                            <p class="text-xs text-gray-500">
                                HP: {{ $display($sekolah->operator_hp) }}
                            </p>
                            <p class="text-xs text-gray-500 break-all">
                                Email: {{ $display($sekolah->email) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Kekuatan & Kelemahan --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="form-section-title mb-4">
                    <span class="form-section-bar"></span>
                    <span class="form-section-label">Kekuatan &amp; Kelemahan</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-4">
                        <p class="text-xs font-semibold text-[#162040] mb-2">
                            Kekuatan / Keunggulan
                        </p>
                        <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                            {{ $display($sekolah->kekuatan, 'Belum ada data kekuatan / keunggulan.') }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-4">
                        <p class="text-xs font-semibold text-[#162040] mb-2">
                            Kelemahan / Tantangan
                        </p>
                        <p class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                            {{ $display($sekolah->kelemahan, 'Belum ada data kelemahan / tantangan.') }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    </div>
@endsection
