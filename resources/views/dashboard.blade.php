@extends('app-layout')

@section('title', 'Dashboard')

@section('content')
    <div class="bg-white rounded-xl p-4 md:p-5 shadow-sm border border-gray-100 mb-4">
        <div class="flex items-center gap-4">
            <img src="{{ asset('images/logo-yht.png') }}" alt="Logo Yayasan Hang Tuah"
                class="w-14 h-14 md:w-16 md:h-16 rounded-full object-cover ring-1 ring-gray-200">

            <div class="min-w-0">
                <h1 class="text-2xl md:text-[34px] font-bold text-[#162040] leading-tight">Yayasan Hang Tuah</h1>
                <p class="text-sm md:text-[28px] text-gray-500 leading-tight mt-1">
                    Dashboard Instrumen Pendataan Satuan Pendidikan {{ date('Y') }}
                </p>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
        {{-- Total Sekolah --}}
        <div
            class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Total Sekolah</p>
                <p class="text-4xl font-bold text-[#162040] mt-1.5">{{ $stats['total_sekolah'] }}</p>
                <p class="text-xs text-gray-400 mt-1.5">Seluruh jenjang</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h1v11H4V10zm14 0h1v11h-1V10zm-7 0h1v11h-1V10z" />
                </svg>
            </div>
        </div>

        {{-- Total Guru --}}
        <div
            class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Total Guru</p>
                <p class="text-4xl font-bold text-[#162040] mt-1.5">{{ $stats['total_guru'] }}</p>
                <p class="text-xs text-gray-400 mt-1.5">Tenaga pendidik</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        {{-- Total Murid --}}
        <div
            class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Total Murid</p>
                <p class="text-4xl font-bold text-[#162040] mt-1.5">{{ $stats['total_murid'] }}</p>
                <p class="text-xs text-gray-400 mt-1.5">L: {{ $stats['murid_laki'] }} | P: {{ $stats['murid_perempuan'] }}
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-rose-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
        </div>

        {{-- Terakreditasi --}}
        <div
            class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Terakreditasi</p>
                <p class="text-4xl font-bold text-[#162040] mt-1.5">{{ $stats['terakreditasi'] }}</p>
                <p class="text-xs text-gray-400 mt-1.5">Sekolah</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-teal-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
        </div>

        {{-- Rata-rata Sarpras --}}
        <div
            class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
            <div>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Rata-rata Sarpras</p>
                <p class="text-4xl font-bold text-[#162040] mt-1.5">{{ $stats['rata_sarpras'] }}%</p>
                <p class="text-xs text-gray-400 mt-1.5">Kondisi kelayakan</p>
            </div>
            <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
        </div>
    </div>

    
    {{-- Top Cards: Jenjang, Wilayah, Akreditasi --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-4 h-4 text-[#162040]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M12 14l6.16-3.422A12.083 12.083 0 0120 17.5c0 1.328-3.582 2.5-8 2.5s-8-1.172-8-2.5a12.083 12.083 0 011.84-6.922L12 14z" />
                </svg>
                <h2 class="text-sm font-semibold text-[#162040]">Jumlah Sekolah per Jenjang</h2>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                @foreach ($jenjangCards as $item)
                    <div class="rounded-xl border border-gray-100 bg-gray-50/70 px-3 py-4 text-center">
                        <p class="text-4xl font-bold leading-none text-[#162040]">{{ $item['count'] }}</p>
                        <span
                            class="mt-3 inline-flex items-center justify-center rounded-full px-3 py-1 text-xs font-bold {{ $item['badge'] }}">
                            {{ $item['label'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-4 h-4 text-[#162040]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <h2 class="text-sm font-semibold text-[#162040]">Jumlah Sekolah per Wilayah</h2>
            </div>

            <div class="space-y-3">
                @forelse ($wilayahSummary as $item)
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-600 truncate pr-3">{{ $item['label'] }}</span>
                            <span class="font-semibold text-[#162040]">{{ $item['count'] }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full bg-[#162040]" style="width: {{ min(100, $item['percent']) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada data wilayah.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h2 class="text-sm font-semibold text-[#162040] mb-4">Status Akreditasi</h2>
            <div class="space-y-3">
                @forelse ($akreditasiSummary as $item)
                    <div>
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-gray-600">{{ $item['label'] }}</span>
                            <span class="font-semibold text-[#162040]">{{ $item['count'] }}</span>
                        </div>
                        <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full bg-[#f5b301]" style="width: {{ min(100, $item['percent']) }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400">Belum ada data akreditasi.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 xl:grid-cols-5 gap-4 mb-6">
        {{-- Bar Chart --}}
        <div class="xl:col-span-3 bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h2 class="text-sm font-semibold text-[#162040] mb-4">Jumlah Sekolah per Jenjang</h2>
            <div class="relative" style="height: 220px;">
                <canvas id="jenjangChart"></canvas>
            </div>
        </div>

        {{-- Donut Chart --}}
        <div class="xl:col-span-2 bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <h2 class="text-sm font-semibold text-[#162040] mb-4">Sebaran per Provinsi</h2>
            <div class="flex flex-col items-center">
                <div class="relative" style="height: 180px; width: 180px;">
                    <canvas id="provinsiChart"></canvas>
                </div>
                <div class="mt-3 flex flex-wrap gap-x-4 gap-y-1.5 justify-center">
                    @foreach ($provinsiData['labels'] as $i => $label)
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full flex-shrink-0"
                                style="background-color: {{ $provinsiData['colors'][$i] }}"></span>
                            <span class="text-xs text-gray-500">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- SDM Murid Summary --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 mb-6">
        <h2 class="text-sm font-semibold text-[#162040] mb-4">Komposisi Murid dan Pekerjaan Orang Tua</h2>
        @php
            $muridTotal = (int) ($stats['total_murid'] ?? 0);
            $persenLaki = $muridTotal > 0 ? round(((int) $stats['murid_laki'] / $muridTotal) * 100, 1) : 0;
            $persenPerempuan = $muridTotal > 0 ? round(((int) $stats['murid_perempuan'] / $muridTotal) * 100, 1) : 0;
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="rounded-xl border border-gray-100 p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Distribusi Murid</p>
                <div class="space-y-3">
                    <div>
                        <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                            <span>Laki-laki</span>
                            <span>{{ $stats['murid_laki'] }} ({{ $persenLaki }}%)</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full bg-blue-500" style="width: {{ $persenLaki }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                            <span>Perempuan</span>
                            <span>{{ $stats['murid_perempuan'] }} ({{ $persenPerempuan }}%)</span>
                        </div>
                        <div class="h-2.5 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full bg-rose-500" style="width: {{ $persenPerempuan }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-100 p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Top Pekerjaan Orang Tua</p>
                <div class="space-y-2.5">
                    @forelse($komposisiOrtu as $item)
                        <div>
                            <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                <span>{{ $item['label'] }}</span>
                                <span>{{ $item['count'] }} ({{ $item['persen'] }}%)</span>
                            </div>
                            <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full bg-[#162040]" style="width: {{ min(100, $item['persen']) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-gray-400">Belum ada data komposisi orang tua.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Updates --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
            <h2 class="text-sm font-semibold text-[#162040]">Data Terakhir Diperbarui</h2>
            <a href="#" class="text-xs font-medium text-[#162040] hover:text-amber-600 transition-colors">Lihat
                Semua →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @foreach ($recentSchools as $school)
                <div
                    class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50/70 transition-colors cursor-pointer">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $school['name'] }}</p>
                        <div class="flex items-center gap-1 mt-0.5">
                            <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p class="text-xs text-gray-400">{{ $school['location'] }}</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold px-2.5 py-1 rounded-md {{ $school['color'] }}">
                        {{ $school['jenjang'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const jenjangCtx = document.getElementById('jenjangChart').getContext('2d');
        new Chart(jenjangCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($jenjangData['labels']) !!},
                datasets: [{
                    data: {!! json_encode($jenjangData['values']) !!},
                    backgroundColor: '#162040',
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 30,
                    hoverBackgroundColor: '#f59e0b',
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
                        padding: 10,
                        callbacks: {
                            label: ctx => ` ${ctx.raw} Sekolah`
                        }
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

        const provinsiCtx = document.getElementById('provinsiChart').getContext('2d');
        new Chart(provinsiCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($provinsiData['labels']) !!},
                datasets: [{
                    data: {!! json_encode($provinsiData['values']) !!},
                    backgroundColor: {!! json_encode($provinsiData['colors']) !!},
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverBorderColor: '#ffffff',
                    hoverOffset: 4,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '68%',
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#162040',
                        titleColor: '#fff',
                        bodyColor: '#c5d5e8',
                        padding: 10,
                        callbacks: {
                            label: ctx => ` ${ctx.label}: ${ctx.raw} sekolah`
                        }
                    }
                }
            }
        });
    </script>
@endpush
