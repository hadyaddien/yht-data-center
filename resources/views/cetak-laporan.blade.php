@extends('app-layout')
@section('title', 'Cetak Laporan')

@section('content')

    {{-- Print-only styles --}}
    <style>
        @media print {

            aside,
            header,
            .screen-content {
                display: none !important;
            }

            #main-content {
                margin-left: 0 !important;
            }

            #print-area {
                display: block !important;
            }

            .print-school.hidden-print,
            .print-section.hidden-print {
                display: none !important;
            }

            @page {
                margin: 1.5cm 1.5cm 1.5cm 1.5cm;
                size: A4;
            }

            body {
                font-family: Arial, sans-serif;
                font-size: 9.5pt;
                color: #111;
            }

            #print-area {
                padding: 0;
            }

            .print-doc-header {
                border-bottom: 2px solid #162040;
                padding-bottom: 8pt;
                margin-bottom: 16pt;
            }

            .print-doc-header h1 {
                font-size: 14pt;
                font-weight: bold;
                color: #162040;
                margin: 0 0 3pt;
            }

            .print-doc-header p {
                font-size: 9pt;
                color: #555;
                margin: 0;
            }

            .print-school {
                page-break-after: always;
                padding-bottom: 16pt;
            }

            .print-school:last-child {
                page-break-after: auto;
            }

            .school-title {
                display: flex;
                align-items: center;
                gap: 8pt;
                margin-bottom: 10pt;
                border-bottom: 1px solid #e5e7eb;
                padding-bottom: 6pt;
            }

            .school-title h2 {
                font-size: 12pt;
                font-weight: bold;
                color: #162040;
                margin: 0;
            }

            .school-badge {
                font-size: 7.5pt;
                font-weight: bold;
                padding: 2pt 6pt;
                border-radius: 4pt;
            }

            .section-title {
                font-size: 9pt;
                font-weight: bold;
                color: #162040;
                background: #f0f4fa;
                padding: 5pt 8pt;
                margin: 10pt 0 4pt;
                border-left: 3pt solid #162040;
            }

            table.print-table {
                width: 100%;
                border-collapse: collapse;
                font-size: 8.5pt;
                margin-bottom: 6pt;
            }

            table.print-table td {
                padding: 4pt 6pt;
                border: 0.5pt solid #d1d5db;
                vertical-align: top;
            }

            table.print-table td:nth-child(odd) {
                background: #f9fafb;
                width: 22%;
                font-weight: 600;
                color: #374151;
            }

            table.print-table td:nth-child(even) {
                width: 28%;
            }

            .badge-KB {
                background: #dcfce7;
                color: #166534;
            }

            .badge-TK {
                background: #d1fae5;
                color: #065f46;
            }

            .badge-SD {
                background: #dbeafe;
                color: #1e40af;
            }

            .badge-SMP {
                background: #ede9fe;
                color: #5b21b6;
            }

            .badge-SMA {
                background: #e0e7ff;
                color: #3730a3;
            }

            .badge-SMK {
                background: #fef3c7;
                color: #92400e;
            }

            .badge-default {
                background: #f3f4f6;
                color: #374151;
            }
        }

        @media screen {
            #print-area {
                display: none;
            }
        }
    </style>

    <div class="screen-content p-6 md:p-8 max-w-5xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-[#162040]">Cetak Laporan</h1>
            <p class="text-sm text-gray-500 mt-1">Ekspor data sekolah dalam format PDF atau Excel (CSV)</p>
        </div>

        {{-- Settings Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-4">
            <h2 class="text-base font-semibold text-[#162040] mb-4">Pengaturan Laporan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Filter Jenjang --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Filter
                        Jenjang</label>
                    <div class="relative">
                        <select id="filter-jenjang"
                            class="w-full appearance-none bg-white border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm text-[#162040] font-medium focus:outline-none focus:ring-2 focus:ring-[#162040]/20 focus:border-[#162040] cursor-pointer transition">
                            <option value="">Semua Jenjang ({{ $sekolahList->count() }} sekolah)</option>
                            @foreach ($jenjangCounts as $jenjang => $count)
                                <option value="{{ $jenjang }}">{{ $jenjang }} ({{ $count }} sekolah)
                                </option>
                            @endforeach
                        </select>
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>

                {{-- Summary + Buttons --}}
                <div class="flex flex-col justify-between">
                    <p class="text-sm text-gray-500 mb-3">
                        Akan mencetak <span id="summary-count"
                            class="font-semibold text-[#162040]">{{ $sekolahList->count() }}</span> sekolah
                        dengan <span id="summary-sections" class="font-semibold text-[#162040]">5</span> bagian data
                    </p>
                    <div class="flex gap-3 flex-wrap">
                        <button onclick="window.print()"
                            class="flex items-center gap-2 bg-[#162040] hover:bg-[#1e2f5c] text-white text-sm font-medium px-5 py-2.5 rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Cetak / Simpan PDF
                        </button>
                        <button onclick="exportCsv()"
                            class="flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-[#162040] text-sm font-medium px-5 py-2.5 rounded-xl transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Ekspor Excel (CSV)
                        </button>
                    </div>
                </div>
            </div>

            {{-- Section Checkboxes --}}
            <div class="mt-5 pt-5 border-t border-gray-100">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Bagian yang Akan
                    Dicetak</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach ([['identitas', 'Identitas Sekolah'], ['program', 'Program Pendidikan'], ['teknologi', 'Teknologi Pembelajaran'], ['sarpras', 'Sarana Prasarana'], ['sdm', 'SDM']] as [$val, $label])
                        <label class="section-label flex items-center gap-2.5 cursor-pointer select-none group">
                            <input type="checkbox" class="section-check hidden" value="{{ $val }}" checked>
                            <span
                                class="section-box w-5 h-5 rounded-md border-2 border-[#162040] flex items-center justify-center transition-all bg-[#162040]">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            <span
                                class="text-sm text-gray-700 group-hover:text-[#162040] transition font-medium">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Preview Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-[#162040]">
                    Preview Data (<span id="preview-count">{{ $sekolahList->count() }}</span> sekolah)
                </h2>
            </div>

            @php
                $badgeCfg = [
                    'KB' => ['bg' => 'bg-green-100', 'text' => 'text-green-700'],
                    'TK' => ['bg' => 'bg-teal-100', 'text' => 'text-teal-700'],
                    'SD' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700'],
                    'SMP' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-700'],
                    'SMA' => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700'],
                    'SMK' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700'],
                ];
            @endphp

            <div id="preview-list" class="divide-y divide-gray-50">
                @forelse ($sekolahList as $sekolah)
                    @php $b = $badgeCfg[$sekolah->jenjang] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600']; @endphp
                    <div class="preview-item flex items-center gap-3 py-3" data-jenjang="{{ $sekolah->jenjang }}">
                        <div
                            class="w-11 h-11 rounded-xl {{ $b['bg'] }} flex items-center justify-center flex-shrink-0">
                            <span class="text-xs font-bold {{ $b['text'] }}">{{ $sekolah->jenjang }}</span>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-[#162040]">{{ $sekolah->nama }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $sekolah->kota?->nama ?? '-' }},
                                {{ $sekolah->provinsi?->nama ?? '-' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-400 py-4 text-center">Tidak ada sekolah.</p>
                @endforelse
            </div>

            <div id="preview-empty" class="hidden text-center py-8">
                <p class="text-sm text-gray-400">Tidak ada sekolah yang cocok dengan filter.</p>
            </div>
        </div>

    </div>{{-- END screen-content --}}


    {{-- ─────────────────────────────────────────────────── --}}
    {{-- PRINT AREA — hidden on screen, visible when printing --}}
    {{-- ─────────────────────────────────────────────────── --}}
    <div id="print-area">

        <div class="print-doc-header">
            <h1>Laporan Data Sekolah &mdash; Yayasan Hang Tuah</h1>
            <p>Dicetak: {{ now()->translatedFormat('d F Y, H:i') }} &nbsp;|&nbsp; Total: <span
                    class="print-total">{{ $sekolahList->count() }}</span> sekolah</p>
        </div>

        @foreach ($sekolahList as $sekolah)
            @php
                $sdm = $sekolah->sdm->first();
                $sarpras = $sekolah->saranaPrasarana->first();
                $program = $sekolah->programPendidikan->first();
                $teknologi = $sekolah->teknologiPembelajaran->first();
                $guruS2S3 = $sekolah->sdmGuru->filter(fn($g) => in_array($g->kualifikasi, ['S2', 'S3']))->count();
                $totalGuru = $sdm ? $sdm->guru_pns + $sdm->guru_honorer + $sdm->guru_p3k : 0;
                $guruTetap = $sdm ? $sdm->guru_pns + $sdm->guru_p3k : 0;
                $totalKaryawan = $sdm ? $sdm->karyawan_pns + $sdm->karyawan_honorer + $sdm->karyawan_p3k : 0;
                $jenjang = $sekolah->jenjang;
                $badgeClass = match ($jenjang) {
                    'KB' => 'badge-KB',
                    'TK' => 'badge-TK',
                    'SD' => 'badge-SD',
                    'SMP' => 'badge-SMP',
                    'SMA' => 'badge-SMA',
                    'SMK' => 'badge-SMK',
                    default => 'badge-default',
                };
            @endphp

            <div class="print-school" data-jenjang="{{ $jenjang }}">

                <div class="school-title">
                    <h2>{{ $sekolah->nama }}</h2>
                    <span class="school-badge {{ $badgeClass }}">{{ $jenjang }}</span>
                </div>

                {{-- ── Identitas Sekolah ─────────────────── --}}
                <div class="print-section" data-section="identitas">
                    <div class="section-title">Identitas Sekolah</div>
                    <table class="print-table">
                        <tr>
                            <td>NPSN</td>
                            <td>{{ $sekolah->npsn ?? '-' }}</td>
                            <td>Jenjang</td>
                            <td>{{ $sekolah->jenjang }}</td>
                        </tr>
                        <tr>
                            <td>Tahun Berdiri</td>
                            <td>{{ $sekolah->tahun_berdiri ?? '-' }}</td>
                            <td>Akreditasi</td>
                            <td>
                                {{ $sekolah->akreditasi_nilai ?? '-' }}
                                @if ($sekolah->akreditasi_predikat)
                                    ({{ strtoupper($sekolah->akreditasi_predikat) }})
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>No SK Akreditasi</td>
                            <td>{{ $sekolah->no_sk_akreditasi ?? '-' }}</td>
                            <td>Tahun Akreditasi</td>
                            <td>{{ $sekolah->akreditasi_tahun ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Kepala Sekolah</td>
                            <td>{{ $sekolah->kepala_sekolah_nama ?? '-' }}</td>
                            <td>HP Kepsek</td>
                            <td>{{ $sekolah->kepala_sekolah_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Operator</td>
                            <td>{{ $sekolah->operator_nama ?? '-' }}</td>
                            <td>HP Operator</td>
                            <td>{{ $sekolah->operator_hp ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{ $sekolah->email ?? '-' }}</td>
                            <td>Website</td>
                            <td>{{ $sekolah->website ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Telepon</td>
                            <td>{{ $sekolah->telepon ?? '-' }}</td>
                            <td>Provinsi</td>
                            <td>{{ $sekolah->provinsi?->nama ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td colspan="3">{{ $sekolah->alamat ?? '-' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- ── Program Pendidikan ─────────────────── --}}
                <div class="print-section" data-section="program">
                    <div class="section-title">Program Pendidikan</div>
                    @if ($program)
                        <table class="print-table">
                            <tr>
                                <td>Kurikulum</td>
                                <td>{{ $program->kurikulum ?? '-' }}</td>
                                <td>Kur. Kebaharian</td>
                                <td>{{ $program->kurikulum_kebaharian ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Program Unggulan</td>
                                <td>{{ $program->program_unggulan ?? '-' }}</td>
                                <td>Ekstrakurikuler</td>
                                <td>{{ $program->ekstrakurikuler ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Skor PBD Literasi</td>
                                <td>{{ $program->pbd_literasi ?? '-' }}</td>
                                <td>Skor PBD Numerasi</td>
                                <td>{{ $program->pbd_numerasi ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>Skor PBD Karakter</td>
                                <td>{{ $program->pbd_karakter ?? '-' }}</td>
                                <td>Tahun Ajaran</td>
                                <td>{{ $program->tahun_ajaran ?? '-' }}</td>
                            </tr>
                        </table>
                    @else
                        <p style="font-size:8pt; color:#888; padding: 4pt 0;">Data belum diisi.</p>
                    @endif
                </div>

                {{-- ── Teknologi Pembelajaran ─────────────── --}}
                <div class="print-section" data-section="teknologi">
                    <div class="section-title">Teknologi Pembelajaran</div>
                    @if ($teknologi)
                        <table class="print-table">
                            <tr>
                                <td>Lab Komputer</td>
                                <td>{{ $teknologi->memiliki_lab_komputer ? 'Ada' : 'Tidak' }}
                                    @if ($teknologi->memiliki_lab_komputer && $teknologi->jumlah_komputer_lab)
                                        ({{ $teknologi->jumlah_komputer_lab }} unit)
                                    @endif
                                </td>
                                <td>Proyektor</td>
                                <td>{{ $teknologi->memiliki_proyektor ? 'Ada' : 'Tidak' }}
                                    @if ($teknologi->memiliki_proyektor && $teknologi->jumlah_proyektor)
                                        ({{ $teknologi->jumlah_proyektor }} unit)
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Internet</td>
                                <td>{{ $teknologi->memiliki_internet ? 'Ada' : 'Tidak' }}
                                    @if ($teknologi->memiliki_internet && $teknologi->bandwidth_mbps)
                                        ({{ $teknologi->bandwidth_mbps }} Mbps)
                                    @endif
                                </td>
                                <td>Jenis Internet</td>
                                <td>{{ $teknologi->jenis_internet ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td>LMS</td>
                                <td>{{ $teknologi->memiliki_lms ? 'Ada' : 'Tidak' }}
                                    @if ($teknologi->memiliki_lms && $teknologi->nama_lms)
                                        ({{ $teknologi->nama_lms }})
                                    @endif
                                </td>
                                <td>E-Perpustakaan</td>
                                <td>{{ $teknologi->memiliki_e_perpustakaan ? 'Ada' : 'Tidak' }}</td>
                            </tr>
                            <tr>
                                <td>Smart Classroom</td>
                                <td>{{ $teknologi->memiliki_smart_classroom ? 'Ada' : 'Tidak' }}</td>
                                <td>Tenaga IT</td>
                                <td>{{ $teknologi->memiliki_tenaga_it ? 'Ada' : 'Tidak' }}</td>
                            </tr>
                            <tr>
                                <td>Laptop Guru</td>
                                <td>{{ $teknologi->jumlah_laptop_guru ?? '-' }} unit</td>
                                <td>Komputer Admin</td>
                                <td>{{ $teknologi->jumlah_komputer_admin ?? '-' }} unit</td>
                            </tr>
                        </table>
                    @else
                        <p style="font-size:8pt; color:#888; padding: 4pt 0;">Data belum diisi.</p>
                    @endif
                </div>

                {{-- ── Sarana Prasarana ───────────────────── --}}
                <div class="print-section" data-section="sarpras">
                    <div class="section-title">Sarana Prasarana</div>
                    <table class="print-table">
                        <tr>
                            <td>Luas Tanah</td>
                            <td>{{ $sekolah->luas_tanah ? number_format($sekolah->luas_tanah, 0, ',', '.') . ' m²' : '-' }}
                            </td>
                            <td>Luas Bangunan</td>
                            <td>{{ $sarpras && $sarpras->luas_bangunan_m2 ? number_format($sarpras->luas_bangunan_m2, 0, ',', '.') . ' m²' : '-' }}
                            </td>
                        </tr>
                        <tr>
                            <td>Status Kepemilikan</td>
                            <td>{{ $sarpras ? str_replace('_', ' ', ucfirst($sarpras->status_kepemilikan ?? '-')) : '-' }}
                            </td>
                            <td>Ruang Kelas</td>
                            <td>{{ $sarpras ? ($sarpras->jumlah_ruang_kelas ?? '-') . ' ruang' : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Perpustakaan</td>
                            <td>{{ $sarpras ? ($sarpras->memiliki_perpustakaan ? 'Ada' : 'Tidak') : '-' }}</td>
                            <td>Laboratorium</td>
                            <td>{{ $sarpras ? ($sarpras->memiliki_laboratorium ? 'Ada' : 'Tidak') : '-' }}
                                @if ($sarpras && $sarpras->memiliki_laboratorium && $sarpras->jenis_laboratorium)
                                    ({{ $sarpras->jenis_laboratorium }})
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>UKS</td>
                            <td>{{ $sarpras ? ($sarpras->memiliki_uks ? 'Ada' : 'Tidak') : '-' }}</td>
                            <td>Lapangan</td>
                            <td>{{ $sarpras ? ($sarpras->memiliki_lapangan ? 'Ada' : 'Tidak') : '-' }}</td>
                        </tr>
                    </table>
                </div>

                {{-- ── SDM ────────────────────────────────── --}}
                <div class="print-section" data-section="sdm">
                    <div class="section-title">SDM</div>
                    <table class="print-table">
                        <tr>
                            <td>Jumlah Guru</td>
                            <td>{{ $totalGuru }}</td>
                            <td>Guru Tetap (GTY)</td>
                            <td>{{ $guruTetap }}</td>
                        </tr>
                        <tr>
                            <td>Guru Tidak Tetap</td>
                            <td>{{ $sdm ? $sdm->guru_honorer : '-' }}</td>
                            <td>Guru Sertifikasi</td>
                            <td>{{ $sdm ? $sdm->guru_bersertifikasi ?? '-' : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Guru S1+</td>
                            <td>{{ $sdm ? $sdm->guru_s1_keatas ?? '-' : '-' }}</td>
                            <td>Guru S2/S3</td>
                            <td>{{ $guruS2S3 ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Jumlah Karyawan</td>
                            <td>{{ $totalKaryawan }}</td>
                            <td>Jumlah Rombel</td>
                            <td>{{ $sdm ? $sdm->jumlah_rombel ?? '-' : '-' }}</td>
                        </tr>
                    </table>
                </div>

            </div>{{-- end .print-school --}}
        @endforeach

    </div>{{-- end #print-area --}}

    @push('scripts')
        @php
            $schoolsDataArr = $sekolahList
                ->map(function ($s) {
                    $sdm = $s->sdm->first();
                    $sarpras = $s->saranaPrasarana->first();
                    $program = $s->programPendidikan->first();
                    $tek = $s->teknologiPembelajaran->first();
                    $totalGuru = $sdm ? $sdm->guru_pns + $sdm->guru_honorer + $sdm->guru_p3k : null;
                    $guruTetap = $sdm ? $sdm->guru_pns + $sdm->guru_p3k : null;
                    $totalKaryawan = $sdm ? $sdm->karyawan_pns + $sdm->karyawan_honorer + $sdm->karyawan_p3k : null;
                    return [
                        'nama' => $s->nama,
                        'jenjang' => $s->jenjang,
                        'npsn' => $s->npsn,
                        'kota' => $s->kota?->nama,
                        'provinsi' => $s->provinsi?->nama,
                        'akreditasi' =>
                            ($s->akreditasi_nilai ?? '') .
                            ($s->akreditasi_predikat ? ' (' . strtoupper($s->akreditasi_predikat) . ')' : ''),
                        'kepala_sekolah' => $s->kepala_sekolah_nama,
                        'email' => $s->email,
                        'tahun_berdiri' => $s->tahun_berdiri,
                        'luas_tanah' => $s->luas_tanah,
                        'luas_bangunan' => $sarpras?->luas_bangunan_m2,
                        'status_kepemilikan' => $sarpras?->status_kepemilikan,
                        'ruang_kelas' => $sarpras?->jumlah_ruang_kelas,
                        'total_guru' => $totalGuru,
                        'guru_tetap' => $guruTetap,
                        'guru_honorer' => $sdm?->guru_honorer,
                        'guru_bersertifikasi' => $sdm?->guru_bersertifikasi,
                        'total_karyawan' => $totalKaryawan,
                        'jumlah_rombel' => $sdm?->jumlah_rombel,
                        'kurikulum' => $program?->kurikulum,
                        'program_unggulan' => $program?->program_unggulan,
                        'lab_komputer' => $tek ? ($tek->memiliki_lab_komputer ? 'Ada' : 'Tidak') : null,
                        'internet' => $tek ? ($tek->memiliki_internet ? 'Ada' : 'Tidak') : null,
                        'bandwidth' => $tek?->bandwidth_mbps,
                    ];
                })
                ->values()
                ->all();
        @endphp
        <script>
            (function() {
                // ── Data embedded for CSV export ────────────────────────────────
                const schoolsData = @json($schoolsDataArr);

                const sectionLabels = {
                    identitas: 'Identitas Sekolah',
                    program: 'Program Pendidikan',
                    teknologi: 'Teknologi Pembelajaran',
                    sarpras: 'Sarana Prasarana',
                    sdm: 'SDM',
                };

                // ── DOM refs ────────────────────────────────────────────────────
                const filterJenjang = document.getElementById('filter-jenjang');
                const sectionChecks = document.querySelectorAll('.section-check');
                const sectionLabelsEl = document.querySelectorAll('.section-label');
                const previewItems = document.querySelectorAll('.preview-item');
                const previewEmpty = document.getElementById('preview-empty');
                const previewCountEl = document.getElementById('preview-count');
                const summaryCountEl = document.getElementById('summary-count');
                const summarySectionsEl = document.getElementById('summary-sections');
                const printSchools = document.querySelectorAll('.print-school');
                const printSections = document.querySelectorAll('.print-section');
                const printTotals = document.querySelectorAll('.print-total');

                // ── Checkbox toggle UI ──────────────────────────────────────────
                sectionLabelsEl.forEach(label => {
                    label.addEventListener('click', function() {
                        const cb = this.querySelector('.section-check');
                        const box = this.querySelector('.section-box');
                        const svg = box.querySelector('svg');
                        cb.checked = !cb.checked;
                        if (cb.checked) {
                            box.classList.add('bg-[#162040]', 'border-[#162040]');
                            box.classList.remove('bg-white', 'border-gray-300');
                            svg.style.display = '';
                        } else {
                            box.classList.remove('bg-[#162040]', 'border-[#162040]');
                            box.classList.add('bg-white', 'border-gray-300');
                            svg.style.display = 'none';
                        }
                        updateFilters();
                    });
                });

                // ── Filter logic ────────────────────────────────────────────────
                function updateFilters() {
                    const selectedJenjang = filterJenjang.value;
                    const checkedSections = [...sectionChecks].filter(c => c.checked).map(c => c.value);

                    // Preview list
                    let visible = 0;
                    previewItems.forEach(item => {
                        const show = !selectedJenjang || item.dataset.jenjang === selectedJenjang;
                        item.classList.toggle('hidden', !show);
                        if (show) visible++;
                    });
                    previewCountEl.textContent = visible;
                    summaryCountEl.textContent = visible;
                    summarySectionsEl.textContent = checkedSections.length;
                    previewEmpty.classList.toggle('hidden', visible > 0);

                    // Print schools
                    printSchools.forEach(school => {
                        const show = !selectedJenjang || school.dataset.jenjang === selectedJenjang;
                        school.classList.toggle('hidden-print', !show);
                    });

                    // Print sections
                    printSections.forEach(section => {
                        const show = checkedSections.includes(section.dataset.section);
                        section.classList.toggle('hidden-print', !show);
                    });

                    // Print totals
                    printTotals.forEach(el => el.textContent = visible);
                }

                filterJenjang.addEventListener('change', updateFilters);
                updateFilters();

                // ── CSV Export ──────────────────────────────────────────────────
                window.exportCsv = function() {
                    const selectedJenjang = filterJenjang.value;
                    const checkedSections = [...sectionChecks].filter(c => c.checked).map(c => c.value);
                    const filtered = schoolsData.filter(s => !selectedJenjang || s.jenjang === selectedJenjang);

                    // Build headers
                    const headers = ['Nama Sekolah', 'Jenjang', 'NPSN', 'Kota', 'Provinsi'];
                    if (checkedSections.includes('identitas')) {
                        headers.push('Akreditasi', 'Kepala Sekolah', 'Email', 'Tahun Berdiri');
                    }
                    if (checkedSections.includes('sarpras')) {
                        headers.push('Luas Tanah (m²)', 'Luas Bangunan (m²)', 'Status Kepemilikan', 'Ruang Kelas');
                    }
                    if (checkedSections.includes('sdm')) {
                        headers.push('Total Guru', 'Guru Tetap', 'Guru Honorer', 'Guru Sertifikasi', 'Total Karyawan',
                            'Jumlah Rombel');
                    }
                    if (checkedSections.includes('program')) {
                        headers.push('Kurikulum', 'Program Unggulan');
                    }
                    if (checkedSections.includes('teknologi')) {
                        headers.push('Lab Komputer', 'Internet', 'Bandwidth (Mbps)');
                    }

                    const escape = v => '"' + String(v ?? '').replace(/"/g, '""') + '"';

                    let csv = '\uFEFF' + headers.map(escape).join(',') + '\n';

                    filtered.forEach(s => {
                        const row = [s.nama, s.jenjang, s.npsn, s.kota, s.provinsi];
                        if (checkedSections.includes('identitas')) {
                            row.push(s.akreditasi, s.kepala_sekolah, s.email, s.tahun_berdiri);
                        }
                        if (checkedSections.includes('sarpras')) {
                            row.push(s.luas_tanah, s.luas_bangunan, s.status_kepemilikan, s.ruang_kelas);
                        }
                        if (checkedSections.includes('sdm')) {
                            row.push(s.total_guru, s.guru_tetap, s.guru_honorer, s.guru_bersertifikasi, s
                                .total_karyawan, s.jumlah_rombel);
                        }
                        if (checkedSections.includes('program')) {
                            row.push(s.kurikulum, s.program_unggulan);
                        }
                        if (checkedSections.includes('teknologi')) {
                            row.push(s.lab_komputer, s.internet, s.bandwidth);
                        }
                        csv += row.map(escape).join(',') + '\n';
                    });

                    const blob = new Blob([csv], {
                        type: 'text/csv;charset=utf-8;'
                    });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'laporan-data-sekolah-{{ now()->format('Ymd') }}.csv';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                };
            })();
        </script>
    @endpush

@endsection
