@extends('app-layout')

@section('title', 'Rekap & Analisis')

@section('content')
    <div class="flex items-start justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#162040]">Rekap &amp; Analisis</h1>
            <p class="text-sm text-gray-500 mt-1">Laporan komprehensif untuk pimpinan Yayasan Hang Tuah</p>
        </div>
        <button
            class="flex items-center gap-2 border border-gray-200 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-lg transition-colors shadow-sm">
            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak / Simpan PDF
        </button>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-1 mb-6 bg-gray-100 p-1 rounded-lg w-fit">
        <button
            class="px-4 py-1.5 rounded-md text-sm font-semibold bg-white text-[#162040] shadow-sm transition-all">Keseluruhan</button>
        <button class="px-4 py-1.5 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700 transition-all">Per
            Jenjang</button>
        <button class="px-4 py-1.5 rounded-md text-sm font-medium text-gray-500 hover:text-gray-700 transition-all">Per
            Wilayah</button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-4 mb-6">
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
            <h3 class="text-sm font-semibold text-[#162040] mb-4">Jumlah Sekolah &amp; Guru per Jenjang</h3>
            <div class="relative" style="height: 220px;">
                <canvas id="rekapJenjangChart"></canvas>
            </div>
        </div>
        <div class="xl:col-span-2 bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h3 class="text-sm font-semibold text-[#162040] mb-4">Rata-rata Skor Rapor Pendidikan</h3>
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
                <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
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

                    <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-gray-100">
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
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Sekolah
                        </th>
                        <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            Jenjang</th>
                        <th class="text-left px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            Provinsi</th>
                        <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            Akreditasi</th>
                        <th class="text-center px-3 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Guru
                        </th>
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
                            <td class="px-3 py-3 text-gray-600">{{ explode(', ', $s['lokasi'])[1] ?? '' }}</td>
                            <td class="px-3 py-3 text-center text-gray-600 text-xs">{{ $s['akreditasi'] ?? '–' }}</td>
                            <td class="px-3 py-3 text-center text-gray-700 font-medium">{{ $s['guru'] }}</td>
                            <td class="px-3 py-3 text-center text-gray-700 font-medium">{{ $s['rombel'] }}</td>
                            <td class="px-3 py-3 text-center text-gray-700">{{ $s['rapor']['literasi'] ?? '–' }}</td>
                            <td class="px-3 py-3 text-center text-gray-700">{{ $s['rapor']['numerasi'] ?? '–' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Bar chart: Sekolah & Guru per Jenjang
        new Chart(document.getElementById('rekapJenjangChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode($jenjangChartData['labels']) !!},
                datasets: [{
                        label: 'Sekolah',
                        data: {!! json_encode($jenjangChartData['sekolah']) !!},
                        backgroundColor: '#162040',
                        borderRadius: 4,
                        barThickness: 16,
                    },
                    {
                        label: 'Guru',
                        data: {!! json_encode($jenjangChartData['guru']) !!},
                        backgroundColor: '#f59e0b',
                        borderRadius: 4,
                        barThickness: 16,
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
                        padding: 10,
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

        // Radar chart: Rapor Pendidikan
        new Chart(document.getElementById('raporRadarChart'), {
            type: 'radar',
            data: {
                labels: ['Literasi', 'Numerasi', 'Karakter', 'Kualitas PBM', 'Iklim Keamanan', 'Ilmu Kebhinekaan'],
                datasets: [{
                    label: 'Rata-rata',
                    data: [89.6, 85.2, 69.5, 75, 80, 72],
                    backgroundColor: 'rgba(22, 32, 64, 0.1)',
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
                                size: 10
                            },
                            color: '#6b7280'
                        }
                    }
                }
            }
        });
    </script>
@endpush
