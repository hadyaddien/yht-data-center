@extends('app-layout')

@section('title', 'Sarana Prasarana')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#162040]">Sarana Prasarana</h1>
        <p class="text-sm text-gray-500 mt-1">Kondisi sarana prasarana per sekolah</p>
    </div>

    {{-- Search & Filter --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input id="sp-search" type="text" placeholder="Cari sekolah..." oninput="spFilter()"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] bg-white shadow-sm">
        </div>
        <div class="relative">
            <select id="sp-jenjang" onchange="spFilter()"
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
    <div id="sp-list" class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-100">
        @forelse ($sekolahList as $sekolah)
            @php
                $sp = $sekolah->saranaPrasarana->first();
                $jenjang = $sekolah->jenjang;
                $luasTanah = $sekolah->luas_tanah;

                $badgeConfig = [
                    'KB' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                    'TK' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                    'SD' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                    'SMP' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                    'SMA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                    'SMK' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                ][$jenjang] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];

                // Map kondisi enum → percentage
                $kondisiMap = [
                    'baik' => 100,
                    'rusak_ringan' => 75,
                    'rusak_sedang' => 50,
                    'rusak_berat' => 25,
                ];

                // Facility items
                $facilities = [];
                if ($sp) {
                    if ($sp->jumlah_ruang_kelas > 0 && $sp->kondisi_ruang_kelas) {
                        $facilities[] = [
                            'label' => 'Ruang Kelas (' . $sp->jumlah_ruang_kelas . ' ruang)',
                            'ada' => true,
                            'pct' => $kondisiMap[$sp->kondisi_ruang_kelas] ?? null,
                        ];
                    }
                    if ($sp->memiliki_perpustakaan !== null) {
                        $facilities[] = [
                            'label' => 'Perpustakaan',
                            'ada' => (bool) $sp->memiliki_perpustakaan,
                            'pct' => $sp->memiliki_perpustakaan ? $kondisiMap[$sp->kondisi_perpustakaan] ?? null : null,
                        ];
                    }
                    if ($sp->memiliki_laboratorium !== null) {
                        $labLabel = 'Laboratorium';
                        if ($sp->jenis_laboratorium) {
                            $labs = array_map('trim', explode(',', $sp->jenis_laboratorium));
                            foreach ($labs as $lab) {
                                $facilities[] = ['label' => $lab, 'ada' => true, 'pct' => 100];
                            }
                        } else {
                            $facilities[] = ['label' => $labLabel, 'ada' => false, 'pct' => null];
                        }
                    }
                    if ($sp->memiliki_uks !== null) {
                        $facilities[] = [
                            'label' => 'UKS / Klinik Kesehatan',
                            'ada' => (bool) $sp->memiliki_uks,
                            'pct' => $sp->memiliki_uks ? $kondisiMap[$sp->kondisi_uks] ?? null : null,
                        ];
                    }
                    if ($sp->memiliki_lapangan !== null) {
                        $facilities[] = [
                            'label' => 'Lapangan Olahraga',
                            'ada' => (bool) $sp->memiliki_lapangan,
                            'pct' => $sp->memiliki_lapangan ? $kondisiMap[$sp->kondisi_lapangan] ?? null : null,
                        ];
                    }
                }

                $countAda = collect($facilities)->where('ada', true)->count();
                $countTidak = collect($facilities)->where('ada', false)->count();
                $skorRataRata = $sp?->skor_rata_rata ?? 0;
            @endphp

            <div class="sp-row" data-name="{{ strtolower($sekolah->nama) }}"
                data-kota="{{ strtolower($sekolah->kota?->nama ?? '') }}" data-jenjang="{{ $jenjang }}">

                {{-- Accordion Header --}}
                <button type="button" onclick="spToggle(this)"
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
                    <svg class="sp-chevron w-4 h-4 text-gray-300 group-hover:text-amber-500 transition-all flex-shrink-0"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Accordion Body --}}
                <div class="sp-body hidden px-5 pb-5">

                    {{-- Stat Cards --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                        <div class="border border-gray-100 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-[#162040]">{{ $countAda }}</p>
                            <p class="text-xs text-gray-400 mt-1">Fasilitas Ada</p>
                        </div>
                        <div class="border border-gray-100 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-[#162040]">{{ $countTidak }}</p>
                            <p class="text-xs text-gray-400 mt-1">Tidak Ada</p>
                        </div>
                        <div class="border border-gray-100 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-[#162040]">
                                {{ $skorRataRata > 0 ? number_format($skorRataRata, 0) . '%' : '0%' }}</p>
                            <p class="text-xs text-gray-400 mt-1">Rata-rata Kondisi</p>
                        </div>
                        <div class="border border-gray-100 rounded-xl p-4 text-center">
                            <p class="text-2xl font-bold text-[#162040]">
                                {{ $luasTanah ? number_format($luasTanah, 0, '.', '.') : '-' }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">Luas Tanah (m²)</p>
                        </div>
                    </div>

                    @if (!$sp || empty($facilities))
                        <p class="text-sm text-center text-blue-400 py-4">Belum ada data sarana prasarana</p>
                    @else
                        {{-- Facility List --}}
                        <div class="mb-5 space-y-2.5">
                            @foreach ($facilities as $fac)
                                @php
                                    $pct = $fac['pct'];
                                    if (!$fac['ada'] || $pct === null) {
                                        $barColor = 'bg-gray-200';
                                    } elseif ($pct >= 90) {
                                        $barColor = 'bg-blue-500';
                                    } elseif ($pct >= 70) {
                                        $barColor = 'bg-amber-400';
                                    } else {
                                        $barColor = 'bg-red-400';
                                    }
                                @endphp
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-2 w-56 flex-shrink-0">
                                        @if ($fac['ada'])
                                            <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300 flex-shrink-0" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                        <span
                                            class="text-sm {{ $fac['ada'] ? 'text-gray-700' : 'text-gray-400' }}">{{ $fac['label'] }}</span>
                                    </div>
                                    <div class="flex-1 flex items-center gap-3">
                                        <div class="flex-1 bg-gray-100 rounded-full h-1.5">
                                            <div class="{{ $barColor }} h-1.5 rounded-full transition-all"
                                                style="width: {{ $fac['ada'] && $pct ? $pct : 0 }}%"></div>
                                        </div>
                                        <span class="text-xs text-gray-400 w-10 text-right">
                                            {{ $fac['ada'] && $pct ? $pct . '%' : '-' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Lahan & Bangunan --}}
                    @if ($luasTanah || ($sp && $sp->luas_bangunan_m2))
                        <div class="border-t border-gray-100 pt-4 mt-2">
                            <p class="text-xs font-semibold tracking-widest text-gray-400 uppercase mb-3">Lahan &amp;
                                Bangunan</p>
                            <div class="flex flex-wrap gap-8">
                                @if ($luasTanah)
                                    <div>
                                        <p class="text-xs text-gray-400 mb-0.5">Luas Tanah</p>
                                        <p class="text-lg font-bold text-[#162040]">
                                            {{ number_format($luasTanah, 0, '.', '.') }} m²</p>
                                    </div>
                                @endif
                                @if ($sp && $sp->luas_bangunan_m2)
                                    <div>
                                        <p class="text-xs text-gray-400 mb-0.5">Luas Bangunan</p>
                                        <p class="text-lg font-bold text-[#162040]">
                                            {{ number_format($sp->luas_bangunan_m2, 0, '.', '.') }} m²</p>
                                    </div>
                                @endif
                                @if ($sp && $sp->status_kepemilikan === 'sewa')
                                    <div>
                                        <p class="text-xs text-gray-400 mb-0.5">Status</p>
                                        <p class="text-sm font-semibold text-amber-600">Sewa</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        @empty
            <div class="px-5 py-10 text-center text-sm text-gray-400">Tidak ada data sekolah.</div>
        @endforelse
    </div>
@endsection

@push('scripts')
    <script>
        function spToggle(btn) {
            const row = btn.closest('.sp-row');
            const body = row.querySelector('.sp-body');
            const chev = btn.querySelector('.sp-chevron');
            const open = !body.classList.contains('hidden');

            document.querySelectorAll('.sp-body').forEach(b => b.classList.add('hidden'));
            document.querySelectorAll('.sp-chevron').forEach(c => {
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

        function spFilter() {
            const search = document.getElementById('sp-search').value.toLowerCase().trim();
            const jenjang = document.getElementById('sp-jenjang').value;

            document.querySelectorAll('.sp-row').forEach(row => {
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
