@extends('app-layout')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-[#162040]">Dashboard</h1>
    <p class="text-sm text-gray-500 mt-1">
        Ringkasan data satuan pendidikan Yayasan <span class="text-[#162040] font-semibold">Hang Tuah</span> se-Indonesia
    </p>
</div>

{{-- Stats Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    {{-- Total Sekolah --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Total Sekolah</p>
            <p class="text-4xl font-bold text-[#162040] mt-1.5">{{ $stats['total_sekolah'] }}</p>
            <p class="text-xs text-gray-400 mt-1.5">Seluruh jenjang</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 21h18M3 10h18M3 7l9-4 9 4M4 10h1v11H4V10zm14 0h1v11h-1V10zm-7 0h1v11h-1V10z"/>
            </svg>
        </div>
    </div>

    {{-- Total Guru --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Total Guru</p>
            <p class="text-4xl font-bold text-[#162040] mt-1.5">{{ $stats['total_guru'] }}</p>
            <p class="text-xs text-gray-400 mt-1.5">Tenaga pendidik</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
    </div>

    {{-- Terakreditasi --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Terakreditasi</p>
            <p class="text-4xl font-bold text-[#162040] mt-1.5">{{ $stats['terakreditasi'] }}</p>
            <p class="text-xs text-gray-400 mt-1.5">Sekolah</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-teal-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
        </div>
    </div>

    {{-- Rata-rata Sarpras --}}
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100 flex items-center justify-between hover:shadow-md transition-shadow">
        <div>
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Rata-rata Sarpras</p>
            <p class="text-4xl font-bold text-[#162040] mt-1.5">{{ $stats['rata_sarpras'] }}%</p>
            <p class="text-xs text-gray-400 mt-1.5">Kondisi kelayakan</p>
        </div>
        <div class="w-12 h-12 rounded-full bg-purple-50 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
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
                @foreach($provinsiData['labels'] as $i => $label)
                <div class="flex items-center gap-1.5">
                    <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background-color: {{ $provinsiData['colors'][$i] }}"></span>
                    <span class="text-xs text-gray-500">{{ $label }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Recent Updates --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <h2 class="text-sm font-semibold text-[#162040]">Data Terakhir Diperbarui</h2>
        <a href="#" class="text-xs font-medium text-[#162040] hover:text-amber-600 transition-colors">Lihat Semua →</a>
    </div>
    <div class="divide-y divide-gray-50">
        @foreach($recentSchools as $school)
        <div class="flex items-center justify-between px-5 py-3.5 hover:bg-gray-50/70 transition-colors cursor-pointer">
            <div>
                <p class="text-sm font-medium text-gray-800">{{ $school['name'] }}</p>
                <div class="flex items-center gap-1 mt-0.5">
                    <svg class="w-3 h-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
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
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#162040',
                    titleColor: '#fff',
                    bodyColor: '#c5d5e8',
                    padding: 10,
                    callbacks: { label: ctx => ` ${ctx.raw} Sekolah` }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1, color: '#9ca3af', font: { size: 11 } },
                    grid: { color: '#f3f4f6' },
                    border: { display: false }
                },
                x: {
                    ticks: { color: '#9ca3af', font: { size: 11 } },
                    grid: { display: false },
                    border: { display: false }
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
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#162040',
                    titleColor: '#fff',
                    bodyColor: '#c5d5e8',
                    padding: 10,
                    callbacks: { label: ctx => ` ${ctx.label}: ${ctx.raw} sekolah` }
                }
            }
        }
    });
</script>
@endpush
