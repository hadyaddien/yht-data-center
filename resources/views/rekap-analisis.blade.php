@extends('app-layout')

@section('title', 'Rekap & Analisis')

@section('content')
    <div class="mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#162040]">Rekap &amp; Analisis</h1>
            <p class="text-sm text-gray-500 mt-1">Laporan komprehensif untuk pimpinan Yayasan Hang Tuah</p>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 mb-6 bg-gray-100 p-1 rounded-lg w-fit">
        <button id="tab-btn-keseluruhan" onclick="switchTab('keseluruhan')"
            class="px-4 py-1.5 rounded-md text-sm font-semibold bg-white text-[#162040] shadow-sm transition-all">Keseluruhan</button>
        <button id="tab-btn-per-jenjang" onclick="switchTab('per-jenjang')"
            class="px-4 py-1.5 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700 transition-all">Per
            Jenjang</button>
        <button id="tab-btn-per-wilayah" onclick="switchTab('per-wilayah')"
            class="px-4 py-1.5 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700 transition-all">Per
            Wilayah</button>
    </div>

    {{-- ===== TAB: KESELURUHAN ===== --}}
    <div id="tab-keseluruhan">
        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h1v11H4V10zm14 0h1v11h-1V10zm-7 0h1v11h-1V10z" />
                    </svg>
                </div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Sekolah</p>
                <p class="text-2xl font-bold text-[#162040] mt-1">{{ $stats['total_sekolah'] }}</p>
                <p class="text-xs text-gray-400">Seluruh jenjang</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Guru</p>
                <p class="text-2xl font-bold text-[#162040] mt-1">{{ $stats['total_guru'] }}</p>
                <p class="text-xs text-gray-400">Tenaga pendidik</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Karyawan</p>
                <p class="text-2xl font-bold text-[#162040] mt-1">{{ $stats['total_karyawan'] }}</p>
                <p class="text-xs text-gray-400">&nbsp;</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <div class="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Rombel</p>
                <p class="text-2xl font-bold text-[#162040] mt-1">{{ $stats['total_rombel'] }}</p>
                <p class="text-xs text-gray-400">Kelas</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <div class="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Murid</p>
                <p class="text-2xl font-bold text-[#162040] mt-1">{{ $stats['total_murid'] }}</p>
                <p class="text-xs text-gray-400">L: {{ $stats['total_murid_laki'] }} | P:
                    {{ $stats['total_murid_perempuan'] }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <div class="w-10 h-10 rounded-full bg-teal-50 flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Terakreditasi</p>
                <p class="text-2xl font-bold text-[#162040] mt-1">{{ $stats['terakreditasi'] }}</p>
                <p class="text-xs text-gray-400">&nbsp;</p>
            </div>
        </div>

        {{-- Charts Row --}}
        <div class="grid grid-cols-1 xl:grid-cols-5 gap-4 mb-6">
            <div class="xl:col-span-3 bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-[#162040] mb-4">Jumlah Sekolah, Guru, dan Murid per Jenjang</h3>
                <div class="relative" style="height: 220px;">
                    <canvas id="rekapJenjangChart"></canvas>
                </div>
            </div>
            <div class="xl:col-span-2 bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-[#162040] mb-4">Rata-rata Rapor Pendidikan</h3>
                <div class="relative flex items-center justify-center" style="height: 220px;">
                    <canvas id="raporRadarChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Teknologi Adopsi --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-6">
            <h3 class="text-sm font-semibold text-[#162040] mb-4">Persentase Adopsi Teknologi</h3>
            <div class="space-y-3">
                @foreach ($teknologiAdopsi as $item)
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-gray-600 w-44 flex-shrink-0">{{ $item['label'] }}</span>
                        <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $item['color'] }} h-full rounded-full transition-all"
                                style="width: {{ $item['persen'] }}%"></div>
                        </div>
                        <span
                            class="text-xs font-semibold text-gray-600 w-10 text-right flex-shrink-0">{{ $item['persen'] }}%</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Komposisi Ortu Murid --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-6">
            <h3 class="text-sm font-semibold text-[#162040] mb-4">Komposisi Pekerjaan Orang Tua Murid</h3>
            <div class="space-y-3">
                @forelse ($komposisiOrtu as $item)
                    <div class="flex items-center gap-4">
                        <span class="text-xs text-gray-600 w-28 flex-shrink-0">{{ $item['label'] }}</span>
                        <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                            <div class="{{ $item['color'] }} h-full rounded-full transition-all"
                                style="width: {{ min(100, $item['persen']) }}%"></div>
                        </div>
                        <span class="text-xs text-gray-600 w-24 text-right">{{ $item['count'] }} murid</span>
                        <span class="text-xs font-semibold text-gray-600 w-12 text-right">{{ $item['persen'] }}%</span>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada data komposisi pekerjaan orang tua.</p>
                @endforelse
            </div>
        </div>

        {{-- School Summary Cards --}}
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-[#162040] mb-4">Kartu Ringkasan Per Sekolah</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach ($ringkasanSekolah as $s)
                    @php
                        $badgeConfig = [
                            'KB' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                            'TK' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                            'SD' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                            'SMP' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                            'SMA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                            'SMK' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                        ][$s['jenjang']] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
                    @endphp
                    <div
                        class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-2 mb-3">
                            <span
                                class="text-xs font-bold px-2 py-0.5 rounded-md {{ $badgeConfig['bg'] }} {{ $badgeConfig['text'] }}">{{ $s['jenjang'] }}</span>
                            @if ($s['akreditasi'])
                                <span
                                    class="text-xs font-medium px-2 py-0.5 rounded-md bg-teal-50 text-teal-700 border border-teal-100">{{ $s['akreditasi'] }}</span>
                            @endif
                        </div>
                        <p class="text-sm font-bold text-[#162040] leading-tight">{{ $s['name'] }}</p>
                        <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-1">
                            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $s['lokasi'] }}
                        </p>
                        <p class="text-xs text-gray-400 mt-1">Kepsek: <span
                                class="text-gray-600 font-medium">{{ $s['kepsek'] }}</span></p>
                        <p class="text-xs text-gray-400 mt-0.5">NPSN: <span
                                class="text-gray-600 font-medium">{{ $s['npsn'] }}</span></p>
                        <div class="grid grid-cols-4 gap-2 mt-4 pt-4 border-t border-gray-100">
                            <div class="text-center">
                                <p class="text-xl font-bold text-[#162040]">{{ $s['guru'] }}</p>
                                <p class="text-[10px] text-gray-400">Guru</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xl font-bold text-[#162040]">{{ $s['karyawan'] }}</p>
                                <p class="text-[10px] text-gray-400">Karyawan</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xl font-bold text-[#162040]">{{ $s['rombel'] }}</p>
                                <p class="text-[10px] text-gray-400">Rombel</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xl font-bold text-[#162040]">{{ $s['murid_total'] }}</p>
                                <p class="text-[10px] text-gray-400">Murid</p>
                            </div>
                        </div>
                        @if ($s['rapor']['literasi'] || $s['rapor']['numerasi'] || $s['rapor']['karakter'])
                            <div class="mt-4 pt-3 border-t border-gray-100">
                                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wide mb-2">Rapor
                                    Pendidikan</p>
                                <div class="flex gap-2 flex-wrap">
                                    @if ($s['rapor']['literasi'])
                                        <div class="flex-1 min-w-0 bg-blue-50 rounded-lg p-2 text-center">
                                            <p class="text-sm font-bold text-blue-700">{{ $s['rapor']['literasi'] }}</p>
                                            <p class="text-[10px] text-blue-500">Literasi</p>
                                        </div>
                                    @endif
                                    @if ($s['rapor']['numerasi'])
                                        <div class="flex-1 min-w-0 bg-green-50 rounded-lg p-2 text-center">
                                            <p class="text-sm font-bold text-green-700">{{ $s['rapor']['numerasi'] }}</p>
                                            <p class="text-[10px] text-green-500">Numerasi</p>
                                        </div>
                                    @endif
                                    @if ($s['rapor']['karakter'])
                                        <div class="flex-1 min-w-0 bg-purple-50 rounded-lg p-2 text-center">
                                            <p class="text-sm font-bold text-purple-700">{{ $s['rapor']['karakter'] }}</p>
                                            <p class="text-[10px] text-purple-500">Karakter</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Summary Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-[#162040]">Tabel Ringkasan Semua Sekolah</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Sekolah</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Jenjang</th>
                            <th class="text-left px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Provinsi</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Akreditasi</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Guru</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Karyawan</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Murid</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Rombel</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Literasi</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Numerasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($ringkasanSekolah as $s)
                            @php
                                $bc = [
                                    'KB' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                                    'TK' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                                    'SD' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                                    'SMP' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                                    'SMA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                                    'SMK' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                                ][$s['jenjang']] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-5 py-3 font-medium text-gray-800">{{ $s['name'] }}</td>
                                <td class="px-3 py-3 text-center">
                                    <span
                                        class="text-xs font-bold px-2 py-0.5 rounded-md {{ $bc['bg'] }} {{ $bc['text'] }}">{{ $s['jenjang'] }}</span>
                                </td>
                                <td class="px-3 py-3 text-gray-600">{{ $s['provinsi'] }}</td>
                                <td class="px-3 py-3 text-center text-gray-600 text-xs">{{ $s['akreditasi'] ?? '–' }}</td>
                                <td class="px-3 py-3 text-center text-gray-700 font-medium">{{ $s['guru'] }}</td>
                                <td class="px-3 py-3 text-center text-gray-700 font-medium">{{ $s['karyawan'] }}</td>
                                <td class="px-3 py-3 text-center text-gray-700 font-medium">{{ $s['murid_total'] }}</td>
                                <td class="px-3 py-3 text-center text-gray-700 font-medium">{{ $s['rombel'] }}</td>
                                <td class="px-3 py-3 text-center text-gray-700">{{ $s['rapor']['literasi'] ?? '–' }}</td>
                                <td class="px-3 py-3 text-center text-gray-700">{{ $s['rapor']['numerasi'] ?? '–' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== TAB: PER JENJANG ===== --}}
    <div id="tab-per-jenjang" class="hidden">
        {{-- Jenjang Filter Buttons --}}
        <div class="flex flex-wrap gap-2 mb-6" id="jenjang-buttons"></div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6" id="jenjang-stats">
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Jumlah Sekolah</p>
                <p class="text-2xl font-bold text-[#162040] mt-1" id="jj-sekolah">–</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Guru</p>
                <p class="text-2xl font-bold text-[#162040] mt-1" id="jj-guru">–</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Rombel</p>
                <p class="text-2xl font-bold text-[#162040] mt-1" id="jj-rombel">–</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Murid</p>
                <p class="text-2xl font-bold text-[#162040] mt-1" id="jj-murid">–</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Terakreditasi</p>
                <p class="text-2xl font-bold text-[#162040] mt-1" id="jj-akreditasi">–</p>
            </div>
        </div>

        {{-- Rapor + Ringkasan SDM --}}
        <div class="grid grid-cols-1 xl:grid-cols-5 gap-4 mb-6">
            <div class="xl:col-span-3 bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-[#162040] mb-4">Rata-rata Skor Rapor Pendidikan</h3>
                <div class="relative" style="height: 180px;">
                    <canvas id="jenjangRaporChart"></canvas>
                </div>
            </div>

            <div class="xl:col-span-2 bg-white rounded-xl p-5 shadow-sm border border-gray-100">
                <h3 class="text-sm font-semibold text-[#162040] mb-4">Ringkasan SDM &amp; Keuangan Jenjang</h3>
                <div class="space-y-2.5 text-sm">
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Total Guru</span>
                        <span id="jj-sdm-guru" class="font-semibold text-[#162040]">-</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Guru Tetap (GTY)</span>
                        <span id="jj-sdm-gty" class="font-semibold text-[#162040]">-</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Guru Sertifikasi</span>
                        <span id="jj-sdm-sertifikasi" class="font-semibold text-[#162040]">-</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Total Karyawan</span>
                        <span id="jj-sdm-karyawan" class="font-semibold text-[#162040]">-</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Total Murid</span>
                        <span id="jj-sdm-murid" class="font-semibold text-[#162040]">-</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Murid (L/P)</span>
                        <span id="jj-sdm-lp" class="font-semibold text-[#162040]">-</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Rasio Murid/Guru</span>
                        <span id="jj-sdm-rasio" class="font-semibold text-[#162040]">-</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Total Luas Tanah</span>
                        <span id="jj-sdm-luas" class="font-semibold text-[#162040]">-</span>
                    </div>
                    <div class="flex items-center justify-between border-b border-gray-100 pb-2">
                        <span class="text-gray-500">Penerimaan BOS</span>
                        <span id="jj-sdm-bos" class="font-semibold text-[#162040]">-</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-500">Penerimaan BOP</span>
                        <span id="jj-sdm-bop" class="font-semibold text-[#162040]">-</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- School Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-[#162040]" id="jenjang-table-title">Daftar Sekolah</h3>
                <span class="text-xs text-gray-400" id="jenjang-table-count"></span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Sekolah</th>
                            <th class="text-left px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Kota</th>
                            <th class="text-left px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Provinsi</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Akreditasi</th>
                            <th class="text-left px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Kepsek</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Guru</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                GTY</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Sertifikasi</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Karyawan</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Murid</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Rombel</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Literasi</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Numerasi</th>
                        </tr>
                    </thead>
                    <tbody id="jenjang-table-body" class="divide-y divide-gray-50"></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== TAB: PER WILAYAH ===== --}}
    <div id="tab-per-wilayah" class="hidden">
        {{-- Provinsi Filter Buttons --}}
        <div class="flex flex-wrap gap-2 mb-6" id="wilayah-buttons"></div>

        {{-- Stats --}}
        <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Sekolah</p>
                <p class="text-2xl font-bold text-[#162040] mt-1" id="ww-sekolah">–</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Guru</p>
                <p class="text-2xl font-bold text-[#162040] mt-1" id="ww-guru">–</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Karyawan</p>
                <p class="text-2xl font-bold text-[#162040] mt-1" id="ww-karyawan">–</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Rombel</p>
                <p class="text-2xl font-bold text-[#162040] mt-1" id="ww-rombel">–</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wide">Total Murid</p>
                <p class="text-2xl font-bold text-[#162040] mt-1" id="ww-murid">–</p>
            </div>
        </div>

        {{-- Bar Chart: Sebaran per Provinsi --}}
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-6">
            <h3 class="text-sm font-semibold text-[#162040] mb-4" id="wilayah-chart-title">Sebaran Sekolah per Provinsi
            </h3>
            <div class="relative" style="height: 220px;">
                <canvas id="wilayahBarChart"></canvas>
            </div>
        </div>

        {{-- School Table --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-semibold text-[#162040]" id="wilayah-table-title">Daftar Sekolah</h3>
                <span class="text-xs text-gray-400" id="wilayah-table-count"></span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Sekolah</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Jenjang</th>
                            <th class="text-left px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Kota</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Guru</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Karyawan</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Murid</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Rombel</th>
                            <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                                Akreditasi</th>
                        </tr>
                    </thead>
                    <tbody id="wilayah-table-body" class="divide-y divide-gray-50"></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ---- Data from server ----
        const sekolahData = {!! json_encode(array_values($ringkasanSekolah)) !!};
        const jenjangColors = {
            KB: '#6b7280',
            TK: '#f59e0b',
            SD: '#3b82f6',
            SMP: '#22c55e',
            SMA: '#a855f7',
            SMK: '#f97316'
        };
        const formatNumber = (value) => new Intl.NumberFormat('id-ID').format(Number(value || 0));
        const formatCurrency = (value) => new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            maximumFractionDigits: 0
        }).format(Number(value || 0));

        // ---- Tab switching ----
        function switchTab(tab) {
            ['keseluruhan', 'per-jenjang', 'per-wilayah'].forEach(t => {
                document.getElementById('tab-' + t).classList.add('hidden');
                document.getElementById('tab-btn-' + t).className =
                    'px-4 py-1.5 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700 transition-all';
            });
            document.getElementById('tab-' + tab).classList.remove('hidden');
            document.getElementById('tab-btn-' + tab).className =
                'px-4 py-1.5 rounded-md text-sm font-semibold bg-white text-[#162040] shadow-sm transition-all';

            if (tab === 'per-jenjang' && !window._jenjangInit) {
                initPerJenjang();
                window._jenjangInit = true;
            }
            if (tab === 'per-wilayah' && !window._wilayahInit) {
                initPerWilayah();
                window._wilayahInit = true;
            }
        }

        // ---- CHART: Bar – Sekolah, Guru, Murid per Jenjang ----
        new Chart(document.getElementById('rekapJenjangChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($jenjangChartData['labels']) !!},
                datasets: [{
                        label: 'Sekolah',
                        data: {!! json_encode($jenjangChartData['sekolah']) !!},
                        backgroundColor: '#162040',
                        borderRadius: 4,
                        barThickness: 16
                    },
                    {
                        label: 'Guru',
                        data: {!! json_encode($jenjangChartData['guru']) !!},
                        backgroundColor: '#f59e0b',
                        borderRadius: 4,
                        barThickness: 16
                    },
                    {
                        label: 'Murid',
                        data: {!! json_encode($jenjangChartData['murid']) !!},
                        backgroundColor: '#10b981',
                        borderRadius: 4,
                        barThickness: 16
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 11
                            },
                            color: '#6b7280'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#162040',
                        titleColor: '#fff',
                        bodyColor: '#c5d5e8',
                        padding: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            color: '#f3f4f6'
                        },
                        border: {
                            display: false
                        }
                    },
                    x: {
                        ticks: {
                            color: '#9ca3af',
                            font: {
                                size: 11
                            }
                        },
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        }
                    }
                }
            }
        });

        // ---- CHART: Radar – Rata-rata Rapor ----
        const raporAvg = {!! json_encode($raporAvg) !!};
        new Chart(document.getElementById('raporRadarChart'), {
            type: 'radar',
            data: {
                labels: ['Literasi', 'Numerasi', 'Karakter', 'Kualitas PBM', 'Iklim Keamanan', 'Iklim Kebhinekaan'],
                datasets: [{
                    label: 'Rata-rata',
                    data: [
                        raporAvg.literasi || 0,
                        raporAvg.numerasi || 0,
                        raporAvg.karakter || 0,
                        raporAvg.kualitas_pbm || 0,
                        raporAvg.iklim_keamanan || 0,
                        raporAvg.iklim_kebhinekaan || 0,
                    ],
                    backgroundColor: 'rgba(22,32,64,0.1)',
                    borderColor: '#162040',
                    borderWidth: 2,
                    pointBackgroundColor: '#162040',
                    pointRadius: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#162040',
                        titleColor: '#fff',
                        bodyColor: '#c5d5e8',
                        callbacks: {
                            label: ctx => 'Rata-rata : ' + ctx.raw
                        }
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            display: false
                        },
                        grid: {
                            color: '#e5e7eb'
                        },
                        pointLabels: {
                            font: {
                                size: 11
                            },
                            color: '#6b7280'
                        }
                    }
                }
            }
        });

        // ======== PER JENJANG ========
        let jenjangRaporChartInst = null;
        let activeJenjang = null;

        function initPerJenjang() {
            const jenjangList = ['KB', 'TK', 'SD', 'SMP', 'SMA', 'SMK'];
            const counts = {};
            jenjangList.forEach(j => counts[j] = sekolahData.filter(s => s.jenjang === j).length);
            const available = jenjangList.filter(j => counts[j] > 0);
            const wrap = document.getElementById('jenjang-buttons');
            available.forEach(j => {
                const btn = document.createElement('button');
                btn.id = 'jbtn-' + j;
                btn.innerHTML = `${j} <span class="ml-1 font-normal text-xs opacity-70">${counts[j]}</span>`;
                btn.className = 'px-4 py-1.5 rounded-lg text-sm font-semibold border transition-all';
                btn.style.cssText = `background:#f9fafb;color:#374151;border-color:#e5e7eb`;
                btn.onclick = () => selectJenjang(j);
                wrap.appendChild(btn);
            });
            if (available.length > 0) selectJenjang(available[0]);
        }

        function selectJenjang(j) {
            activeJenjang = j;
            // Update button styles
            document.querySelectorAll('#jenjang-buttons button').forEach(b => {
                b.style.cssText = 'background:#f9fafb;color:#374151;border-color:#e5e7eb';
            });
            const active = document.getElementById('jbtn-' + j);
            if (active) active.style.cssText = `background:#162040;color:#fff;border-color:#162040`;

            const filtered = sekolahData.filter(s => s.jenjang === j);
            const totalGuru = filtered.reduce((a, s) => a + s.guru, 0);
            const totalGuruTetap = filtered.reduce((a, s) => a + s.guru_tetap, 0);
            const totalSertifikasi = filtered.reduce((a, s) => a + s.guru_sertifikasi, 0);
            const totalKaryawan = filtered.reduce((a, s) => a + s.karyawan, 0);
            const totalRombel = filtered.reduce((a, s) => a + s.rombel, 0);
            const totalMurid = filtered.reduce((a, s) => a + s.murid_total, 0);
            const totalMuridL = filtered.reduce((a, s) => a + s.murid_laki, 0);
            const totalMuridP = filtered.reduce((a, s) => a + s.murid_perempuan, 0);
            const totalLuas = filtered.reduce((a, s) => a + (Number(s.luas_tanah) || 0), 0);
            const totalBos = filtered.reduce((a, s) => a + (Number(s.penerimaan_bos) || 0), 0);
            const totalBop = filtered.reduce((a, s) => a + (Number(s.penerimaan_bop) || 0), 0);
            const rasioMuridGuru = totalGuru > 0 ? (totalMurid / totalGuru).toFixed(1) + ' : 1' : '-';
            const akreditasi = filtered.filter(s => s.akreditasi).length;

            document.getElementById('jj-sekolah').textContent = filtered.length;
            document.getElementById('jj-guru').textContent = totalGuru;
            document.getElementById('jj-rombel').textContent = totalRombel;
            document.getElementById('jj-murid').textContent = totalMurid;
            document.getElementById('jj-akreditasi').textContent = akreditasi + '/' + filtered.length;
            document.getElementById('jj-sdm-guru').textContent = totalGuru + ' orang';
            document.getElementById('jj-sdm-gty').textContent = totalGuruTetap + ' orang';
            document.getElementById('jj-sdm-sertifikasi').textContent = totalSertifikasi + ' orang';
            document.getElementById('jj-sdm-karyawan').textContent = totalKaryawan + ' orang';
            document.getElementById('jj-sdm-murid').textContent = totalMurid + ' murid';
            document.getElementById('jj-sdm-lp').textContent = totalMuridL + ' / ' + totalMuridP;
            document.getElementById('jj-sdm-rasio').textContent = rasioMuridGuru;
            document.getElementById('jj-sdm-luas').textContent = formatNumber(Math.round(totalLuas)) + ' m2';
            document.getElementById('jj-sdm-bos').textContent = totalBos + ' sekolah menerima';
            document.getElementById('jj-sdm-bop').textContent = totalBop + ' sekolah menerima';
            document.getElementById('jenjang-table-title').textContent = 'Sekolah Jenjang ' + j;
            document.getElementById('jenjang-table-count').textContent = filtered.length + ' sekolah';

            // Rapor averages
            const avg = (key) => {
                const vals = filtered.map(s => s.rapor[key]).filter(v => v !== null && v !== undefined);
                return vals.length ? (vals.reduce((a, b) => a + b, 0) / vals.length).toFixed(1) : 0;
            };
            const raporData = [
                parseFloat(avg('literasi')),
                parseFloat(avg('numerasi')),
                parseFloat(avg('karakter')),
                parseFloat(avg('kualitas_pbm')),
                parseFloat(avg('iklim_keamanan')),
                parseFloat(avg('iklim_kebhinekaan')),
            ];
            if (jenjangRaporChartInst) {
                jenjangRaporChartInst.destroy();
            }
            jenjangRaporChartInst = new Chart(document.getElementById('jenjangRaporChart'), {
                type: 'bar',
                data: {
                    labels: ['Literasi', 'Numerasi', 'Karakter', 'Kualitas PBM', 'Iklim Keamanan',
                        'Iklim Kebhinekaan'
                    ],
                    datasets: [{
                        label: 'Rata-rata Skor',
                        data: raporData,
                        backgroundColor: ['#3b82f6', '#22c55e', '#a855f7', '#0ea5e9', '#f59e0b', '#6366f1'],
                        borderRadius: 4,
                        barThickness: 16
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#162040'
                        }
                    },
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                color: '#9ca3af',
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: '#f3f4f6'
                            },
                            border: {
                                display: false
                            }
                        },
                        y: {
                            ticks: {
                                color: '#374151',
                                font: {
                                    size: 12
                                }
                            },
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Table
            const tbody = document.getElementById('jenjang-table-body');
            tbody.innerHTML = filtered.map(s => `
        <tr class="hover:bg-gray-50/50 transition-colors">
            <td class="px-5 py-3 font-medium text-gray-800">${s.name}</td>
            <td class="px-3 py-3 text-gray-500 text-xs">${s.kota || '–'}</td>
            <td class="px-3 py-3 text-gray-500 text-xs">${s.provinsi || '–'}</td>
            <td class="px-3 py-3 text-center text-gray-600 text-xs">${s.akreditasi ?? '–'}</td>
            <td class="px-3 py-3 text-gray-600 text-xs">${s.kepsek || '–'}</td>
            <td class="px-3 py-3 text-center text-gray-700 font-medium">${s.guru}</td>
            <td class="px-3 py-3 text-center text-gray-700">${s.guru_tetap}</td>
            <td class="px-3 py-3 text-center text-gray-700">${s.guru_sertifikasi}</td>
            <td class="px-3 py-3 text-center text-gray-700">${s.karyawan}</td>
            <td class="px-3 py-3 text-center text-gray-700">${s.murid_total}</td>
            <td class="px-3 py-3 text-center text-gray-700">${s.rombel}</td>
            <td class="px-3 py-3 text-center text-gray-700">${s.rapor.literasi ?? '–'}</td>
            <td class="px-3 py-3 text-center text-gray-700">${s.rapor.numerasi ?? '–'}</td>
        </tr>`).join('');
        }

        // ======== PER WILAYAH ========
        let wilayahBarChartInst = null;
        let activeWilayah = null;

        function initPerWilayah() {
            // Group by provinsi
            const provinsiMap = {};
            sekolahData.forEach(s => {
                const p = s.provinsi || 'Tidak diketahui';
                if (!provinsiMap[p]) provinsiMap[p] = [];
                provinsiMap[p].push(s);
            });
            const provinsiList = Object.keys(provinsiMap).sort();
            const counts = provinsiList.map(p => provinsiMap[p].length);

            // Bar chart
            wilayahBarChartInst = new Chart(document.getElementById('wilayahBarChart'), {
                type: 'bar',
                data: {
                    labels: provinsiList,
                    datasets: [{
                        label: 'Sekolah',
                        data: counts,
                        backgroundColor: '#162040',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#162040',
                            bodyColor: '#c5d5e8'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                color: '#9ca3af',
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: '#f3f4f6'
                            },
                            border: {
                                display: false
                            }
                        },
                        x: {
                            ticks: {
                                color: '#374151',
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                display: false
                            },
                            border: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Provinsi buttons
            const wrap = document.getElementById('wilayah-buttons');
            provinsiList.forEach((p, i) => {
                const btn = document.createElement('button');
                btn.id = 'wbtn-' + i;
                btn.dataset.provinsi = p;
                btn.innerHTML = `${p} <span class="ml-1 font-normal text-xs opacity-70">${counts[i]}</span>`;
                btn.className = 'px-4 py-1.5 rounded-lg text-sm font-semibold border transition-all';
                btn.style.cssText = 'background:#f9fafb;color:#374151;border-color:#e5e7eb';
                btn.onclick = () => selectWilayah(p, i);
                wrap.appendChild(btn);
            });
            if (provinsiList.length > 0) selectWilayah(provinsiList[0], 0);
        }

        function selectWilayah(provinsi, idx) {
            activeWilayah = provinsi;
            document.querySelectorAll('#wilayah-buttons button').forEach(b => {
                b.style.cssText = 'background:#f9fafb;color:#374151;border-color:#e5e7eb';
            });
            const active = document.querySelector(`#wilayah-buttons button[data-provinsi="${provinsi}"]`);
            if (active) active.style.cssText = 'background:#162040;color:#fff;border-color:#162040';

            const filtered = sekolahData.filter(s => s.provinsi === provinsi);
            const totalGuru = filtered.reduce((a, s) => a + s.guru, 0);
            const totalKaryawan = filtered.reduce((a, s) => a + s.karyawan, 0);
            const totalRombel = filtered.reduce((a, s) => a + s.rombel, 0);
            const totalMurid = filtered.reduce((a, s) => a + s.murid_total, 0);

            document.getElementById('ww-sekolah').textContent = filtered.length;
            document.getElementById('ww-guru').textContent = totalGuru;
            document.getElementById('ww-karyawan').textContent = totalKaryawan;
            document.getElementById('ww-rombel').textContent = totalRombel;
            document.getElementById('ww-murid').textContent = totalMurid;
            document.getElementById('wilayah-chart-title').textContent = 'Sebaran Sekolah per Provinsi - Fokus ' + provinsi;
            document.getElementById('wilayah-table-title').textContent = 'Sekolah di ' + provinsi;
            document.getElementById('wilayah-table-count').textContent = filtered.length + ' sekolah';

            const tbody = document.getElementById('wilayah-table-body');
            tbody.innerHTML = filtered.map(s => {
                const jc = {
                    KB: 'bg-gray-100 text-gray-600',
                    TK: 'bg-amber-100 text-amber-700',
                    SD: 'bg-blue-100 text-blue-700',
                    SMP: 'bg-green-100 text-green-700',
                    SMA: 'bg-purple-100 text-purple-700',
                    SMK: 'bg-orange-100 text-orange-700'
                } [s.jenjang] || 'bg-gray-100 text-gray-600';
                const kota = s.lokasi.split(', ')[0] || '–';
                return `<tr class="hover:bg-gray-50/50 transition-colors">
            <td class="px-5 py-3 font-medium text-gray-800">${s.name}</td>
            <td class="px-3 py-3 text-center"><span class="text-xs font-bold px-2 py-0.5 rounded-md ${jc}">${s.jenjang}</span></td>
            <td class="px-3 py-3 text-gray-500 text-xs">${kota}</td>
            <td class="px-3 py-3 text-center text-gray-700 font-medium">${s.guru}</td>
            <td class="px-3 py-3 text-center text-gray-700">${s.karyawan}</td>
            <td class="px-3 py-3 text-center text-gray-700">${s.murid_total}</td>
            <td class="px-3 py-3 text-center text-gray-700">${s.rombel}</td>
            <td class="px-3 py-3 text-center text-gray-600 text-xs">${s.akreditasi ?? '–'}</td>
        </tr>`;
            }).join('');
        }
    </script>
@endpush
