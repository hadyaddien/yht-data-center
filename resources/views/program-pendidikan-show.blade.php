@extends('app-layout')
@section('title', $sekolah->nama . ' — Program Pendidikan')
@section('content')
    @php
        $display = fn($v, $f = '-') => filled($v) ? $v : $f;
        $badge = [
            'KB' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
            'TK' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
            'SD' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
            'SMP' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
            'SMA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
            'SMK' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
        ][$sekolah->jenjang] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
    @endphp
    <div class="max-w-5xl mx-auto space-y-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <a href="{{ route('program.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-[#162040] mb-3"><svg
                        class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>Kembali</a>
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-xl font-bold text-[#162040]">{{ $sekolah->nama }}</h1>
                    <span
                        class="inline-flex items-center rounded-full px-2.5 py-1 text-[11px] font-bold ring-1 {{ $badge['bg'] }} {{ $badge['text'] }}">{{ $sekolah->jenjang }}</span>
                </div>
                <p class="text-sm text-gray-400 mt-1">NPSN: {{ $sekolah->npsn }} · {{ $sekolah->kota?->nama }},
                    {{ $sekolah->provinsi?->nama }}</p>
            </div>
            <a href="{{ route('program.edit', $sekolah) }}"
                class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-[#162040] hover:bg-[#1e2f5a] text-white text-xs font-semibold rounded-md">Edit</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Visi & Misi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400 mb-1">Visi</p>
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $display($pp?->visi, 'Belum ada data.') }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400 mb-1">Misi</p>
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $display($pp?->misi, 'Belum ada data.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Nilai Ujian & Rapor Pendidikan</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach (['nilai_ujian_ta1' => 'Nilai Ujian 24/25', 'nilai_ujian_ta2' => 'Nilai Ujian 25/26', 'pbd_literasi' => 'Literasi', 'pbd_numerasi' => 'Numerasi', 'pbd_karakter' => 'Karakter', 'pbd_kualitas_pembelajaran' => 'Kualitas PBM', 'pbd_iklim_keamanan' => 'Iklim Keamanan', 'pbd_iklim_kebhinekaan' => 'Iklim Kebhinekaan'] as $f => $l)
                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="text-xs text-gray-400">{{ $l }}</p>
                        <p class="text-sm font-semibold">{{ $display($pp?->{$f}) }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        @php $tahun = [2025, 2026]; @endphp

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Prestasi Akademik</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($tahun as $thn)
                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="text-xs font-semibold text-[#162040] mb-2">TA {{ $thn }}/{{ $thn + 1 }}
                        </p>
                        @foreach (['kota' => 'Kota', 'provinsi' => 'Provinsi', 'nasional' => 'Nasional', 'internasional' => 'Internasional'] as $k => $l)
                            <div class="flex justify-between text-sm py-0.5">
                                <span class="text-gray-500">{{ $l }}</span>
                                <span class="font-medium">{{ $display($pp?->{'prestasi_akad_' . $thn . '_' . $k}) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Prestasi Non-Akademik</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($tahun as $thn)
                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="text-xs font-semibold text-[#162040] mb-2">TA {{ $thn }}/{{ $thn + 1 }}
                        </p>
                        @foreach (['kota' => 'Kota', 'provinsi' => 'Provinsi', 'nasional' => 'Nasional', 'internasional' => 'Internasional'] as $k => $l)
                            <div class="flex justify-between text-sm py-0.5">
                                <span class="text-gray-500">{{ $l }}</span>
                                <span class="font-medium">{{ $display($pp?->{'prestasi_non_' . $thn . '_' . $k}) }}</span>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Kurikulum & Sumber Dana</h2>
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Kurikulum</p>
                    <p class="text-sm font-semibold">{{ $display($pp?->kurikulum) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Kur. Kebaharian</p>
                    <p class="text-sm font-semibold">{{ $display($pp?->kurikulum_kebaharian) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Guru Kebaharian</p>
                    <p class="text-sm font-semibold">{{ $display($pp?->jumlah_guru_kebaharian) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">BOS</p>
                    <p class="text-sm font-semibold">{{ $display($pp?->penerimaan_bos) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">BOP</p>
                    <p class="text-sm font-semibold">{{ $display($pp?->penerimaan_bop) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Program Unggulan & Ekstrakurikuler</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400 mb-1">Program Unggulan</p>
                    <p class="text-sm text-gray-600 whitespace-pre-line">
                        {{ $display($pp?->program_unggulan, 'Belum ada data.') }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400 mb-1">Ekstrakurikuler</p>
                    <p class="text-sm text-gray-600 whitespace-pre-line">
                        {{ $display($pp?->ekstrakurikuler, 'Belum ada data.') }}</p>
                </div>
            </div>
        </div>

        {{-- DOKUMEN --}}
        @php $dokumenList = $sekolah->dokumen->where('kategori', 'program_pendidikan'); @endphp
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
