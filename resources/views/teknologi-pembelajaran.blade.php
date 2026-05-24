@extends('app-layout')

@section('title', 'Teknologi Pembelajaran')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#162040]">Teknologi Pembelajaran</h1>
        <p class="text-sm text-gray-500 mt-1">Pemanfaatan teknologi per sekolah</p>
    </div>

    {{-- Search & Filter --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input id="tk-search" type="text" placeholder="Cari sekolah..." oninput="tkFilter()"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] bg-white shadow-sm">
        </div>
        <div class="relative">
            <select id="tk-jenjang" onchange="tkFilter()"
                class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2.5 pr-9 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] cursor-pointer">
                <option value="">Semua Jenjang</option>
                <option value="KB">KB</option>
                <option value="TK">TK</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
                <option value="SMA">SMA</option>
                <option value="SMK">SMK</option>
            </select>
            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    {{-- School Accordion List --}}
    <div id="tk-list" class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-100">
        @forelse ($sekolahList as $sekolah)
            @php
                $tk = $sekolah->teknologiPembelajaran->first();
                $jenjang = $sekolah->jenjang;
                $badgeConfig = [
                    'KB' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                    'TK' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                    'SD' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                    'SMP' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                    'SMA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                    'SMK' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                ][$jenjang] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];

                $statusItems = [
                    'Software Pembelajaran' => $tk?->software_aplikasi_pembelajaran_status,
                    'LMS Kemendikdasmen' => $tk?->lms_kemendikdasmen_status,
                    'Smart Classroom' => $tk?->aplikasi_smart_classroom_status,
                    'Koleksi E-Book' => $tk?->koleksi_ebook_status,
                    'Website Sekolah' => $tk?->website_sekolah_status,
                    'Server Pembelajaran' => $tk?->server_pembelajaran_status,
                    'Tenaga IT' => $tk?->tenaga_khusus_it_status,
                ];

                $platformGroups = [
                    'LMS' => $tk?->platform_lms ?? [],
                    'Platform Pendidikan' => $tk?->platform_pendidikan ?? [],
                    'Alat Interaktif' => $tk?->alat_interaktif ?? [],
                    'Platform Komunikasi' => $tk?->platform_komunikasi ?? [],
                    'Aplikasi Manajemen' => $tk?->aplikasi_manajemen ?? [],
                    'Media Sosial' => $tk?->media_sosial ?? [],
                ];
            @endphp

            <div class="tk-row" data-name="{{ strtolower($sekolah->nama) }}"
                data-kota="{{ strtolower($sekolah->kota?->nama ?? '') }}" data-jenjang="{{ $jenjang }}">

                {{-- Accordion Header --}}
                <button type="button" onclick="tkToggle(this)"
                    class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50/70 transition-colors group text-left">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-xl {{ $badgeConfig['bg'] }} flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold {{ $badgeConfig['text'] }}">{{ $jenjang }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#162040] group-hover:text-amber-600 transition-colors">
                                {{ $sekolah->nama }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $sekolah->kota?->nama ?? '-' }}, {{ $sekolah->provinsi?->nama ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <svg class="tk-chevron w-4 h-4 text-gray-300 group-hover:text-amber-500 transition-all flex-shrink-0"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Accordion Body --}}
                <div class="tk-body hidden px-5 pb-5 pt-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        {{-- Left: Status Penggunaan --}}
                        <div>
                            <p class="text-xs font-semibold tracking-widest text-gray-400 uppercase mb-3">
                                Status Penggunaan</p>
                            <div class="space-y-2.5">
                                @foreach ($statusItems as $label => $val)
                                    @php
                                        $valLower = strtolower($val ?? '');
                                        if (!$val) {
                                            $icon = null;
                                            $color = 'text-gray-300';
                                        } elseif (str_contains($valLower, 'proses')) {
                                            $icon = 'clock';
                                            $color = 'text-amber-500';
                                        } elseif (str_contains($valLower, 'belum')) {
                                            $icon = 'x';
                                            $color = 'text-gray-400';
                                        } else {
                                            $icon = 'check';
                                            $color = 'text-green-500';
                                        }
                                    @endphp
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-sm text-gray-600">{{ $label }}</span>
                                        @if (!$val)
                                            <span class="text-sm text-gray-300">-</span>
                                        @elseif ($icon === 'clock')
                                            <span class="flex items-center gap-1 text-sm {{ $color }}">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $val }}
                                            </span>
                                        @elseif ($icon === 'x')
                                            <span class="flex items-center gap-1 text-sm {{ $color }}">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $val }}
                                            </span>
                                        @else
                                            <span class="flex items-center gap-1 text-sm {{ $color }}">
                                                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $val }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Right: Platform & Alat --}}
                        <div>
                            <p class="text-xs font-semibold tracking-widest text-gray-400 uppercase mb-3">
                                Platform &amp; Alat</p>
                            <div class="space-y-3">
                                @foreach ($platformGroups as $groupLabel => $items)
                                    @if (!empty($items))
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1.5">{{ $groupLabel }}</p>
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach ($items as $item)
                                                    <span
                                                        class="inline-block px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                                        {{ $item }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @if (collect($platformGroups)->filter(fn($i) => !empty($i))->isEmpty())
                                    <p class="text-sm text-gray-300">-</p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        @empty
            <div class="px-5 py-10 text-center text-sm text-gray-400">Tidak ada data sekolah.</div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script>
        function tkToggle(btn) {
            const row = btn.closest('.tk-row');
            const body = row.querySelector('.tk-body');
            const chev = btn.querySelector('.tk-chevron');
            const open = !body.classList.contains('hidden');

            // Close all open rows
            document.querySelectorAll('.tk-body').forEach(b => b.classList.add('hidden'));
            document.querySelectorAll('.tk-chevron').forEach(c => {
                c.style.transform = '';
                c.classList.remove('text-amber-500');
                c.classList.add('text-gray-300');
            });

            if (!open) {
                body.classList.remove('hidden');
                chev.style.transform = 'rotate(90deg)';
                chev.classList.remove('text-gray-300');
                chev.classList.add('text-amber-500');
            }
        }

        function tkFilter() {
            const search = document.getElementById('tk-search').value.toLowerCase().trim();
            const jenjang = document.getElementById('tk-jenjang').value;

            document.querySelectorAll('.tk-row').forEach(row => {
                const name = row.dataset.name || '';
                const kota = row.dataset.kota || '';
                const jenj = row.dataset.jenjang || '';

                const matchSearch = !search || name.includes(search) || kota.includes(search);
                const matchJenjang = !jenjang || jenj === jenjang;

                row.style.display = (matchSearch && matchJenjang) ? '' : 'none';
            });
        }
    </script>
@endpush
