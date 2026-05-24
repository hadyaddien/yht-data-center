@extends('app-layout')

@section('title', 'Program Pendidikan')

@section('content')
    @php
        $jenjangBadge = [
            'KB' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
            'TK' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
            'SD' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
            'SMP' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
            'SMA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
            'SMK' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
        ];

        $kebaharianBadge = [
            'Sudah berjalan' => 'bg-green-100 text-green-700',
            'Belum berjalan' => 'bg-gray-100 text-gray-500',
            'Tidak ada' => 'bg-gray-100 text-gray-500',
        ];

        $danaBadge = [
            'Menerima' => 'bg-green-100 text-green-700',
            'Belum menerima' => 'bg-amber-100 text-amber-700',
            'Tidak menerima' => 'bg-red-100 text-red-600',
        ];
    @endphp

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#162040]">Program Pendidikan</h1>
        <p class="text-sm text-gray-500 mt-1">Data program pendidikan per sekolah</p>
    </div>

    {{-- Search & Filter --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input type="text" id="pp-search" placeholder="Cari sekolah..." oninput="ppFilter()"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] bg-white shadow-sm">
        </div>
        <div class="relative">
            <select id="pp-jenjang" onchange="ppFilter()"
                class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2.5 pr-9 text-sm text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] cursor-pointer">
                <option value="">Semua Jenjang</option>
                @foreach (['KB', 'TK', 'SD', 'SMP', 'SMA', 'SMK'] as $j)
                    <option value="{{ $j }}">{{ $j }}</option>
                @endforeach
            </select>
            <svg class="w-4 h-4 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    {{-- School Accordion List --}}
    <div class="space-y-2" id="pp-list">

        @forelse ($sekolahList as $sekolah)
            @php
                $pp = $sekolah->programPendidikan->first();
                $badge = $jenjangBadge[$sekolah->jenjang] ?? $jenjangBadge['KB'];
                $lokasi = collect([$sekolah->kota?->nama, $sekolah->provinsi?->nama])
                    ->filter()
                    ->implode(', ');

                $pbdFields = [
                    'pbd_literasi' => 'Kemampuan Literasi',
                    'pbd_numerasi' => 'Kemampuan Numerasi',
                    'pbd_karakter' => 'Karakter',
                    'pbd_kualitas_pembelajaran' => 'Kualitas Pembelajaran',
                    'pbd_iklim_keamanan' => 'Iklim Keamanan',
                    'pbd_iklim_kebhinekaan' => 'Iklim Kebhinekaan',
                ];
            @endphp

            <div class="pp-card bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden"
                data-nama="{{ Str::lower($sekolah->nama) }}" data-jenjang="{{ $sekolah->jenjang }}">

                {{-- Header Row --}}
                <button type="button" onclick="ppToggle(this)"
                    class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50/60 transition-colors text-left group">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-10 h-10 rounded-xl {{ $badge['bg'] }} flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold {{ $badge['text'] }}">{{ $sekolah->jenjang }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#162040] group-hover:text-[#162040] leading-tight">
                                {{ $sekolah->nama }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $lokasi }}</p>
                        </div>
                    </div>
                    <svg class="pp-chevron w-4 h-4 text-gray-400 transition-transform duration-200 flex-shrink-0"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Expanded Content --}}
                <div class="pp-detail hidden border-t border-gray-100 px-5 py-5">

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                        {{-- ── LEFT: Rapor PBD ─────────────────────────── --}}
                        <div>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-4">
                                Rapor Pendidikan (Skor PBD)
                            </p>

                            @php
                                $hasPbd =
                                    $pp && collect(array_keys($pbdFields))->contains(fn($f) => $pp->{$f} !== null);
                            @endphp

                            @if ($hasPbd)
                                <div class="space-y-3">
                                    @foreach ($pbdFields as $field => $label)
                                        @if ($pp && $pp->{$field} !== null)
                                            @php
                                                $val = (float) $pp->{$field};
                                                $color = $val >= 80 ? 'bg-green-500' : 'bg-amber-400';
                                            @endphp
                                            <div>
                                                <div class="flex justify-between items-center mb-1">
                                                    <span class="text-xs text-gray-500">{{ $label }}</span>
                                                    <span class="text-xs font-semibold text-gray-700">
                                                        {{ rtrim(rtrim(number_format($val, 2), '0'), '.') }}
                                                    </span>
                                                </div>
                                                <div class="w-full bg-gray-100 rounded-full h-1.5">
                                                    <div class="{{ $color }} h-1.5 rounded-full transition-all"
                                                        style="width: {{ min($val, 100) }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p class="text-xs text-gray-400">Belum ada data rapor pendidikan.</p>
                            @endif
                        </div>

                        {{-- ── RIGHT: Kurikulum + Nilai Ujian + Sumber Dana ── --}}
                        <div class="space-y-5">

                            {{-- Kurikulum --}}
                            <div>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Kurikulum</p>
                                <div class="space-y-2">
                                    @if ($pp?->kurikulum)
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs text-gray-500">Kurikulum</span>
                                            <span
                                                class="text-xs font-medium bg-gray-100 text-gray-700 px-2.5 py-0.5 rounded-md whitespace-nowrap">
                                                {{ $pp->kurikulum }}
                                            </span>
                                        </div>
                                    @endif

                                    @if ($pp?->kurikulum_kebaharian)
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs text-gray-500">Kurikulum Kebaharian</span>
                                            <span
                                                class="text-xs font-medium px-2.5 py-0.5 rounded-md whitespace-nowrap
                                            {{ $kebaharianBadge[$pp->kurikulum_kebaharian] ?? 'bg-gray-100 text-gray-500' }}">
                                                {{ $pp->kurikulum_kebaharian }}
                                            </span>
                                        </div>
                                    @endif

                                    @if ($pp?->jumlah_guru_kebaharian !== null)
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs text-gray-500">Guru Kebaharian</span>
                                            <span class="text-xs font-medium text-gray-700">
                                                {{ $pp->jumlah_guru_kebaharian }} orang
                                            </span>
                                        </div>
                                    @endif

                                    @if (!$pp || (!$pp->kurikulum && !$pp->kurikulum_kebaharian))
                                        <p class="text-xs text-gray-400">Belum ada data kurikulum.</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Nilai Ujian Sekolah --}}
                            <div>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Nilai Ujian
                                    Sekolah</p>
                                <div class="space-y-2">
                                    @if ($pp?->nilai_ujian_ta1 !== null)
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs text-gray-500">TA 2024/2025</span>
                                            <span class="text-xs font-bold text-[#162040]">
                                                {{ rtrim(rtrim(number_format((float) $pp->nilai_ujian_ta1, 2), '0'), '.') }}
                                            </span>
                                        </div>
                                    @endif
                                    @if ($pp?->nilai_ujian_ta2 !== null)
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs text-gray-500">TA 2025/2026</span>
                                            <span class="text-xs font-bold text-[#162040]">
                                                {{ rtrim(rtrim(number_format((float) $pp->nilai_ujian_ta2, 2), '0'), '.') }}
                                            </span>
                                        </div>
                                    @endif
                                    @if (!$pp || ($pp->nilai_ujian_ta1 === null && $pp->nilai_ujian_ta2 === null))
                                        <p class="text-xs text-gray-400">–</p>
                                    @endif
                                </div>
                            </div>

                            {{-- Sumber Dana --}}
                            <div>
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Sumber Dana</p>
                                <div class="space-y-2">
                                    @if ($pp?->penerimaan_bos)
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs text-gray-500">BOS</span>
                                            <span
                                                class="text-xs font-medium px-2.5 py-0.5 rounded-md whitespace-nowrap
                                            {{ $danaBadge[$pp->penerimaan_bos] ?? 'bg-gray-100 text-gray-500' }}">
                                                {{ $pp->penerimaan_bos }}
                                            </span>
                                        </div>
                                    @endif
                                    @if ($pp?->penerimaan_bop)
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs text-gray-500">BOP</span>
                                            <span
                                                class="text-xs font-medium px-2.5 py-0.5 rounded-md whitespace-nowrap
                                            {{ $danaBadge[$pp->penerimaan_bop] ?? 'bg-gray-100 text-gray-500' }}">
                                                {{ $pp->penerimaan_bop }}
                                            </span>
                                        </div>
                                    @endif
                                    @if (!$pp || (!$pp->penerimaan_bos && !$pp->penerimaan_bop))
                                        <p class="text-xs text-gray-400">–</p>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ── PRESTASI SISWA ──────────────────────────────── --}}
                    <div class="mt-5 pt-5 border-t border-gray-100">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-4">Prestasi Siswa</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            {{-- Akademik --}}
                            <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-4">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-3">Akademik</p>
                                @php $hasAkad = false; @endphp
                                @foreach ([2025, 2026] as $thn)
                                    @php
                                        $chips = [];
                                        foreach (
                                            [
                                                'kota' => 'Kota/Kab',
                                                'provinsi' => 'Provinsi',
                                                'nasional' => 'Nasional',
                                                'internasional' => 'Internasional',
                                            ]
                                            as $key => $label
                                        ) {
                                            $v = $pp?->{"prestasi_akad_{$thn}_{$key}"};
                                            if ($v) {
                                                $chips[] = "{$label}: {$v}×";
                                            }
                                        }
                                        if (!empty($chips)) {
                                            $hasAkad = true;
                                        }
                                    @endphp
                                    @if (!empty($chips))
                                        <div class="mb-3 last:mb-0">
                                            <p class="text-[11px] text-gray-400 mb-1.5">Tahun {{ $thn }}</p>
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach ($chips as $chip)
                                                    <span
                                                        class="text-[11px] bg-white border border-gray-200 text-gray-600 px-2 py-0.5 rounded-md font-medium">
                                                        {{ $chip }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @if (!$hasAkad)
                                    <p class="text-xs text-gray-400">–</p>
                                @endif
                            </div>

                            {{-- Non Akademik --}}
                            <div class="rounded-xl bg-gray-50 border border-gray-100 px-4 py-4">
                                <p class="text-xs font-semibold text-gray-500 uppercase mb-3">Non Akademik</p>
                                @php $hasNon = false; @endphp
                                @foreach ([2025, 2026] as $thn)
                                    @php
                                        $chips = [];
                                        foreach (
                                            [
                                                'kota' => 'Kota/Kab',
                                                'provinsi' => 'Provinsi',
                                                'nasional' => 'Nasional',
                                                'internasional' => 'Internasional',
                                            ]
                                            as $key => $label
                                        ) {
                                            $v = $pp?->{"prestasi_non_{$thn}_{$key}"};
                                            if ($v) {
                                                $chips[] = "{$label}: {$v}×";
                                            }
                                        }
                                        if (!empty($chips)) {
                                            $hasNon = true;
                                        }
                                    @endphp
                                    @if (!empty($chips))
                                        <div class="mb-3 last:mb-0">
                                            <p class="text-[11px] text-gray-400 mb-1.5">Tahun {{ $thn }}</p>
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach ($chips as $chip)
                                                    <span
                                                        class="text-[11px] bg-white border border-gray-200 text-gray-600 px-2 py-0.5 rounded-md font-medium">
                                                        {{ $chip }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                                @if (!$hasNon)
                                    <p class="text-xs text-gray-400">–</p>
                                @endif
                            </div>

                        </div>
                    </div>
                    {{-- END PRESTASI --}}

                </div>
                {{-- END EXPANDED --}}
            </div>

        @empty
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 py-16 text-center">
                <p class="text-gray-400 text-sm">Tidak ada data sekolah.</p>
            </div>
        @endforelse

    </div>
    {{-- END LIST --}}

    @push('scripts')
        <script>
            function ppToggle(btn) {
                const card = btn.closest('.pp-card');
                const detail = card.querySelector('.pp-detail');
                const chevron = btn.querySelector('.pp-chevron');
                const isOpen = !detail.classList.contains('hidden');

                detail.classList.toggle('hidden');
                chevron.style.transform = isOpen ? '' : 'rotate(90deg)';
            }

            function ppFilter() {
                const search = document.getElementById('pp-search').value.toLowerCase().trim();
                const jenjang = document.getElementById('pp-jenjang').value;

                document.querySelectorAll('.pp-card').forEach(card => {
                    const matchNama = !search || card.dataset.nama.includes(search);
                    const matchJenjang = !jenjang || card.dataset.jenjang === jenjang;
                    card.style.display = (matchNama && matchJenjang) ? '' : 'none';
                });
            }
        </script>
    @endpush

@endsection
