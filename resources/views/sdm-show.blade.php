@extends('app-layout')
@section('title', $sekolah->nama . ' — SDM')
@section('content')
    @php $display = fn($v, $f='-') => filled($v) ? $v : $f; @endphp
    <div class="max-w-5xl mx-auto space-y-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <a href="{{ route('sdm.index') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-[#162040] mb-3"><svg
                        class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>Kembali</a>
                <h1 class="text-xl font-bold text-[#162040]">{{ $sekolah->nama }}</h1>
                <p class="text-sm text-gray-400">NPSN: {{ $sekolah->npsn }} · {{ $sekolah->kota?->nama }},
                    {{ $sekolah->provinsi?->nama }}</p>
            </div>
            <a href="{{ route('sdm.edit', $sekolah) }}"
                class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-[#162040] hover:bg-[#1e2f5a] text-white text-xs font-semibold rounded-md">Edit</a>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Tenaga Pendidik</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <div class="rounded-xl border border-gray-100 bg-blue-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Total Guru</p>
                    <p class="text-lg font-bold text-[#162040]">{{ $display($sdm?->jumlah_guru) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">GTY</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->guru_tetap_yayasan) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">GTT</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->guru_tidak_tetap) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Rombel</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->jumlah_rombel) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Status Kepegawaian</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs font-semibold mb-2">Guru</p>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">PNS</span><span
                                class="font-medium">{{ $display($sdm?->guru_pns) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">P3K</span><span
                                class="font-medium">{{ $display($sdm?->guru_p3k) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Honorer</span><span
                                class="font-medium">{{ $display($sdm?->guru_honorer) }}</span></div>
                    </div>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs font-semibold mb-2">Karyawan</p>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">PNS</span><span
                                class="font-medium">{{ $display($sdm?->karyawan_pns) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">P3K</span><span
                                class="font-medium">{{ $display($sdm?->karyawan_p3k) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Honorer</span><span
                                class="font-medium">{{ $display($sdm?->karyawan_honorer) }}</span></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Kualifikasi Guru</h2>
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">S1 Pendidikan</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->guru_s1_pendidikan) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">S1 Non-Pend</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->guru_s1_non_pendidikan) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">S2</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->guru_s2) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">S3</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->guru_s3) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Sertifikasi</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->guru_sertifikasi) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Karyawan</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Total</p>
                    <p class="text-lg font-bold">{{ $display($sdm?->jumlah_karyawan) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Tetap</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->karyawan_tetap) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Tidak Tetap</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->karyawan_tidak_tetap) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Data Siswa</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div class="rounded-xl border border-gray-100 bg-blue-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Total Murid</p>
                    <p class="text-lg font-bold text-[#162040]">{{ $display($sdm?->jumlah_murid_total) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Laki-laki</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->jumlah_murid_laki) }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Perempuan</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->jumlah_murid_perempuan) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Pekerjaan Orang Tua</h2>
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                @foreach (['murid_ortu_tni_al' => 'TNI AL', 'murid_ortu_tni' => 'TNI', 'murid_ortu_polisi' => 'Polisi', 'murid_ortu_pns' => 'PNS', 'murid_ortu_pengusaha' => 'Pengusaha', 'murid_ortu_wiraswasta' => 'Wiraswasta', 'murid_ortu_buruh' => 'Buruh', 'murid_ortu_guru' => 'Guru', 'murid_ortu_lainnya_jumlah' => 'Lainnya'] as $f => $l)
                    <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                        <p class="text-xs text-gray-400">{{ $l }}</p>
                        <p class="text-sm font-semibold">{{ $display($sdm?->{$f}) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h2 class="text-sm font-bold text-[#162040] mb-4">Informasi Tambahan</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Rata Gaji Guru</p>
                    <p class="text-sm font-semibold">
                        {{ $sdm?->rata_gaji_guru ? 'Rp ' . number_format($sdm->rata_gaji_guru, 0, ',', '.') : '-' }}</p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Rata Gaji Karyawan</p>
                    <p class="text-sm font-semibold">
                        {{ $sdm?->rata_gaji_karyawan ? 'Rp ' . number_format($sdm->rata_gaji_karyawan, 0, ',', '.') : '-' }}
                    </p>
                </div>
                <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400">Masa Jabatan</p>
                    <p class="text-sm font-semibold">{{ $display($sdm?->masa_jabatan_kepsek) }} thn</p>
                </div>
            </div>
            @if ($sdm?->hambatan_tantangan)
                <div class="mt-3 rounded-xl border border-gray-100 bg-gray-50 px-4 py-3">
                    <p class="text-xs text-gray-400 mb-1">Hambatan & Tantangan</p>
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $sdm->hambatan_tantangan }}</p>
                </div>
            @endif
        </div>
        @php $dokumenList = $sekolah->dokumen->where('kategori', 'sdm'); @endphp
        @if ($dokumenList->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <h2 class="text-sm font-bold text-[#162040] mb-4">Dokumen</h2>
                <div class="space-y-2">
                    @foreach ($dokumenList as $doc)
                        <div class="flex items-center gap-2 text-sm text-gray-600 bg-gray-50 rounded-lg px-3 py-2">
                            <x-file-icon :mime="$doc->mime_type" class="w-4 h-4 flex-shrink-0 text-gray-400" />
                            <a href="{{ Storage::url($doc->path) }}" target="_blank"
                                class="hover:text-[#162040] underline truncate flex-1">{{ $doc->nama }}</a>
                            <span
                                class="text-[10px] text-gray-400 flex-shrink-0">{{ round($doc->ukuran_bytes / 1024, 1) }}
                                KB</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
