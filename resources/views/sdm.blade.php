@extends('app-layout')

@section('title', 'SDM')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#162040]">SDM</h1>
        <p class="text-sm text-gray-500 mt-1">Tenaga pendidik dan kependidikan per sekolah</p>
    </div>

    {{-- Search & Filter --}}
    <div class="flex flex-col sm:flex-row gap-3 mb-5">
        <div class="relative flex-1">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input id="sdm-search" type="text" placeholder="Cari sekolah..." oninput="sdmFilter()"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] bg-white shadow-sm">
        </div>
        <div class="relative">
            <select id="sdm-jenjang" onchange="sdmFilter()"
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
    <div id="sdm-list" class="bg-white rounded-xl shadow-sm border border-gray-100 divide-y divide-gray-100">
        @forelse ($sekolahList as $sekolah)
            @php
                $sdm = $sekolah->sdm->first();
                $guru = $sekolah->sdmGuru;
                $jenjang = $sekolah->jenjang;
                $bodyOpen = auth()->user()->isKepalaSekolah();

                $badgeConfig = [
                    'KB' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'],
                    'TK' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                    'SD' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                    'SMP' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                    'SMA' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                    'SMK' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700'],
                ][$jenjang] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];

                // Derived totals — gunakan field baru, fallback ke legacy
                $totalGuru = $sdm ? $sdm->jumlah_guru ?? $sdm->guru_pns + $sdm->guru_honorer + $sdm->guru_p3k : 0;
                $guruTetap = $sdm ? $sdm->guru_tetap_yayasan ?? $sdm->guru_pns + $sdm->guru_p3k : 0;
                $guruTidakTetap = $sdm ? $sdm->guru_tidak_tetap ?? $sdm->guru_honorer : 0;
                $guruSertif = $sdm ? $sdm->guru_sertifikasi ?? $sdm->guru_bersertifikasi : 0;
                $totalKary = $sdm
                    ? $sdm->jumlah_karyawan ?? $sdm->karyawan_pns + $sdm->karyawan_honorer + $sdm->karyawan_p3k
                    : 0;
                $karyTetap = $sdm ? $sdm->karyawan_tetap ?? $sdm->karyawan_pns + $sdm->karyawan_p3k : 0;
                $karyTidakTetap = $sdm ? $sdm->karyawan_tidak_tetap ?? $sdm->karyawan_honorer : 0;
                $jumlahRombel = $sdm ? $sdm->jumlah_rombel : 0;

                // Kualifikasi aggregate dari field baru
                $kualifikasiAggregate = [];
                if (
                    $sdm &&
                    ($sdm->guru_s3 !== null ||
                        $sdm->guru_s2 !== null ||
                        $sdm->guru_s1_pendidikan !== null ||
                        $sdm->guru_s1_non_pendidikan !== null)
                ) {
                    if ($sdm->guru_s3 > 0) {
                        $kualifikasiAggregate['S3'] = $sdm->guru_s3;
                    }
                    if ($sdm->guru_s2 > 0) {
                        $kualifikasiAggregate['S2'] = $sdm->guru_s2;
                    }
                    if ($sdm->guru_s1_pendidikan > 0) {
                        $kualifikasiAggregate['S1 Pendidikan'] = $sdm->guru_s1_pendidikan;
                    }
                    if ($sdm->guru_s1_non_pendidikan > 0) {
                        $kualifikasiAggregate['S1 Non-Pend.'] = $sdm->guru_s1_non_pendidikan;
                    }
                }

                // Kualifikasi dari sdmGuru (fallback jika aggregate belum diisi)
                $kualifikasi = $guru->groupBy('kualifikasi')->map->count();

                $muridTotal = $sdm ? $sdm->jumlah_murid_total : 0;
                $muridLaki = $sdm ? $sdm->jumlah_murid_laki : 0;
                $muridPerempuan = $sdm ? $sdm->jumlah_murid_perempuan : 0;
                $ortuCounts = [
                    'TNI AL' => $sdm?->murid_ortu_tni_al ?? 0,
                    'TNI' => $sdm?->murid_ortu_tni ?? 0,
                    'POLISI' => $sdm?->murid_ortu_polisi ?? 0,
                    'PNS' => $sdm?->murid_ortu_pns ?? 0,
                    'Pengusaha' => $sdm?->murid_ortu_pengusaha ?? 0,
                    'Wiraswasta' => $sdm?->murid_ortu_wiraswasta ?? 0,
                    'Buruh' => $sdm?->murid_ortu_buruh ?? 0,
                    'Guru' => $sdm?->murid_ortu_guru ?? 0,
                ];
                $ortuLainJumlah = $sdm?->murid_ortu_lainnya_jumlah ?? 0;

                // Kualifikasi from sdm_guru records
                $kualifikasi = $guru->groupBy('kualifikasi')->map->count();
            @endphp

            <div class="sdm-row" data-name="{{ strtolower($sekolah->nama) }}"
                data-kota="{{ strtolower($sekolah->kota?->nama ?? '') }}" data-jenjang="{{ $jenjang }}">

                {{-- Accordion Header --}}
                <button type="button" onclick="sdmToggle(this)"
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
                    <svg class="sdm-chevron w-4 h-4 group-hover:text-amber-500 transition-all flex-shrink-0"
                        @if ($bodyOpen) style="transform: rotate(90deg)" @endif fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>

                {{-- Accordion Body --}}
                <div class="sdm-body {{ $bodyOpen ? '' : 'hidden' }} px-5 pb-5">

                    {{-- Stat Cards Row 1: Guru --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-3">
                        <div class="border border-gray-100 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Total Guru</p>
                            <p class="text-xl font-bold text-[#162040]">{{ $totalGuru }}
                                <span class="text-xs font-normal text-blue-400">orang</span>
                            </p>
                        </div>
                        <div class="border border-gray-100 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Guru Tetap (GTY)</p>
                            <p class="text-xl font-bold text-[#162040]">{{ $guruTetap }}
                                <span class="text-xs font-normal text-blue-400">orang</span>
                            </p>
                        </div>
                        <div class="border border-gray-100 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Guru Tidak Tetap (GTT)</p>
                            <p class="text-xl font-bold text-[#162040]">{{ $guruTidakTetap }}
                                <span class="text-xs font-normal text-blue-400">orang</span>
                            </p>
                        </div>
                        <div class="border border-gray-100 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Guru Sertifikasi</p>
                            <p class="text-xl font-bold text-[#162040]">{{ $guruSertif }}
                                <span class="text-xs font-normal text-blue-400">orang</span>
                            </p>
                        </div>
                    </div>

                    {{-- Stat Cards Row 2: Karyawan + Rombel --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
                        <div class="border border-gray-100 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Total Karyawan</p>
                            <p class="text-xl font-bold text-[#162040]">{{ $totalKary }}
                                <span class="text-xs font-normal text-blue-400">orang</span>
                            </p>
                        </div>
                        <div class="border border-gray-100 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Karyawan Tetap (KTY)</p>
                            <p class="text-xl font-bold text-[#162040]">{{ $karyTetap }}
                                <span class="text-xs font-normal text-blue-400">orang</span>
                            </p>
                        </div>
                        <div class="border border-gray-100 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Karyawan Tidak Tetap</p>
                            <p class="text-xl font-bold text-[#162040]">{{ $karyTidakTetap }}
                                <span class="text-xs font-normal text-blue-400">orang</span>
                            </p>
                        </div>
                        <div class="border border-gray-100 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-1">Rombongan Belajar</p>
                            <p class="text-xl font-bold text-[#162040]">{{ $jumlahRombel }}
                                <span class="text-xs font-normal text-blue-400">kelas</span>
                            </p>
                        </div>
                    </div>

                    {{-- Kualifikasi & Penghasilan --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-4">

                        {{-- Kualifikasi Guru --}}
                        <div>
                            <p class="text-xs font-semibold tracking-widest text-gray-400 uppercase mb-3">
                                Kualifikasi Pendidikan Guru</p>
                            @if (!empty($kualifikasiAggregate))
                                <div class="space-y-2">
                                    @foreach ($kualifikasiAggregate as $level => $jumlah)
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-blue-500">{{ $level }}</span>
                                            <span class="text-sm font-medium text-gray-700">{{ $jumlah }}
                                                orang</span>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif ($kualifikasi->isNotEmpty())
                                <div class="space-y-2">
                                    @foreach (['S3', 'S2', 'S1', 'D3', 'SMA'] as $level)
                                        @if (isset($kualifikasi[$level]))
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm text-blue-500">{{ $level }}</span>
                                                <span class="text-sm font-medium text-gray-700">
                                                    {{ $kualifikasi[$level] }} orang
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p class="text-sm text-gray-300">Belum ada data</p>
                            @endif
                        </div>

                        {{-- Penghasilan & Jabatan --}}
                        <div>
                            <p class="text-xs font-semibold tracking-widest text-gray-400 uppercase mb-3">
                                Penghasilan &amp; Jabatan</p>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Rata-rata Gaji Guru</span>
                                    <span class="text-sm font-medium text-gray-700">
                                        @if ($sdm?->rata_gaji_guru)
                                            Rp {{ number_format($sdm->rata_gaji_guru, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Rata-rata Gaji Karyawan</span>
                                    <span class="text-sm font-medium text-gray-700">
                                        @if ($sdm?->rata_gaji_karyawan)
                                            Rp {{ number_format($sdm->rata_gaji_karyawan, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500">Masa Jabatan Kepsek</span>
                                    <span class="text-sm font-medium text-gray-700">
                                        @if ($sdm?->masa_jabatan_kepsek)
                                            {{ $sdm->masa_jabatan_kepsek }} tahun
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                                <div class="flex items-center justify-between border-t border-gray-100 pt-2">
                                    <span class="text-sm text-gray-500">Nama Kepala Sekolah</span>
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $sekolah->kepala_sekolah_nama ?? '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Data Siswa --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-4">

                        <div>
                            <p class="text-xs font-semibold tracking-widest text-gray-400 uppercase mb-3">Data Siswa</p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="border border-gray-100 rounded-xl p-4">
                                    <p class="text-xs text-gray-400 mb-1">Jumlah Murid</p>
                                    <p class="text-xl font-bold text-[#162040]">{{ $muridTotal }}
                                        <span class="text-xs font-normal text-blue-400">orang</span>
                                    </p>
                                </div>
                                <div class="border border-gray-100 rounded-xl p-4">
                                    <p class="text-xs text-gray-400 mb-1">Murid Laki-laki</p>
                                    <p class="text-xl font-bold text-[#162040]">{{ $muridLaki }}
                                        <span class="text-xs font-normal text-blue-400">orang</span>
                                    </p>
                                </div>
                                <div class="border border-gray-100 rounded-xl p-4">
                                    <p class="text-xs text-gray-400 mb-1">Murid Perempuan</p>
                                    <p class="text-xl font-bold text-[#162040]">{{ $muridPerempuan }}
                                        <span class="text-xs font-normal text-blue-400">orang</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <p class="text-xs font-semibold tracking-widest text-gray-400 uppercase mb-3">Pekerjaan Orang
                                Tua</p>
                            @if (!$sdm)
                                <p class="text-sm text-gray-300">-</p>
                            @else
                                <div class="space-y-2">
                                    @foreach ($ortuCounts as $label => $value)
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-500">{{ $label }}</span>
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ $value }} murid
                                            </span>
                                        </div>
                                    @endforeach
                                    @if ($ortuLainJumlah > 0)
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-500">Lainnya</span>
                                            <span class="text-sm font-medium text-gray-700">
                                                {{ $ortuLainJumlah }} murid
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                    </div>

                    {{-- Hambatan & Catatan --}}
                    @php $hambatan = $sdm?->hambatan_tantangan ?? $sdm?->catatan_hambatan; @endphp
                    @if ($hambatan)
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                            <p class="text-xs font-semibold text-amber-600 mb-1">Hambatan &amp; Catatan</p>
                            <p class="text-sm text-amber-800">{{ $hambatan }}</p>
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
        function sdmToggle(btn) {
            const row = btn.closest('.sdm-row');
            const body = row.querySelector('.sdm-body');
            const chev = btn.querySelector('.sdm-chevron');
            const open = !body.classList.contains('hidden');

            document.querySelectorAll('.sdm-body').forEach(b => b.classList.add('hidden'));
            document.querySelectorAll('.sdm-chevron').forEach(c => {
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

        function sdmFilter() {
            const search = document.getElementById('sdm-search').value.toLowerCase().trim();
            const jenjang = document.getElementById('sdm-jenjang').value;

            document.querySelectorAll('.sdm-row').forEach(row => {
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
