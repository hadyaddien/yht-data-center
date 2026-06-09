@extends('app-layout')
@section('title', $sekolah->nama . ' — Sarana Prasarana')
@section('content')
    @php
        $display = fn($v, $f = '-') => filled($v) ? $v : $f;
        $items = [
            'perpustakaan' => 'Perpustakaan',
            'laboratorium_ipa' => 'Lab IPA',
            'laboratorium_bahasa' => 'Lab Bahasa',
            'laboratorium_komputer' => 'Lab Komputer',
            'ruang_keterampilan' => 'Ruang Keterampilan',
            'ruang_seni' => 'Ruang Seni',
            'ruang_osis' => 'Ruang OSIS',
            'uks_klinik_kesehatan' => 'UKS',
            'ruang_kepala_sekolah' => 'Ruang Kepsek',
            'ruang_wakil_kepala_sekolah' => 'Ruang Wakepsek',
            'ruang_tata_usaha' => 'Ruang TU',
            'ruang_bendahara' => 'Ruang Bendahara',
            'ruang_guru' => 'Ruang Guru',
            'ruang_bk_konseling' => 'Ruang BK',
            'aula_pertemuan' => 'Aula',
            'kantin_sekolah' => 'Kantin',
            'lapangan_olahraga' => 'Lapangan',
            'lab_studio_kebaharian' => 'Lab Kebaharian',
            'toilet_terpisah' => 'Toilet',
            'taman_hijau' => 'Taman Hijau',
            'tempat_parkir' => 'Parkir',
            'ruang_ibadah' => 'Ruang Ibadah',
            'ape_kb_tk' => 'APE KB/TK',
            'ifp_dari_pemerintah' => 'IFP Pemerintah',
            'laptop_ext_hd_dari_pemerintah' => 'Laptop & Ext HD',
        ];
    @endphp
    <div class="max-w-5xl mx-auto space-y-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <a href="{{ route('sarpras.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-[#162040] mb-3"><svg
                        class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>Kembali</a>
                <h1 class="text-xl font-bold text-[#162040]">{{ $sekolah->nama }}</h1>
                <p class="text-sm text-gray-400">NPSN: {{ $sekolah->npsn }} · {{ $sekolah->kota?->nama }},
                    {{ $sekolah->provinsi?->nama }}</p>
            </div>
            <a href="{{ route('sarpras.edit', $sekolah) }}"
                class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-[#162040] hover:bg-[#1e2f5a] text-white text-xs font-semibold rounded-md">Edit</a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Data Umum</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Luas Tanah</p>
                    <p class="text-sm font-semibold">{{ $display($sp?->luas_tanah) }} m²</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Luas Bangunan</p>
                    <p class="text-sm font-semibold">{{ $display($sp?->luas_bangunan) }} m²</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Biaya Sewa</p>
                    <p class="text-sm font-semibold">
                        {{ $sp?->biaya_sewa_lahan ? 'Rp ' . number_format($sp->biaya_sewa_lahan, 0, ',', '.') : '-' }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Fasilitas & Sarana</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach ($items as $f => $l)
                    <div
                        class="rounded-xl border px-4 py-3 {{ $sp && $sp->{$f . '_ada'} ? 'border-green-200 bg-green-50/50' : 'border-gray-100 bg-gray-50' }}">
                        <div class="flex items-center gap-2 mb-1"><span
                                class="w-2 h-2 rounded-full {{ $sp && $sp->{$f . '_ada'} ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                            <p
                                class="text-xs font-medium {{ $sp && $sp->{$f . '_ada'} ? 'text-[#162040]' : 'text-gray-500' }}">
                                {{ $l }}</p>
                        </div>
                        <p class="text-xs text-gray-400">Kondisi: <span
                                class="font-semibold">{{ $sp && $sp->{$f . '_ada'} ? $display($sp->{$f . '_kondisi'}) . '%' : '-' }}</span>
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
        @php $dokumenList = $sekolah->dokumen->where('kategori', 'sarpras'); @endphp
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
    @endsection
