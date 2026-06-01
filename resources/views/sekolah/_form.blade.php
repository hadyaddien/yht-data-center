{{--
    Shared form partial for create & edit.
    Variables expected:
      $sekolah       (Sekolah model or null for create)
      $provinsiList  (Collection)
      $kotaList      (Collection)
      $kecamatanList (Collection, for edit only)
      $kelurahanList (Collection, for edit only)
      $tahunList     (array of years, desc)
      $action        (route URL)
      $method        ('POST' | 'PUT')
--}}

@php
    $isEdit = isset($sekolah) && $sekolah->exists;
    $v = fn(string $field, $default = '') => old($field, $isEdit ? $sekolah->$field ?? $default : $default);
    $kecamatanList = $kecamatanList ?? collect();
    $kelurahanList = $kelurahanList ?? collect();
    $pp = $programPendidikan ?? null;
    $pv_pp = fn(string $f, $d = '') => old("pp.$f", $pp?->$f ?? $d);
    $tp = $teknologiPembelajaran ?? null;
    $tpFieldMap = [
        'software_aplikasi_pembelajaran' => 'software_aplikasi_pembelajaran_status',
        'lms_kemendikdasmen' => 'lms_kemendikdasmen_status',
        'aplikasi_smart_classroom' => 'aplikasi_smart_classroom_status',
        'koleksi_ebook' => 'koleksi_ebook_status',
        'website_sekolah' => 'website_sekolah_status',
        'server_pembelajaran' => 'server_pembelajaran_status',
        'tenaga_khusus_it' => 'tenaga_khusus_it_status',
    ];
    $pv_tp = fn(string $f, $d = '') => old("tp.$f", $tp?->{$tpFieldMap[$f] ?? $f} ?? $d);
    $pv_tp_arr = function (string $f) use ($tp) {
        $value = old("tp.$f", $tp?->$f ?? []);
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            $value = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($value)) {
            return [];
        }

        return array_values(array_filter($value, fn($v) => $v !== null && $v !== ''));
    };
    $placeholderSelected = fn($value) => blank($value) ? 'selected' : '';

    $sp = $saranaPrasarana ?? null;

    $pv_sp = fn(string $f, $d = '') => old("sp.$f", $sp?->{$f} ?? $d);

    $pv_sp_bool = function (string $f) use ($sp) {
        $value = old("sp.$f", $sp?->{$f} ?? 0);
        return filter_var($value, FILTER_VALIDATE_BOOLEAN) || (string) $value === '1';
    };

    $sarprasItems = [
        'perpustakaan' => 'Perpustakaan',
        'laboratorium_ipa' => 'Laboratorium IPA',
        'laboratorium_bahasa' => 'Laboratorium Bahasa',
        'laboratorium_komputer' => 'Laboratorium Komputer',
        'ruang_keterampilan' => 'Ruang Keterampilan',
        'ruang_seni' => 'Ruang Seni',
        'ruang_osis' => 'Ruang OSIS',
        'uks_klinik_kesehatan' => 'UKS/Klinik Kesehatan',
        'ruang_kepala_sekolah' => 'Ruang Kepala Sekolah',
        'ruang_wakil_kepala_sekolah' => 'Ruang Wakil Kepala Sekolah',
        'ruang_tata_usaha' => 'Ruang Tata Usaha',
        'ruang_bendahara' => 'Ruang Bendahara',
        'ruang_guru' => 'Ruang Guru',
        'ruang_bk_konseling' => 'Ruang BK/Konseling',
        'aula_pertemuan' => 'Aula/Pertemuan',
        'kantin_sekolah' => 'Kantin Sekolah',
        'lapangan_olahraga' => 'Lapangan Olahraga',
        'lab_studio_kebaharian' => 'Lab/Studio Kebaharian',
        'toilet_terpisah' => 'Toilet Terpisah',
        'taman_hijau' => 'Taman Hijau',
        'tempat_parkir' => 'Tempat Parkir',
        'ruang_ibadah' => 'Ruang Ibadah',
        'ape_kb_tk' => 'APE (KB/TK)',
        'ifp_dari_pemerintah' => 'IFP dari Pemerintah',
        'laptop_ext_hd_dari_pemerintah' => 'Laptop & Ext HD dari Pemerintah',
    ];

    $sdmData = $sumberDayaManusia ?? ($sdmSekolah ?? ($sdm ?? null));

    $pv_sdm = fn(string $f, $d = '') => old("sdm.$f", $sdmData?->{$f} ?? $d);
@endphp

{{-- Validation Errors --}}
@if ($errors->any())
    <div class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm mb-5">
        <svg class="w-5 h-5 flex-shrink-0 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <ul class="list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Section style override: seragam dengan tab Program Pendidikan --}}
<style>
    #sekolah-form .form-section {
        padding-bottom: 1.25rem;
        margin-bottom: 1.25rem;
        border-bottom: 1px solid #eef2f7;
    }

    #sekolah-form .tab-panel>.form-section:last-child {
        padding-bottom: 0;
        margin-bottom: 0;
        border-bottom: 0;
    }

    #sekolah-form .form-section-title {
        display: flex;
        align-items: center;
        gap: 0.625rem;
        margin-bottom: 1rem;
        padding-bottom: 0 !important;
        border-bottom: 0 !important;
    }

    #sekolah-form .form-section-bar {
        width: 3px;
        height: 18px;
        border-radius: 9999px;
        background: #f5b301;
        flex-shrink: 0;
    }

    #sekolah-form .form-section-label {
        color: #162040;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: 0.02em;
        text-transform: uppercase;
    }
</style>

<form method="POST" action="{{ $action }}" id="sekolah-form" enctype="multipart/form-data">
    @csrf
    @if ($method === 'PUT')
        @method('PUT')
    @endif

    {{-- TABS --}}
    <div class="border-b border-gray-100 mb-0 -mx-6 px-6">
        <nav class="flex gap-0 overflow-x-auto" id="tab-nav">
            @php
                $tabs = [
                    'identitas' => 'Identitas',
                    'program' => 'Program Pendidikan',
                    'teknologi' => 'Teknologi',
                    'sarpras' => 'Sarana Prasarana',
                    'sdm' => 'SDM',
                ];
            @endphp
            @foreach ($tabs as $key => $label)
                @php
                    $tabAllowedOnCreate = in_array($key, ['identitas', 'program', 'teknologi', 'sarpras', 'sdm']);
                    $tabDisabled = !$tabAllowedOnCreate && !$isEdit;
                @endphp
                <button type="button" onclick="switchTab('{{ $key }}')" id="tab-btn-{{ $key }}"
                    @if ($tabDisabled) disabled @endif
                    class="tab-btn flex-shrink-0 px-4 py-2.5 text-sm border-b-2 transition-all whitespace-nowrap
                    {{ $key === 'identitas' ? 'border-[#162040] bg-[#eef3f9] text-[#162040] font-semibold rounded-t-lg' : 'border-transparent text-gray-500 font-medium hover:text-gray-700 hover:bg-gray-50 rounded-t-lg' }}
                    {{ $tabDisabled ? 'opacity-40 cursor-not-allowed' : 'cursor-pointer' }}">
                    {{ $label }}
                </button>
            @endforeach
        </nav>
    </div>
    <br>

    {{-- TAB: IDENTITAS --}}
    <div id="tab-identitas" class="tab-panel space-y-0">

        {{-- IDENTITAS SEKOLAH --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Identitas Sekolah</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-4">
                {{-- Nama Sekolah --}}
                <div>
                    <label class="form-label">
                        Nama Sekolah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" value="{{ $v('nama') }}"
                        placeholder="Contoh: SMP Hang Tuah 2 Jakarta"
                        class="form-input @error('nama') !border-red-400 @enderror">
                    @error('nama')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jenjang --}}
                <div>
                    <label class="form-label">
                        Jenjang <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <select name="jenjang" class="form-select @error('jenjang') !border-red-400 @enderror">
                            <option value="" {{ $placeholderSelected($v('jenjang')) }} disabled hidden>Pilih
                                Jenjang
                            </option>
                            @foreach (['KB', 'TK', 'SD', 'SMP', 'SMA', 'SMK'] as $j)
                                <option value="{{ $j }}" {{ $v('jenjang') === $j ? 'selected' : '' }}>
                                    {{ $j }}
                                </option>
                            @endforeach
                        </select>
                        <x-select-chevron />
                    </div>
                    @error('jenjang')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NPSN --}}
                <div>
                    <label class="form-label">
                        NPSN <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="npsn" value="{{ $v('npsn') }}" placeholder="8 digit angka"
                        maxlength="20" class="form-input @error('npsn') !border-red-400 @enderror">
                    @error('npsn')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tahun Berdiri --}}
                <div>
                    <label class="form-label">
                        Tahun Berdiri
                    </label>
                    <div class="relative">
                        <select name="tahun_berdiri" class="form-select">
                            <option value="" {{ $placeholderSelected($v('tahun_berdiri')) }} disabled hidden>—
                                Pilih Tahun —</option>
                            @foreach ($tahunList as $thn)
                                <option value="{{ $thn }}"
                                    {{ (string) $v('tahun_berdiri') === (string) $thn ? 'selected' : '' }}>
                                    {{ $thn }}
                                </option>
                            @endforeach
                        </select>
                        <x-select-chevron />
                    </div>
                </div>
            </div>
        </div>

        {{-- AKREDITASI --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Akreditasi</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                <div>
                    <label class="form-label">No. SK Akreditasi</label>
                    <input type="text" name="no_sk_akreditasi" value="{{ $v('no_sk_akreditasi') }}"
                        placeholder="Nomor SK BAN" class="form-input" maxlength="100">
                </div>
                <div>
                    <label class="form-label">Tahun Akreditasi</label>
                    <div class="relative">
                        <select name="akreditasi_tahun" class="form-select">
                            <option value="" {{ $placeholderSelected($v('akreditasi_tahun')) }} disabled hidden>—
                                Pilih Tahun —</option>
                            @foreach (range(date('Y'), 1990) as $thn)
                                <option value="{{ $thn }}"
                                    {{ (string) $v('akreditasi_tahun') === (string) $thn ? 'selected' : '' }}>
                                    {{ $thn }}</option>
                            @endforeach
                        </select>
                        <x-select-chevron />
                    </div>
                </div>
                <div>
                    <label class="form-label">Nilai Akreditasi <span class="text-gray-400 font-normal">(0 –
                            100)</span></label>
                    <div class="relative">
                        <input type="number" name="akreditasi_nilai" id="akreditasi_nilai"
                            value="{{ $v('akreditasi_nilai') }}" placeholder="Contoh: 95" min="0" max="100"
                            class="form-input pr-28" oninput="updatePredikat(this.value)">
                        <span id="predikat-badge"
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] font-bold px-2 py-0.5 rounded-full
                            {{ $v('akreditasi_predikat') ? 'bg-teal-100 text-teal-700' : 'bg-gray-100 text-gray-400' }}">
                            {{ $v('akreditasi_predikat') ?: 'Predikat' }}
                        </span>
                    </div>
                </div>
                <div class="flex items-end pb-0.5">
                    <p class="text-[11px] text-gray-400 leading-relaxed">
                        Predikat otomatis:<br>
                        ≥91 <strong class="text-teal-600">UNGGUL</strong> · 71–90 <strong class="text-blue-600">BAIK
                            SEKALI</strong><br>
                        51–70 <strong class="text-green-600">BAIK</strong> · &lt;51 <strong
                            class="text-yellow-600">CUKUP</strong>
                    </p>
                </div>
            </div>
        </div>

        {{-- KONTAK & PIMPINAN --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Kontak &amp; Pimpinan</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                {{-- Nama Kepala Sekolah: full width --}}
                <div class="sm:col-span-2">
                    <label class="form-label">Nama Kepala Sekolah</label>
                    <input type="text" name="kepala_sekolah_nama" value="{{ $v('kepala_sekolah_nama') }}"
                        placeholder="Nama lengkap kepala sekolah" class="form-input">
                </div>
                {{-- HP & NIP side by side --}}
                <div>
                    <label class="form-label">HP Kepala Sekolah</label>
                    <input type="text" name="kepala_sekolah_hp" value="{{ $v('kepala_sekolah_hp') }}"
                        placeholder="08xx-xxxx-xxxx" class="form-input" maxlength="30">
                </div>
                <div>
                    <label class="form-label">NIP Kepala Sekolah</label>
                    <input type="text" name="kepala_sekolah_nip" value="{{ $v('kepala_sekolah_nip') }}"
                        placeholder="NIP / NIK" class="form-input" maxlength="30">
                </div>
                {{-- Nama Operator: full width --}}
                <div class="sm:col-span-2">
                    <label class="form-label">Nama Operator</label>
                    <input type="text" name="operator_nama" value="{{ $v('operator_nama') }}"
                        placeholder="Nama operator sekolah" class="form-input">
                </div>
                {{-- HP, Email, Website, Telepon: 2 columns --}}
                <div>
                    <label class="form-label">HP Operator</label>
                    <input type="text" name="operator_hp" value="{{ $v('operator_hp') }}"
                        placeholder="08xx-xxxx-xxxx" class="form-input" maxlength="30">
                </div>
                <div>
                    <label class="form-label">Email Sekolah</label>
                    <input type="email" name="email" value="{{ $v('email') }}"
                        placeholder="email@sekolah.sch.id"
                        class="form-input @error('email') !border-red-400 @enderror">
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Website</label>
                    <input type="url" name="website" value="{{ $v('website') }}"
                        placeholder="https://www.sekolah.sch.id"
                        class="form-input @error('website') !border-red-400 @enderror">
                    @error('website')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" value="{{ $v('telepon') }}" placeholder="(021) 7xxxxxx"
                        class="form-input" maxlength="30">
                </div>
            </div>
        </div>

        {{-- ALAMAT --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Alamat</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                <div>
                    <label class="form-label">Provinsi <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select name="provinsi_id" id="provinsi_id"
                            class="form-select @error('provinsi_id') !border-red-400 @enderror"
                            onchange="loadKota(this.value)">
                            <option value="" {{ $placeholderSelected($v('provinsi_id')) }} disabled hidden>—
                                Pilih Provinsi —</option>
                            @foreach ($provinsiList as $prov)
                                <option value="{{ $prov->id }}"
                                    {{ (string) $v('provinsi_id') === (string) $prov->id ? 'selected' : '' }}>
                                    {{ $prov->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-select-chevron />
                    </div>
                    @error('provinsi_id')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Kota / Kabupaten</label>
                    <div class="relative">
                        <select name="kota_id" id="kota_id" class="form-select"
                            onchange="loadKecamatan(this.value)">
                            <option value="" {{ $placeholderSelected($v('kota_id')) }} disabled hidden>— Pilih
                                Kota/Kabupaten —</option>
                            @foreach ($kotaList as $kota)
                                <option value="{{ $kota->id }}"
                                    {{ (string) $v('kota_id') === (string) $kota->id ? 'selected' : '' }}>
                                    {{ $kota->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-select-chevron />
                    </div>
                </div>
                <div>
                    <label class="form-label">Kecamatan</label>
                    <div class="relative">
                        <select name="kecamatan" id="kecamatan_id" class="form-select"
                            onchange="loadKelurahan(this.value, document.getElementById('kota_id').value)">
                            <option value="" {{ $placeholderSelected($v('kecamatan')) }} disabled hidden>— Pilih
                                Kecamatan —</option>
                            @foreach ($kecamatanList as $kec)
                                <option value="{{ $kec->nama }}"
                                    {{ $v('kecamatan') === $kec->nama ? 'selected' : '' }}>
                                    {{ $kec->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-select-chevron />
                    </div>
                </div>
                <div>
                    <label class="form-label">Kelurahan / Desa</label>
                    <div class="relative">
                        <select name="kelurahan" id="kelurahan_id" class="form-select">
                            <option value="" {{ $placeholderSelected($v('kelurahan')) }} disabled hidden>— Pilih
                                Kelurahan —</option>
                            @foreach ($kelurahanList as $kel)
                                <option value="{{ $kel->nama }}"
                                    {{ $v('kelurahan') === $kel->nama ? 'selected' : '' }}>
                                    {{ $kel->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-select-chevron />
                    </div>
                </div>
                <div class="sm:col-span-1">
                    <label class="form-label">Alamat Jalan</label>
                    <input type="text" name="alamat" value="{{ $v('alamat') }}"
                        placeholder="Jl. Hang Tuah No. 1" class="form-input">
                </div>
                <div class="sm:col-span-1">
                    <label class="form-label">Kode Pos</label>
                    <input type="text" name="kode_pos" value="{{ $v('kode_pos') }}" placeholder="12345"
                        class="form-input" maxlength="10">
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-[#162040]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        Koordinat GPS / Link Embed Peta
                    </label>
                    <input type="text" name="koordinat_gps" value="{{ $v('koordinat_gps') }}"
                        placeholder="Contoh: -6.2088,106.8456 atau link Google Maps embed" class="form-input">
                    <p class="text-[10px] text-gray-400 mt-1">Masukkan koordinat (latitude, longitude) atau link embed
                        peta lokasi sekolah</p>
                </div>
            </div>
        </div>

        {{-- KEKUATAN & KELEMAHAN --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Kekuatan &amp; Kelemahan Sekolah</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                <div>
                    <label class="form-label flex items-center gap-1.5 text-green-700">
                        <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                        </svg>
                        Kekuatan / Keunggulan
                    </label>
                    <textarea name="kekuatan" rows="5"
                        placeholder="Tuliskan kekuatan dan keunggulan sekolah (prestasi, fasilitas unggulan, program khusus, dll)..."
                        class="form-input resize-none">{{ $v('kekuatan') }}</textarea>
                </div>
                <div>
                    <label class="form-label flex items-center gap-1.5 text-rose-600">
                        <svg class="w-3.5 h-3.5 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
                        </svg>
                        Kelemahan / Tantangan
                    </label>
                    <textarea name="kelemahan" rows="5"
                        placeholder="Tuliskan kelemahan dan tantangan yang dihadapi sekolah (sarana, SDM, keuangan, dll)..."
                        class="form-input resize-none">{{ $v('kelemahan') }}</textarea>
                </div>
            </div>
        </div>

        {{-- STATUS OPERASIONAL --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Status Operasional Sekolah</span>
            </div>
            <div class="flex items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="status_operasional" value="aktif"
                        class="w-4 h-4 text-[#162040] border-gray-300 focus:ring-[#162040]"
                        {{ $v('status_operasional', 'aktif') === 'aktif' ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-green-700 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-green-500 inline-block"></span>
                        Aktif
                    </span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="radio" name="status_operasional" value="tidak_aktif"
                        class="w-4 h-4 text-[#162040] border-gray-300 focus:ring-[#162040]"
                        {{ $v('status_operasional') === 'tidak_aktif' ? 'checked' : '' }}>
                    <span class="text-sm font-medium text-rose-600 flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-rose-500 inline-block"></span>
                        Tidak Aktif
                    </span>
                </label>
            </div>
        </div>

        {{-- UPLOAD DOKUMEN --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Upload Dokumen</span>
            </div>
            <div>
                <label class="form-label text-xs text-gray-500">Unggah file pendukung (Surat Akreditasi, SK, Foto, dll)
                    — Maks. 10MB per file</label>
                <input type="file" name="dokumen_identitas[]" multiple
                    accept=".png,.jpg,.jpeg,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.csv"
                    class="file-upload-input block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#eef3f9] file:text-[#162040] hover:file:bg-[#dce5f3] cursor-pointer mt-2"
                    data-preview="preview-identitas">
            </div>
            <div id="preview-identitas" class="file-preview-list mt-2 space-y-1"></div>
            @if ($isEdit && $sekolah->dokumen->where('kategori', 'identitas')->count())
                <div class="mt-3 space-y-1">
                    <p class="text-[10px] text-gray-400 font-medium">File tersimpan:</p>
                    @foreach ($sekolah->dokumen->where('kategori', 'identitas') as $doc)
                        <div
                            class="flex items-center gap-2 text-xs text-gray-600 bg-gray-50 rounded-lg px-3 py-2 group">
                            <x-file-icon :mime="$doc->mime_type" class="w-4 h-4 flex-shrink-0 text-gray-400" />
                            <a href="{{ Storage::url($doc->path) }}" target="_blank"
                                class="hover:text-[#162040] underline truncate flex-1">{{ $doc->nama }}</a>
                            <span
                                class="text-[10px] text-gray-400 flex-shrink-0">{{ round($doc->ukuran_bytes / 1024, 1) }}
                                KB</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>{{-- end tab-identitas --}}

    {{-- TAB: PROGRAM PENDIDIKAN --}}
    <div id="tab-program" class="tab-panel hidden">

        {{-- VISI & MISI --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Visi &amp; Misi</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-4">
                <div>
                    <label class="form-label flex items-center gap-1.5 text-[#162040]">
                        <svg class="w-3.5 h-3.5 text-[#162040]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Visi
                    </label>
                    <textarea name="pp[visi]" rows="4" placeholder="Tuliskan visi sekolah..." class="form-input resize-none">{{ $pv_pp('visi') }}</textarea>
                </div>
                <div>
                    <label class="form-label flex items-center gap-1.5 text-[#162040]">
                        <svg class="w-3.5 h-3.5 text-[#162040]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        Misi
                    </label>
                    <textarea name="pp[misi]" rows="4" placeholder="Tuliskan misi sekolah..." class="form-input resize-none">{{ $pv_pp('misi') }}</textarea>
                </div>
            </div>
        </div>

        {{-- NILAI UJIAN SEKOLAH --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Nilai Ujian Sekolah</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                <div>
                    <label class="form-label">Rata-rata Nilai Ujian TA 2024/2025</label>
                    <input type="number" name="pp[nilai_ujian_ta1]" value="{{ $pv_pp('nilai_ujian_ta1') }}"
                        step="0.01" min="0" max="100" inputmode="decimal"
                        class="form-input pp-decimal" placeholder="0.00-100.00">
                </div>
                <div>
                    <label class="form-label">Rata-rata Nilai Ujian TA 2025/2026</label>
                    <input type="number" name="pp[nilai_ujian_ta2]" value="{{ $pv_pp('nilai_ujian_ta2') }}"
                        step="0.01" min="0" max="100" inputmode="decimal"
                        class="form-input pp-decimal" placeholder="0.00-100.00">
                </div>
            </div>
        </div>

        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Rapor Pendidikan <span class="text-gray-400 font-normal">(Skor
                        PBD)</span></span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-5 gap-y-4">
                <div>
                    <label class="form-label">Kemampuan Literasi</label>
                    <input type="number" name="pp[pbd_literasi]" value="{{ $pv_pp('pbd_literasi') }}"
                        step="0.01" min="0" max="100" inputmode="decimal"
                        class="form-input pp-decimal" placeholder="Skor literasi">
                </div>
                <div>
                    <label class="form-label">Kemampuan Numerasi</label>
                    <input type="number" name="pp[pbd_numerasi]" value="{{ $pv_pp('pbd_numerasi') }}"
                        step="0.01" min="0" max="100" inputmode="decimal"
                        class="form-input pp-decimal" placeholder="Skor numerasi">
                </div>
                <div>
                    <label class="form-label">Karakter</label>
                    <input type="number" name="pp[pbd_karakter]" value="{{ $pv_pp('pbd_karakter') }}"
                        step="0.01" min="0" max="100" inputmode="decimal"
                        class="form-input pp-decimal" placeholder="Skor karakter">
                </div>
                <div>
                    <label class="form-label">Kualitas Pembelajaran</label>
                    <input type="number" name="pp[pbd_kualitas_pembelajaran]"
                        value="{{ $pv_pp('pbd_kualitas_pembelajaran') }}" step="0.01" min="0"
                        max="100" inputmode="decimal" class="form-input pp-decimal"
                        placeholder="Skor kualitas">
                </div>
                <div>
                    <label class="form-label">Iklim Keamanan</label>
                    <input type="number" name="pp[pbd_iklim_keamanan]" value="{{ $pv_pp('pbd_iklim_keamanan') }}"
                        step="0.01" min="0" max="100" inputmode="decimal"
                        class="form-input pp-decimal" placeholder="Skor keamanan">
                </div>
                <div>
                    <label class="form-label">Iklim Kebhinekaan</label>
                    <input type="number" name="pp[pbd_iklim_kebhinekaan]"
                        value="{{ $pv_pp('pbd_iklim_kebhinekaan') }}" step="0.01" min="0" max="100"
                        inputmode="decimal" class="form-input pp-decimal" placeholder="Skor kebhinekaan">
                </div>
            </div>
        </div>

        {{-- PRESTASI AKADEMIK --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Prestasi Akademik</span>
            </div>
            <div class="space-y-4">
                @foreach ([2025, 2026] as $thn)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-3">
                        <div>
                            <label class="form-label text-xs text-[#162040]/70">{{ $thn }} – Tk. Kota</label>
                            <input type="number" name="pp[prestasi_akad_{{ $thn }}_kota]"
                                value="{{ $pv_pp('prestasi_akad_' . $thn . '_kota') }}" min="0"
                                inputmode="numeric" step="1" class="form-input pp-integer"
                                placeholder="Jumlah penghargaan">
                        </div>
                        <div>
                            <label class="form-label text-xs text-[#162040]/70">{{ $thn }} – Tk.
                                Provinsi</label>
                            <input type="number" name="pp[prestasi_akad_{{ $thn }}_provinsi]"
                                value="{{ $pv_pp('prestasi_akad_' . $thn . '_provinsi') }}" min="0"
                                inputmode="numeric" step="1" class="form-input pp-integer"
                                placeholder="Jumlah penghargaan">
                        </div>
                        <div>
                            <label class="form-label text-xs text-[#162040]/70">{{ $thn }} – Tk.
                                Nasional</label>
                            <input type="number" name="pp[prestasi_akad_{{ $thn }}_nasional]"
                                value="{{ $pv_pp('prestasi_akad_' . $thn . '_nasional') }}" min="0"
                                inputmode="numeric" step="1" class="form-input pp-integer"
                                placeholder="Jumlah penghargaan">
                        </div>
                        <div>
                            <label class="form-label text-xs text-[#162040]/70">{{ $thn }} – Tk.
                                Internasional</label>
                            <input type="number" name="pp[prestasi_akad_{{ $thn }}_internasional]"
                                value="{{ $pv_pp('prestasi_akad_' . $thn . '_internasional') }}" min="0"
                                inputmode="numeric" step="1" class="form-input pp-integer"
                                placeholder="Jumlah penghargaan">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- PRESTASI NON AKADEMIK --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Prestasi Non Akademik</span>
            </div>
            <div class="space-y-4">
                @foreach ([2025, 2026] as $thn)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-3">
                        <div>
                            <label class="form-label text-xs text-[#162040]/70">{{ $thn }} – Tk. Kota</label>
                            <input type="number" name="pp[prestasi_non_{{ $thn }}_kota]"
                                value="{{ $pv_pp('prestasi_non_' . $thn . '_kota') }}" min="0"
                                inputmode="numeric" step="1" class="form-input pp-integer"
                                placeholder="Jumlah penghargaan">
                        </div>
                        <div>
                            <label class="form-label text-xs text-[#162040]/70">{{ $thn }} – Tk.
                                Provinsi</label>
                            <input type="number" name="pp[prestasi_non_{{ $thn }}_provinsi]"
                                value="{{ $pv_pp('prestasi_non_' . $thn . '_provinsi') }}" min="0"
                                inputmode="numeric" step="1" class="form-input pp-integer"
                                placeholder="Jumlah penghargaan">
                        </div>
                        <div>
                            <label class="form-label text-xs text-[#162040]/70">{{ $thn }} – Tk.
                                Nasional</label>
                            <input type="number" name="pp[prestasi_non_{{ $thn }}_nasional]"
                                value="{{ $pv_pp('prestasi_non_' . $thn . '_nasional') }}" min="0"
                                inputmode="numeric" step="1" class="form-input pp-integer"
                                placeholder="Jumlah penghargaan">
                        </div>
                        <div>
                            <label class="form-label text-xs text-[#162040]/70">{{ $thn }} – Tk.
                                Internasional</label>
                            <input type="number" name="pp[prestasi_non_{{ $thn }}_internasional]"
                                value="{{ $pv_pp('prestasi_non_' . $thn . '_internasional') }}" min="0"
                                inputmode="numeric" step="1" class="form-input pp-integer"
                                placeholder="Jumlah penghargaan">
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- KURIKULUM & KEBAHARIAN --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Kurikulum &amp; Kebaharian</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-5 gap-y-4">
                <div>
                    <label class="form-label">Kurikulum</label>
                    <div class="relative">
                        <select name="pp[kurikulum]" class="form-select">
                            <option value="" {{ $placeholderSelected($pv_pp('kurikulum')) }} disabled hidden>
                                Pilih</option>
                            @foreach (['K-13', 'Kurikulum Merdeka', 'K-13 dan Merdeka'] as $opt)
                                <option value="{{ $opt }}"
                                    {{ $pv_pp('kurikulum') === $opt ? 'selected' : '' }}>
                                    {{ $opt }}</option>
                            @endforeach
                        </select>
                        <x-select-chevron />
                    </div>
                </div>
                <div>
                    <label class="form-label">Kurikulum Kebaharian</label>
                    <div class="relative">
                        <select name="pp[kurikulum_kebaharian]" class="form-select">
                            <option value="" {{ $placeholderSelected($pv_pp('kurikulum_kebaharian')) }}
                                disabled hidden>Pilih</option>
                            @foreach (['Sudah berjalan', 'Belum berjalan', 'Tidak ada'] as $opt)
                                <option value="{{ $opt }}"
                                    {{ $pv_pp('kurikulum_kebaharian') === $opt ? 'selected' : '' }}>
                                    {{ $opt }}</option>
                            @endforeach
                        </select>
                        <x-select-chevron />
                    </div>
                </div>
                <div>
                    <label class="form-label">Jumlah Guru Kebaharian</label>
                    <input type="number" name="pp[jumlah_guru_kebaharian]"
                        value="{{ $pv_pp('jumlah_guru_kebaharian') }}" min="0" inputmode="numeric"
                        step="1" class="form-input pp-integer" placeholder="0">
                </div>
            </div>
        </div>

        {{-- SUMBER DANA --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Sumber Dana</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                <div>
                    <label class="form-label">Penerimaan BOS</label>
                    <div class="relative">
                        <select name="pp[penerimaan_bos]" class="form-select">
                            <option value="" {{ $placeholderSelected($pv_pp('penerimaan_bos')) }} disabled
                                hidden>Pilih</option>
                            @foreach (['Menerima', 'Belum menerima', 'Tidak menerima'] as $opt)
                                <option value="{{ $opt }}"
                                    {{ $pv_pp('penerimaan_bos') === $opt ? 'selected' : '' }}>{{ $opt }}
                                </option>
                            @endforeach
                        </select>
                        <x-select-chevron />
                    </div>
                </div>
                <div>
                    <label class="form-label">Penerimaan BOP</label>
                    <div class="relative">
                        <select name="pp[penerimaan_bop]" class="form-select">
                            <option value="" {{ $placeholderSelected($pv_pp('penerimaan_bop')) }} disabled
                                hidden>Pilih</option>
                            @foreach (['Menerima', 'Belum menerima', 'Tidak menerima'] as $opt)
                                <option value="{{ $opt }}"
                                    {{ $pv_pp('penerimaan_bop') === $opt ? 'selected' : '' }}>{{ $opt }}
                                </option>
                            @endforeach
                        </select>
                        <x-select-chevron />
                    </div>
                </div>
            </div>
        </div>

        {{-- PROGRAM UNGGULAN & EKSTRAKURIKULER --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Program Unggulan &amp; Ekstrakurikuler</span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-4">
                <div>
                    <label class="form-label">Program Unggulan</label>
                    <textarea name="pp[program_unggulan]" rows="4" placeholder="Deskripsikan program unggulan sekolah..."
                        class="form-input">{{ $pv_pp('program_unggulan') }}</textarea>
                    @error('pp.program_unggulan')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="form-label">Ekstrakurikuler</label>
                    <textarea name="pp[ekstrakurikuler]" rows="4" placeholder="Daftar kegiatan ekstrakurikuler..."
                        class="form-input">{{ $pv_pp('ekstrakurikuler') }}</textarea>
                    @error('pp.ekstrakurikuler')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- UPLOAD DOKUMEN --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Upload Dokumen</span>
            </div>
            <div>
                <label class="form-label text-xs text-gray-500">Unggah file pendukung (Nilai, Rapor, Sertifikat
                    Prestasi, dll) — Maks. 10MB per file</label>
                <input type="file" name="dokumen_program[]" multiple
                    accept=".png,.jpg,.jpeg,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.csv"
                    class="file-upload-input block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#eef3f9] file:text-[#162040] hover:file:bg-[#dce5f3] cursor-pointer mt-2"
                    data-preview="preview-program">
            </div>
            <div id="preview-program" class="file-preview-list mt-2 space-y-1"></div>
            @if ($isEdit && $sekolah->dokumen->where('kategori', 'program_pendidikan')->count())
                <div class="mt-3 space-y-1">
                    <p class="text-[10px] text-gray-400 font-medium">File tersimpan:</p>
                    @foreach ($sekolah->dokumen->where('kategori', 'program_pendidikan') as $doc)
                        <div
                            class="flex items-center gap-2 text-xs text-gray-600 bg-gray-50 rounded-lg px-3 py-2 group">
                            <x-file-icon :mime="$doc->mime_type" class="w-4 h-4 flex-shrink-0 text-gray-400" />
                            <a href="{{ Storage::url($doc->path) }}" target="_blank"
                                class="hover:text-[#162040] underline truncate flex-1">{{ $doc->nama }}</a>
                            <span
                                class="text-[10px] text-gray-400 flex-shrink-0">{{ round($doc->ukuran_bytes / 1024, 1) }}
                                KB</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>{{-- end tab-program --}}

    {{-- TAB: TEKNOLOGI --}}
    <div id="tab-teknologi" class="tab-panel hidden">
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Pemanfaatan Teknologi</span>
            </div>

            @php
                $techDropdowns = [
                    'software_aplikasi_pembelajaran' => [
                        'label' => 'Software Aplikasi Pembelajaran',
                        'options' => ['Sudah', 'Proses uji coba', 'Belum menggunakan'],
                    ],
                    'aplikasi_smart_classroom' => [
                        'label' => 'Aplikasi Smart Classroom',
                        'options' => ['Sudah', 'Dalam proses pemasangan', 'Belum ada'],
                    ],
                    'lms_kemendikdasmen' => [
                        'label' => 'LMS Kemendikdasmen',
                        'options' => ['Sudah', 'Proses pelatihan', 'Belum tahu'],
                    ],
                    'koleksi_ebook' => [
                        'label' => 'Koleksi E-Book',
                        'options' => ['Sudah ada', 'Ada tapi sedikit', 'Belum ada'],
                    ],
                    'website_sekolah' => [
                        'label' => 'Website Sekolah',
                        'options' => ['Ada', 'Sedang proses', 'Belum ada'],
                    ],
                    'server_pembelajaran' => [
                        'label' => 'Server Pembelajaran',
                        'options' => ['Sudah ada', 'Proses pembangunan', 'Belum ada'],
                    ],
                    'tenaga_khusus_it' => [
                        'label' => 'Tenaga Khusus IT',
                        'options' => ['Ada khusus IT', 'Ada namun paruh waktu', 'Belum ada'],
                    ],
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-4">
                @foreach ($techDropdowns as $field => $config)
                    @if ($field === 'lms_kemendikdasmen')
                        <div class="md:col-span-2">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-3">
                                <div>
                                    <label class="form-label">{{ $config['label'] }}</label>
                                    <div class="relative">
                                        <select name="tp[{{ $field }}]" class="form-select">
                                            <option value="" {{ $placeholderSelected($pv_tp($field)) }}
                                                disabled hidden>
                                                Pilih
                                            </option>
                                            @foreach ($config['options'] as $opt)
                                                <option value="{{ $opt }}"
                                                    {{ $pv_tp($field) === $opt ? 'selected' : '' }}>
                                                    {{ $opt }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-select-chevron />
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label">Nama LMS yang Digunakan</label>
                                    <input type="text" name="tp[nama_lms]" value="{{ $pv_tp('nama_lms') }}"
                                        placeholder="Contoh: Google Classroom, Moodle, LMS Kemdikbud..."
                                        class="form-input">
                                </div>
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="form-label">{{ $config['label'] }}</label>
                            <div class="relative">
                                <select name="tp[{{ $field }}]" class="form-select">
                                    <option value="" {{ $placeholderSelected($pv_tp($field)) }} disabled
                                        hidden>
                                        Pilih
                                    </option>
                                    @foreach ($config['options'] as $opt)
                                        <option value="{{ $opt }}"
                                            {{ $pv_tp($field) === $opt ? 'selected' : '' }}>
                                            {{ $opt }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-select-chevron />
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            {{-- CATATAN TAMBAHAN --}}
            <div class="mt-4">
                <label class="form-label">Catatan Tambahan Teknologi</label>
                <textarea name="tp[catatan]" rows="3"
                    placeholder="Catatan tambahan terkait teknologi pembelajaran di sekolah..." class="form-input resize-none">{{ $pv_tp('catatan') }}</textarea>
            </div>
        </div>

        @php
            $techMultiChoices = [
                'media_sosial' => [
                    'label' => 'Media Sosial',
                    'options' => ['Instagram', 'TikTok', 'YouTube', 'Facebook'],
                ],
                'platform_lms' => [
                    'label' => 'Platform Lms',
                    'options' => ['Google Classroom', 'Moodle', 'Schoology', 'Quipper'],
                ],
                'platform_pendidikan' => [
                    'label' => 'Platform Pendidikan',
                    'options' => ['PMM (Platform Merdeka Mengajar)', 'Rumah Belajar', 'Ruang Guru & Zenius'],
                ],
                'alat_interaktif' => [
                    'label' => 'Alat Interaktif',
                    'options' => ['Wordwall', 'Interactive Flat Panel (IFP)', 'Kahoot', 'Quiziz'],
                ],
                'platform_komunikasi' => [
                    'label' => 'Platform Komunikasi',
                    'options' => ['WhatsApp', 'Zoom Meet', 'Google Meet'],
                ],
                'aplikasi_manajemen' => [
                    'label' => 'Aplikasi Manajemen',
                    'options' => ['Portal khusus SPMB', 'EdConnect', 'Stela', 'Si Aka'],
                ],
            ];
        @endphp

        @foreach ($techMultiChoices as $field => $config)
            @php
                $selectedValues = $pv_tp_arr($field);
            @endphp
            <div class="form-section">
                <div class="form-section-title">
                    <span class="form-section-bar"></span>
                    <span class="form-section-label">{{ $config['label'] }}</span>
                </div>
                <div class="flex flex-wrap gap-x-5 gap-y-3">
                    @foreach ($config['options'] as $idx => $opt)
                        @php
                            $inputId = 'tp-' . $field . '-' . $idx;
                        @endphp
                        <label for="{{ $inputId }}"
                            class="inline-flex items-center gap-2 cursor-pointer select-none">
                            <input id="{{ $inputId }}" type="checkbox" name="tp[{{ $field }}][]"
                                value="{{ $opt }}"
                                class="h-4 w-4 rounded-full border-gray-300 accent-[#162040] focus:ring-[#162040]/20"
                                {{ in_array($opt, $selectedValues, true) ? 'checked' : '' }}>
                            <span class="text-[13px] text-[#162040]">{{ $opt }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        @endforeach
        {{-- PERANGKAT KERAS & INFRASTRUKTUR --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Perangkat Keras &amp; Infrastruktur</span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-5 gap-y-4">

                <div>
                    <label class="form-label">Lab Komputer</label>
                    <div class="relative">
                        @php $valLabKomp = $pv_tp('memiliki_lab_komputer'); @endphp
                        <select name="tp[memiliki_lab_komputer]" class="form-select">
                            <option value="" {{ $placeholderSelected($valLabKomp) }} disabled hidden>Pilih
                            </option>
                            <option value="1"
                                {{ $valLabKomp === true || $valLabKomp === 1 || $valLabKomp === '1' ? 'selected' : '' }}>
                                Ada</option>
                            <option value="0"
                                {{ $valLabKomp === false || $valLabKomp === 0 || $valLabKomp === '0' ? 'selected' : '' }}>
                                Tidak Ada</option>
                        </select>
                        <x-select-chevron />
                    </div>
                </div>

                <div>
                    <label class="form-label">Jumlah Komputer Lab</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="tp[jumlah_komputer_lab]"
                            value="{{ $pv_tp('jumlah_komputer_lab') }}" min="0" step="1"
                            inputmode="numeric" placeholder="0" class="form-input">
                        <span class="text-xs text-gray-500 shrink-0">unit</span>
                    </div>
                    @error('tp.jumlah_komputer_lab')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Jumlah Komputer Admin</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="tp[jumlah_komputer_admin]"
                            value="{{ $pv_tp('jumlah_komputer_admin') }}" min="0" step="1"
                            inputmode="numeric" placeholder="0" class="form-input">
                        <span class="text-xs text-gray-500 shrink-0">unit</span>
                    </div>
                    @error('tp.jumlah_komputer_admin')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Jumlah Laptop Guru</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="tp[jumlah_laptop_guru]"
                            value="{{ $pv_tp('jumlah_laptop_guru') }}" min="0" step="1"
                            inputmode="numeric" placeholder="0" class="form-input">
                        <span class="text-xs text-gray-500 shrink-0">unit</span>
                    </div>
                    @error('tp.jumlah_laptop_guru')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Proyektor</label>
                    <div class="relative">
                        @php $valProy = $pv_tp('memiliki_proyektor'); @endphp
                        <select name="tp[memiliki_proyektor]" class="form-select">
                            <option value="" {{ $placeholderSelected($valProy) }} disabled hidden>Pilih
                            </option>
                            <option value="1"
                                {{ $valProy === true || $valProy === 1 || $valProy === '1' ? 'selected' : '' }}>Ada
                            </option>
                            <option value="0"
                                {{ $valProy === false || $valProy === 0 || $valProy === '0' ? 'selected' : '' }}>
                                Tidak Ada</option>
                        </select>
                        <x-select-chevron />
                    </div>
                </div>

                <div>
                    <label class="form-label">Jumlah Proyektor</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="tp[jumlah_proyektor]" value="{{ $pv_tp('jumlah_proyektor') }}"
                            min="0" step="1" inputmode="numeric" placeholder="0" class="form-input">
                        <span class="text-xs text-gray-500 shrink-0">unit</span>
                    </div>
                    @error('tp.jumlah_proyektor')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Koneksi Internet</label>
                    <div class="relative">
                        @php $valInet = $pv_tp('memiliki_internet'); @endphp
                        <select name="tp[memiliki_internet]" class="form-select">
                            <option value="" {{ $placeholderSelected($valInet) }} disabled hidden>Pilih
                            </option>
                            <option value="1"
                                {{ $valInet === true || $valInet === 1 || $valInet === '1' ? 'selected' : '' }}>Ada
                            </option>
                            <option value="0"
                                {{ $valInet === false || $valInet === 0 || $valInet === '0' ? 'selected' : '' }}>
                                Tidak Ada</option>
                        </select>
                        <x-select-chevron />
                    </div>
                </div>

                <div>
                    <label class="form-label">Jenis Internet</label>
                    <input type="text" name="tp[jenis_internet]" value="{{ $pv_tp('jenis_internet') }}"
                        placeholder="Fiber, 4G, DSL, dll" class="form-input">
                    @error('tp.jenis_internet')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Bandwidth Internet</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="tp[bandwidth_mbps]" value="{{ $pv_tp('bandwidth_mbps') }}"
                            min="0" step="0.1" inputmode="decimal" placeholder="0" class="form-input">
                        <span class="text-xs text-gray-500 shrink-0">Mbps</span>
                    </div>
                    @error('tp.bandwidth_mbps')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- UPLOAD DOKUMEN --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Upload Dokumen</span>
            </div>
            <div>
                <label class="form-label text-xs text-gray-500">Unggah file pendukung (Foto Perangkat, Spesifikasi,
                    dll) — Maks. 10MB per file</label>
                <input type="file" name="dokumen_teknologi[]" multiple
                    accept=".png,.jpg,.jpeg,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.csv"
                    class="file-upload-input block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#eef3f9] file:text-[#162040] hover:file:bg-[#dce5f3] cursor-pointer mt-2"
                    data-preview="preview-teknologi">
            </div>
            <div id="preview-teknologi" class="file-preview-list mt-2 space-y-1"></div>
            @if ($isEdit && $sekolah->dokumen->where('kategori', 'teknologi')->count())
                <div class="mt-3 space-y-1">
                    <p class="text-[10px] text-gray-400 font-medium">File tersimpan:</p>
                    @foreach ($sekolah->dokumen->where('kategori', 'teknologi') as $doc)
                        <div
                            class="flex items-center gap-2 text-xs text-gray-600 bg-gray-50 rounded-lg px-3 py-2 group">
                            <x-file-icon :mime="$doc->mime_type" class="w-4 h-4 flex-shrink-0 text-gray-400" />
                            <a href="{{ Storage::url($doc->path) }}" target="_blank"
                                class="hover:text-[#162040] underline truncate flex-1">{{ $doc->nama }}</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>{{-- end tab-teknologi --}}

    {{-- TAB: SARANA PRASARANA --}}
    <div id="tab-sarpras" class="tab-panel hidden">

        {{-- KONDISI SARANA PRASARANA --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Kondisi Sarana Prasarana</span>
            </div>
            <p class="text-xs text-gray-400 -mt-1 mb-4">
                Aktifkan toggle jika sarana tersedia, lalu isi persentase kondisi fasilitas dalam rentang 0–100%.
            </p>

            <div class="space-y-2.5">
                @foreach ($sarprasItems as $field => $label)
                    @php
                        $adaField = $field . '_ada';
                        $kondisiField = $field . '_kondisi';
                        $isAda = $pv_sp_bool($adaField);
                        $inputId = 'sp-' . $field . '-kondisi';
                        $toggleId = 'sp-' . $field . '-ada';
                    @endphp

                    <div class="flex items-center justify-between gap-4 rounded-xl bg-gray-50/80 px-3 py-2.5">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-[#162040]">{{ $label }}</p>
                            @error("sp.$kondisiField")
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-xs text-gray-500">Ada</span>

                            <label for="{{ $toggleId }}"
                                class="relative inline-flex items-center cursor-pointer">
                                <input type="hidden" name="sp[{{ $adaField }}]" value="0">

                                <input id="{{ $toggleId }}" type="checkbox" name="sp[{{ $adaField }}]"
                                    value="1" class="sr-only peer sarpras-toggle"
                                    data-target="{{ $inputId }}" {{ $isAda ? 'checked' : '' }}>

                                <div
                                    class="relative w-9 h-5 bg-gray-200 rounded-full transition-colors
                                    peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-[#162040]/20
                                    peer-checked:bg-[#162040]
                                    after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                    after:bg-white after:border after:border-gray-300 after:rounded-full
                                    after:h-4 after:w-4 after:transition-all
                                    peer-checked:after:translate-x-4 peer-checked:after:border-white">
                                </div>
                            </label>

                            <div id="{{ $inputId }}-wrap"
                                class="flex items-center gap-1.5 {{ $isAda ? '' : 'hidden' }}">
                                <input id="{{ $inputId }}" type="number" name="sp[{{ $kondisiField }}]"
                                    value="{{ $pv_sp($kondisiField) }}" min="0" max="100"
                                    step="1" inputmode="numeric" placeholder="0"
                                    class="sarpras-percent w-20 rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm text-[#162040] outline-none focus:border-[#162040] focus:ring-2 focus:ring-[#162040]/10"
                                    {{ $isAda ? '' : 'disabled' }}>
                                <span class="text-xs text-gray-400">%</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- LUAS & BIAYA --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Luas &amp; Biaya</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-5 gap-y-4">
                <div>
                    <label class="form-label">Luas Tanah (m²)</label>
                    <input type="number" name="sp[luas_tanah]" value="{{ $pv_sp('luas_tanah') }}" min="0"
                        step="0.01" inputmode="decimal" placeholder="Contoh: 1500"
                        class="form-input sarpras-decimal">
                    @error('sp.luas_tanah')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Luas Bangunan (m²)</label>
                    <input type="number" name="sp[luas_bangunan]" value="{{ $pv_sp('luas_bangunan') }}"
                        min="0" step="0.01" inputmode="decimal" placeholder="Contoh: 750"
                        class="form-input sarpras-decimal">
                    @error('sp.luas_bangunan')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Biaya Sewa Lahan (Rp)</label>
                    <input type="number" name="sp[biaya_sewa_lahan]" value="{{ $pv_sp('biaya_sewa_lahan') }}"
                        min="0" step="1" inputmode="numeric" placeholder="Contoh: 5000000"
                        class="form-input sarpras-integer">
                    @error('sp.biaya_sewa_lahan')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        {{-- UPLOAD DOKUMEN --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Upload Dokumen</span>
            </div>
            <div>
                <label class="form-label text-xs text-gray-500">Unggah file pendukung (Foto Sarpras, Denah, dll) —
                    Maks. 10MB per file</label>
                <input type="file" name="dokumen_sarpras[]" multiple
                    accept=".png,.jpg,.jpeg,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.csv"
                    class="file-upload-input block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#eef3f9] file:text-[#162040] hover:file:bg-[#dce5f3] cursor-pointer mt-2"
                    data-preview="preview-sarpras">
            </div>
            <div id="preview-sarpras" class="file-preview-list mt-2 space-y-1"></div>
            @if ($isEdit && $sekolah->dokumen->where('kategori', 'sarpras')->count())
                <div class="mt-3 space-y-1">
                    <p class="text-[10px] text-gray-400 font-medium">File tersimpan:</p>
                    @foreach ($sekolah->dokumen->where('kategori', 'sarpras') as $doc)
                        <div
                            class="flex items-center gap-2 text-xs text-gray-600 bg-gray-50 rounded-lg px-3 py-2 group">
                            <x-file-icon :mime="$doc->mime_type" class="w-4 h-4 flex-shrink-0 text-gray-400" />
                            <a href="{{ Storage::url($doc->path) }}" target="_blank"
                                class="hover:text-[#162040] underline truncate flex-1">{{ $doc->nama }}</a>
                            <span
                                class="text-[10px] text-gray-400 flex-shrink-0">{{ round($doc->ukuran_bytes / 1024, 1) }}
                                KB</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>{{-- end tab-sarpras --}}


    {{-- TAB: SDM --}}
    <div id="tab-sdm" class="tab-panel hidden">

        {{-- TENAGA PENDIDIK & KEPENDIDIKAN --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Tenaga Pendidik &amp; Kependidikan</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-5 gap-y-4">

                <div>
                    <label class="form-label">Jumlah Rombongan Belajar</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[jumlah_rombel]" value="{{ $pv_sdm('jumlah_rombel') }}"
                            min="0" step="1" inputmode="numeric" placeholder="Contoh: 12"
                            class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">kelas</span>
                    </div>
                    @error('sdm.jumlah_rombel')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Jumlah Guru</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[jumlah_guru]" value="{{ $pv_sdm('jumlah_guru') }}"
                            min="0" step="1" inputmode="numeric" placeholder="Contoh: 24"
                            class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                    @error('sdm.jumlah_guru')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Guru Tetap Yayasan (GTY)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[guru_tetap_yayasan]"
                            value="{{ $pv_sdm('guru_tetap_yayasan') }}" min="0" step="1"
                            inputmode="numeric" placeholder="Contoh: 15" class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                    @error('sdm.guru_tetap_yayasan')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Guru Tidak Tetap (GTT)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[guru_tidak_tetap]"
                            value="{{ $pv_sdm('guru_tidak_tetap') }}" min="0" step="1"
                            inputmode="numeric" placeholder="Contoh: 9" class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                    @error('sdm.guru_tidak_tetap')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Guru PNS</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[guru_pns]" value="{{ $pv_sdm('guru_pns') }}"
                            min="0" step="1" inputmode="numeric" placeholder="0"
                            class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                </div>

                <div>
                    <label class="form-label">Guru P3K</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[guru_p3k]" value="{{ $pv_sdm('guru_p3k') }}"
                            min="0" step="1" inputmode="numeric" placeholder="0"
                            class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                </div>

                <div>
                    <label class="form-label">Guru Honorer</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[guru_honorer]" value="{{ $pv_sdm('guru_honorer') }}"
                            min="0" step="1" inputmode="numeric" placeholder="0"
                            class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                </div>

                <div>
                    <label class="form-label">Guru S1 Pendidikan</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[guru_s1_pendidikan]"
                            value="{{ $pv_sdm('guru_s1_pendidikan') }}" min="0" step="1"
                            inputmode="numeric" placeholder="Contoh: 18" class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                    @error('sdm.guru_s1_pendidikan')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Guru S1 Non Pendidikan</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[guru_s1_non_pendidikan]"
                            value="{{ $pv_sdm('guru_s1_non_pendidikan') }}" min="0" step="1"
                            inputmode="numeric" placeholder="Contoh: 3" class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                    @error('sdm.guru_s1_non_pendidikan')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Guru S2</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[guru_s2]" value="{{ $pv_sdm('guru_s2') }}" min="0"
                            step="1" inputmode="numeric" placeholder="Contoh: 4"
                            class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                    @error('sdm.guru_s2')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Guru S3</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[guru_s3]" value="{{ $pv_sdm('guru_s3') }}" min="0"
                            step="1" inputmode="numeric" placeholder="Contoh: 0"
                            class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                    @error('sdm.guru_s3')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Guru Sertifikasi</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[guru_sertifikasi]"
                            value="{{ $pv_sdm('guru_sertifikasi') }}" min="0" step="1"
                            inputmode="numeric" placeholder="Contoh: 10" class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                    @error('sdm.guru_sertifikasi')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Jumlah Karyawan</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[jumlah_karyawan]" value="{{ $pv_sdm('jumlah_karyawan') }}"
                            min="0" step="1" inputmode="numeric" placeholder="Contoh: 8"
                            class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                    @error('sdm.jumlah_karyawan')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Karyawan Tetap (KTY)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[karyawan_tetap]" value="{{ $pv_sdm('karyawan_tetap') }}"
                            min="0" step="1" inputmode="numeric" placeholder="Contoh: 5"
                            class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                    @error('sdm.karyawan_tetap')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Karyawan Tidak Tetap (KTT)</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[karyawan_tidak_tetap]"
                            value="{{ $pv_sdm('karyawan_tidak_tetap') }}" min="0" step="1"
                            inputmode="numeric" placeholder="Contoh: 3" class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                    @error('sdm.karyawan_tidak_tetap')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Karyawan PNS</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[karyawan_pns]" value="{{ $pv_sdm('karyawan_pns') }}"
                            min="0" step="1" inputmode="numeric" placeholder="0"
                            class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                </div>

                <div>
                    <label class="form-label">Karyawan P3K</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[karyawan_p3k]" value="{{ $pv_sdm('karyawan_p3k') }}"
                            min="0" step="1" inputmode="numeric" placeholder="0"
                            class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                </div>

                <div>
                    <label class="form-label">Karyawan Honorer</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="sdm[karyawan_honorer]"
                            value="{{ $pv_sdm('karyawan_honorer') }}" min="0" step="1"
                            inputmode="numeric" placeholder="0" class="form-input sdm-integer">
                        <span class="text-xs text-gray-500 shrink-0">orang</span>
                    </div>
                </div>

            </div>
        </div>


        {{-- DATA SISWA --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Data Siswa</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-5 gap-y-4">
                <div>
                    <label class="form-label">Jumlah Murid Total</label>
                    <input type="number" name="sdm[jumlah_murid_total]" id="sdm-murid-total"
                        value="{{ $pv_sdm('jumlah_murid_total') }}" min="0" step="1"
                        inputmode="numeric" placeholder="Otomatis"
                        class="form-input sdm-integer bg-gray-50 text-gray-600 cursor-not-allowed" readonly>
                    @error('sdm.jumlah_murid_total')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Jumlah Murid Laki-laki</label>
                    <input type="number" name="sdm[jumlah_murid_laki]" id="sdm-murid-laki"
                        value="{{ $pv_sdm('jumlah_murid_laki') }}" min="0" step="1"
                        inputmode="numeric" placeholder="Contoh: 225" class="form-input sdm-integer">
                    @error('sdm.jumlah_murid_laki')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Jumlah Murid Perempuan</label>
                    <input type="number" name="sdm[jumlah_murid_perempuan]" id="sdm-murid-perempuan"
                        value="{{ $pv_sdm('jumlah_murid_perempuan') }}" min="0" step="1"
                        inputmode="numeric" placeholder="Contoh: 225" class="form-input sdm-integer">
                    @error('sdm.jumlah_murid_perempuan')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <p class="text-[11px] text-gray-400 mt-2">Total otomatis dihitung dari L/P.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-x-5 gap-y-4 mt-3">
                <div>
                    <label class="form-label">Ortu TNI AL</label>
                    <input type="number" name="sdm[murid_ortu_tni_al]"
                        value="{{ $pv_sdm('murid_ortu_tni_al') }}" min="0" step="1"
                        inputmode="numeric" placeholder="Contoh: 5" class="form-input sdm-integer"
                        data-ortu-count>
                    @error('sdm.murid_ortu_tni_al')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Ortu TNI</label>
                    <input type="number" name="sdm[murid_ortu_tni]" value="{{ $pv_sdm('murid_ortu_tni') }}"
                        min="0" step="1" inputmode="numeric" placeholder="Contoh: 8"
                        class="form-input sdm-integer" data-ortu-count>
                    @error('sdm.murid_ortu_tni')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Ortu Polisi</label>
                    <input type="number" name="sdm[murid_ortu_polisi]"
                        value="{{ $pv_sdm('murid_ortu_polisi') }}" min="0" step="1"
                        inputmode="numeric" placeholder="Contoh: 6" class="form-input sdm-integer"
                        data-ortu-count>
                    @error('sdm.murid_ortu_polisi')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Ortu PNS</label>
                    <input type="number" name="sdm[murid_ortu_pns]" value="{{ $pv_sdm('murid_ortu_pns') }}"
                        min="0" step="1" inputmode="numeric" placeholder="Contoh: 20"
                        class="form-input sdm-integer" data-ortu-count>
                    @error('sdm.murid_ortu_pns')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Ortu Pengusaha</label>
                    <input type="number" name="sdm[murid_ortu_pengusaha]"
                        value="{{ $pv_sdm('murid_ortu_pengusaha') }}" min="0" step="1"
                        inputmode="numeric" placeholder="Contoh: 15" class="form-input sdm-integer"
                        data-ortu-count>
                    @error('sdm.murid_ortu_pengusaha')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Ortu Wiraswasta</label>
                    <input type="number" name="sdm[murid_ortu_wiraswasta]"
                        value="{{ $pv_sdm('murid_ortu_wiraswasta') }}" min="0" step="1"
                        inputmode="numeric" placeholder="Contoh: 40" class="form-input sdm-integer"
                        data-ortu-count>
                    @error('sdm.murid_ortu_wiraswasta')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Ortu Buruh</label>
                    <input type="number" name="sdm[murid_ortu_buruh]" value="{{ $pv_sdm('murid_ortu_buruh') }}"
                        min="0" step="1" inputmode="numeric" placeholder="Contoh: 60"
                        class="form-input sdm-integer" data-ortu-count>
                    @error('sdm.murid_ortu_buruh')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Ortu Guru</label>
                    <input type="number" name="sdm[murid_ortu_guru]" value="{{ $pv_sdm('murid_ortu_guru') }}"
                        min="0" step="1" inputmode="numeric" placeholder="Contoh: 10"
                        class="form-input sdm-integer" data-ortu-count>
                    @error('sdm.murid_ortu_guru')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                <div class="flex flex-wrap items-center gap-3 text-xs text-slate-600">
                    <span>Total: <span data-ortu-total>0</span></span>
                    <span>Terisi: <span data-ortu-terisi>0</span></span>
                    <span>Sisa: <span data-ortu-sisa>0</span></span>
                    <span class="text-rose-600 hidden" data-ortu-error>Jumlah melebihi total murid</span>
                </div>
                <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-slate-200">
                    <div class="h-full bg-emerald-500 transition-all" data-ortu-bar style="width: 0%"></div>
                </div>
                <p class="mt-2 text-[11px] text-slate-500">Lainnya dihitung otomatis dari total - terisi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4 mt-3">
                <div>
                    <label class="form-label">Ortu Lainnya (Jumlah)</label>
                    <input type="number" name="sdm[murid_ortu_lainnya_jumlah]"
                        value="{{ $pv_sdm('murid_ortu_lainnya_jumlah') }}" min="0" step="1"
                        inputmode="numeric" placeholder="Otomatis"
                        class="form-input sdm-integer bg-gray-50 text-gray-600 cursor-not-allowed" readonly
                        data-ortu-lainnya>
                    @error('sdm.murid_ortu_lainnya_jumlah')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>


        {{-- PENGHASILAN & JABATAN --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Penghasilan &amp; Jabatan</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-x-5 gap-y-4">

                <div>
                    <label class="form-label">Rata-rata Gaji Guru/bulan (Rp)</label>
                    <input type="number" name="sdm[rata_gaji_guru]" value="{{ $pv_sdm('rata_gaji_guru') }}"
                        min="0" step="1" inputmode="numeric" placeholder="Contoh: 3500000"
                        class="form-input sdm-integer">
                    @error('sdm.rata_gaji_guru')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Rata-rata Gaji Karyawan/bulan (Rp)</label>
                    <input type="number" name="sdm[rata_gaji_karyawan]"
                        value="{{ $pv_sdm('rata_gaji_karyawan') }}" min="0" step="1"
                        inputmode="numeric" placeholder="Contoh: 2800000" class="form-input sdm-integer">
                    @error('sdm.rata_gaji_karyawan')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="form-label">Masa Jabatan Kepsek (tahun)</label>
                    <input type="number" name="sdm[masa_jabatan_kepsek]"
                        value="{{ $pv_sdm('masa_jabatan_kepsek') }}" min="0" step="1"
                        inputmode="numeric" placeholder="Contoh: 4" class="form-input sdm-integer">
                    @error('sdm.masa_jabatan_kepsek')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>


        {{-- CATATAN HAMBATAN --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Catatan Hambatan</span>
            </div>

            <div>
                <label class="form-label">Hambatan &amp; Tantangan</label>
                <textarea name="sdm[hambatan_tantangan]" rows="4"
                    placeholder="Tuliskan hambatan dan tantangan yang dihadapi..." class="form-input resize-none">{{ $pv_sdm('hambatan_tantangan') }}</textarea>

                @error('sdm.hambatan_tantangan')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- UPLOAD DOKUMEN --}}
        <div class="form-section">
            <div class="form-section-title">
                <span class="form-section-bar"></span>
                <span class="form-section-label">Upload Dokumen</span>
            </div>
            <div>
                <label class="form-label text-xs text-gray-500">Unggah file pendukung (Data Guru, SK, Sertifikat
                    Pendidik, dll) — Maks. 10MB per file</label>
                <input type="file" name="dokumen_sdm[]" multiple
                    accept=".png,.jpg,.jpeg,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.csv"
                    class="file-upload-input block w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#eef3f9] file:text-[#162040] hover:file:bg-[#dce5f3] cursor-pointer mt-2"
                    data-preview="preview-sdm">
            </div>
            <div id="preview-sdm" class="file-preview-list mt-2 space-y-1"></div>
            @if ($isEdit && $sekolah->dokumen->where('kategori', 'sdm')->count())
                <div class="mt-3 space-y-1">
                    <p class="text-[10px] text-gray-400 font-medium">File tersimpan:</p>
                    @foreach ($sekolah->dokumen->where('kategori', 'sdm') as $doc)
                        <div
                            class="flex items-center gap-2 text-xs text-gray-600 bg-gray-50 rounded-lg px-3 py-2 group">
                            <x-file-icon :mime="$doc->mime_type" class="w-4 h-4 flex-shrink-0 text-gray-400" />
                            <a href="{{ Storage::url($doc->path) }}" target="_blank"
                                class="hover:text-[#162040] underline truncate flex-1">{{ $doc->nama }}</a>
                            <span
                                class="text-[10px] text-gray-400 flex-shrink-0">{{ round($doc->ukuran_bytes / 1024, 1) }}
                                KB</span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>{{-- end tab-sdm --}}

</form>

<script>
    function switchTab(key) {
        const isEdit = {{ $isEdit ? 'true' : 'false' }};
        const allowedOnCreate = ['identitas', 'program', 'teknologi', 'sarpras', 'sdm'];
        if (!allowedOnCreate.includes(key) && !isEdit) return;

        document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
        document.getElementById('tab-' + key).classList.remove('hidden');

        document.querySelectorAll('.tab-btn').forEach(b => {
            b.classList.remove('border-[#162040]', 'bg-[#eef3f9]', 'text-[#162040]', 'font-semibold');
            b.classList.add('border-transparent', 'text-gray-500', 'font-medium');
        });
        const btn = document.getElementById('tab-btn-' + key);
        btn.classList.remove('border-transparent', 'text-gray-500', 'font-medium');
        btn.classList.add('border-[#162040]', 'bg-[#eef3f9]', 'text-[#162040]', 'font-semibold');
    }

    function syncSelectPlaceholderColor(sel) {
        if (!sel) return;
        if (sel.value) {
            sel.classList.remove('text-gray-400');
            sel.classList.add('text-gray-800');
        } else {
            sel.classList.remove('text-gray-800');
            sel.classList.add('text-gray-400');
        }
    }

    function loadKota(provinsiId) {
        const kotaSelect = document.getElementById('kota_id');
        const kecSelect = document.getElementById('kecamatan_id');
        const kelSelect = document.getElementById('kelurahan_id');

        kotaSelect.innerHTML = '<option value="" selected disabled hidden>— Pilih Kota/Kabupaten —</option>';
        kecSelect.innerHTML = '<option value="" selected disabled hidden>— Pilih Kecamatan —</option>';
        kelSelect.innerHTML = '<option value="" selected disabled hidden>— Pilih Kelurahan —</option>';
        syncSelectPlaceholderColor(kotaSelect);
        syncSelectPlaceholderColor(kecSelect);
        syncSelectPlaceholderColor(kelSelect);
        syncClearBtn(kotaSelect);
        syncClearBtn(kecSelect);
        syncClearBtn(kelSelect);

        if (!provinsiId) return;

        kotaSelect.innerHTML = '<option value="">Memuat...</option>';
        kotaSelect.disabled = true;
        fetch(`/api/kota-kabupaten?provinsi_id=${provinsiId}`)
            .then(r => r.json())
            .then(data => {
                kotaSelect.innerHTML =
                    '<option value="" selected disabled hidden>— Pilih Kota/Kabupaten —</option>';
                data.forEach(k => {
                    const opt = document.createElement('option');
                    opt.value = k.id;
                    opt.textContent = k.nama;
                    kotaSelect.appendChild(opt);
                });
                kotaSelect.disabled = false;
                syncSelectPlaceholderColor(kotaSelect);
            })
            .catch(() => {
                kotaSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                kotaSelect.disabled = false;
                syncSelectPlaceholderColor(kotaSelect);
            });
    }

    function loadKecamatan(kotaId) {
        const kecSelect = document.getElementById('kecamatan_id');
        const kelSelect = document.getElementById('kelurahan_id');

        kecSelect.innerHTML = '<option value="" selected disabled hidden>— Pilih Kecamatan —</option>';
        kelSelect.innerHTML = '<option value="" selected disabled hidden>— Pilih Kelurahan —</option>';
        syncSelectPlaceholderColor(kecSelect);
        syncSelectPlaceholderColor(kelSelect);
        syncClearBtn(kecSelect);
        syncClearBtn(kelSelect);

        if (!kotaId) return;

        kecSelect.innerHTML = '<option value="">Memuat...</option>';
        kecSelect.disabled = true;
        fetch(`/api/kecamatan?kota_id=${kotaId}`)
            .then(r => r.json())
            .then(data => {
                kecSelect.innerHTML = '<option value="" selected disabled hidden>— Pilih Kecamatan —</option>';
                data.forEach(k => {
                    const opt = document.createElement('option');
                    opt.value = k.nama;
                    opt.textContent = k.nama;
                    kecSelect.appendChild(opt);
                });
                kecSelect.disabled = false;
                syncSelectPlaceholderColor(kecSelect);
            })
            .catch(() => {
                kecSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                kecSelect.disabled = false;
                syncSelectPlaceholderColor(kecSelect);
            });
    }

    function loadKelurahan(kecamatanNama, kotaId) {
        const kelSelect = document.getElementById('kelurahan_id');
        kelSelect.innerHTML = '<option value="" selected disabled hidden>— Pilih Kelurahan —</option>';
        syncSelectPlaceholderColor(kelSelect);
        syncClearBtn(kelSelect);

        if (!kecamatanNama || !kotaId) return;

        kelSelect.innerHTML = '<option value="">Memuat...</option>';
        kelSelect.disabled = true;
        fetch(`/api/kelurahan?kecamatan_nama=${encodeURIComponent(kecamatanNama)}&kota_id=${kotaId}`)
            .then(r => r.json())
            .then(data => {
                kelSelect.innerHTML = '<option value="" selected disabled hidden>— Pilih Kelurahan —</option>';
                data.forEach(k => {
                    const opt = document.createElement('option');
                    opt.value = k.nama;
                    opt.textContent = k.nama;
                    kelSelect.appendChild(opt);
                });
                kelSelect.disabled = false;
                syncSelectPlaceholderColor(kelSelect);
            })
            .catch(() => {
                kelSelect.innerHTML = '<option value="">Gagal memuat data</option>';
                kelSelect.disabled = false;
                syncSelectPlaceholderColor(kelSelect);
            });
    }

    function updatePredikat(val) {
        const badge = document.getElementById('predikat-badge');
        const n = parseInt(val);
        let label = 'Predikat',
            cls = 'bg-gray-100 text-gray-400';
        if (!isNaN(n) && val !== '') {
            if (n >= 91) {
                label = 'UNGGUL';
                cls = 'bg-teal-100 text-teal-700';
            } else if (n >= 71) {
                label = 'BAIK SEKALI';
                cls = 'bg-blue-100 text-blue-700';
            } else if (n >= 51) {
                label = 'BAIK';
                cls = 'bg-green-100 text-green-700';
            } else {
                label = 'CUKUP';
                cls = 'bg-yellow-100 text-yellow-700';
            }
        }
        badge.textContent = label;
        badge.className =
            `absolute right-2.5 top-1/2 -translate-y-1/2 text-[10px] font-bold px-2 py-0.5 rounded-full ${cls}`;
    }

    function sanitizeDecimalValue(value) {
        const cleaned = value.replace(/[^0-9.]/g, '');
        const parts = cleaned.split('.');
        return parts.length > 2 ? `${parts.shift()}.${parts.join('')}` : cleaned;
    }

    function sanitizeIntegerValue(value) {
        return value.replace(/[^0-9]/g, '');
    }

    function blockInvalidNumberKeys(event, allowDecimal) {
        const blockedKeys = ['e', 'E', '+', '-'];
        if (blockedKeys.includes(event.key)) {
            event.preventDefault();
            return;
        }

        if (allowDecimal && event.key === '.') {
            const input = event.currentTarget;
            if (input.value.includes('.')) {
                event.preventDefault();
            }
        }
    }

    function initNumericInputs() {
        document.querySelectorAll('.pp-decimal').forEach(function(input) {
            input.addEventListener('keydown', function(event) {
                blockInvalidNumberKeys(event, true);
            });
            input.addEventListener('input', function() {
                this.value = sanitizeDecimalValue(this.value);
            });
            input.addEventListener('paste', function() {
                window.setTimeout(() => {
                    this.value = sanitizeDecimalValue(this.value);
                }, 0);
            });
        });

        document.querySelectorAll('.pp-integer').forEach(function(input) {
            input.addEventListener('keydown', function(event) {
                blockInvalidNumberKeys(event, false);
            });
            input.addEventListener('input', function() {
                this.value = sanitizeIntegerValue(this.value);
            });
            input.addEventListener('paste', function() {
                window.setTimeout(() => {
                    this.value = sanitizeIntegerValue(this.value);
                }, 0);
            });
        });
    }

    // ── Clear button for <select> dropdowns ──────────────────
    function syncClearBtn(sel) {
        const btn = sel._clearBtn;
        if (!btn) return;
        if (sel.value) {
            btn.style.display = 'flex';
            sel.style.paddingRight = '3.5rem';
        } else {
            btn.style.display = 'none';
            sel.style.paddingRight = '';
        }
    }

    function initSelectClear() {
        document.querySelectorAll('select.form-select').forEach(function(sel) {
            const wrapper = sel.parentElement;
            if (!wrapper || !wrapper.classList.contains('relative')) return;

            const btn = document.createElement('button');
            btn.type = 'button';
            btn.title = 'Hapus pilihan';
            Object.assign(btn.style, {
                position: 'absolute',
                right: '2rem',
                top: '50%',
                transform: 'translateY(-50%)',
                width: '15px',
                height: '15px',
                borderRadius: '50%',
                background: '#d1d5db',
                border: 'none',
                cursor: 'pointer',
                display: 'none',
                alignItems: 'center',
                justifyContent: 'center',
                padding: '0',
                fontSize: '8px',
                color: '#6b7280',
                lineHeight: '1',
                zIndex: '10',
            });
            btn.innerHTML = '✕';
            btn.onmouseenter = () => {
                btn.style.background = '#9ca3af';
                btn.style.color = '#fff';
            };
            btn.onmouseleave = () => {
                btn.style.background = '#d1d5db';
                btn.style.color = '#6b7280';
            };
            wrapper.appendChild(btn);
            sel._clearBtn = btn;

            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                sel.value = '';
                sel.dispatchEvent(new Event('change'));
                syncSelectPlaceholderColor(sel);
                syncClearBtn(sel);
            });

            sel.addEventListener('change', function() {
                syncSelectPlaceholderColor(sel);
                syncClearBtn(sel);
            });

            syncSelectPlaceholderColor(sel);
            syncClearBtn(sel);
        });
    }

    function normalizePercentValue(value) {
        const cleaned = sanitizeIntegerValue(value);

        if (cleaned === '') {
            return '';
        }

        const numberValue = Math.min(100, Math.max(0, parseInt(cleaned, 10)));
        return String(numberValue);
    }

    function initSarprasToggle() {
        document.querySelectorAll('.sarpras-toggle').forEach(function(toggle) {
            const targetId = toggle.dataset.target;
            const input = document.getElementById(targetId);
            const wrapper = document.getElementById(targetId + '-wrap');

            if (!input || !wrapper) return;

            function syncSarprasInput() {
                if (toggle.checked) {
                    wrapper.classList.remove('hidden');
                    input.disabled = false;
                    input.required = true;
                } else {
                    wrapper.classList.add('hidden');
                    input.disabled = true;
                    input.required = false;
                }
            }

            toggle.addEventListener('change', syncSarprasInput);
            syncSarprasInput();
        });
    }

    function initSarprasNumericInputs() {
        document.querySelectorAll('.sarpras-percent').forEach(function(input) {
            input.addEventListener('keydown', function(event) {
                blockInvalidNumberKeys(event, false);
            });

            input.addEventListener('input', function() {
                this.value = normalizePercentValue(this.value);
            });

            input.addEventListener('paste', function() {
                window.setTimeout(() => {
                    this.value = normalizePercentValue(this.value);
                }, 0);
            });
        });

        document.querySelectorAll('.sarpras-decimal').forEach(function(input) {
            input.addEventListener('keydown', function(event) {
                blockInvalidNumberKeys(event, true);
            });

            input.addEventListener('input', function() {
                this.value = sanitizeDecimalValue(this.value);
            });

            input.addEventListener('paste', function() {
                window.setTimeout(() => {
                    this.value = sanitizeDecimalValue(this.value);
                }, 0);
            });
        });

        document.querySelectorAll('.sarpras-integer').forEach(function(input) {
            input.addEventListener('keydown', function(event) {
                blockInvalidNumberKeys(event, false);
            });

            input.addEventListener('input', function() {
                this.value = sanitizeIntegerValue(this.value);
            });

            input.addEventListener('paste', function() {
                window.setTimeout(() => {
                    this.value = sanitizeIntegerValue(this.value);
                }, 0);
            });
        });
    }

    function initSdmNumericInputs() {
        document.querySelectorAll('.sdm-integer').forEach(function(input) {
            input.addEventListener('keydown', function(event) {
                blockInvalidNumberKeys(event, false);
            });

            input.addEventListener('input', function() {
                this.value = sanitizeIntegerValue(this.value);
            });

            input.addEventListener('paste', function() {
                window.setTimeout(() => {
                    this.value = sanitizeIntegerValue(this.value);
                }, 0);
            });
        });
    }

    function initSdmMuridTotal() {
        const totalInput = document.getElementById('sdm-murid-total');
        const lakiInput = document.getElementById('sdm-murid-laki');
        const perempuanInput = document.getElementById('sdm-murid-perempuan');

        if (!totalInput || !lakiInput || !perempuanInput) {
            return;
        }

        const syncTotal = function() {
            const lakiRaw = (lakiInput.value || '').trim();
            const perempuanRaw = (perempuanInput.value || '').trim();

            if (lakiRaw === '' && perempuanRaw === '') {
                totalInput.value = '';
                return;
            }

            const laki = parseInt(lakiRaw || '0', 10);
            const perempuan = parseInt(perempuanRaw || '0', 10);
            const total = (isNaN(laki) ? 0 : laki) + (isNaN(perempuan) ? 0 : perempuan);
            totalInput.value = total;
        };

        lakiInput.addEventListener('input', syncTotal);
        perempuanInput.addEventListener('input', syncTotal);
        syncTotal();
    }

    function initSdmOrtuComposition() {
        const lakiInput = document.getElementById('sdm-murid-laki');
        const perempuanInput = document.getElementById('sdm-murid-perempuan');
        const countInputs = document.querySelectorAll('[data-ortu-count]');
        const lainnyaInput = document.querySelector('[data-ortu-lainnya]');
        const totalOut = document.querySelector('[data-ortu-total]');
        const terisiOut = document.querySelector('[data-ortu-terisi]');
        const sisaOut = document.querySelector('[data-ortu-sisa]');
        const errorOut = document.querySelector('[data-ortu-error]');
        const bar = document.querySelector('[data-ortu-bar]');

        if (!lakiInput || !perempuanInput || !lainnyaInput || !countInputs.length) {
            return;
        }

        const toNumber = function(value) {
            const parsed = parseInt((value || '').toString().trim(), 10);
            return Number.isNaN(parsed) ? 0 : parsed;
        };

        const recalc = function() {
            const total = toNumber(lakiInput.value) + toNumber(perempuanInput.value);
            let terisi = 0;
            countInputs.forEach(function(input) {
                terisi += toNumber(input.value);
            });

            const sisa = total - terisi;
            const ratio = total > 0 ? Math.min(1, Math.max(0, terisi / total)) : 0;

            if (total === 0 && terisi === 0) {
                lainnyaInput.value = '';
            } else {
                lainnyaInput.value = Math.max(0, sisa);
            }

            if (totalOut) totalOut.textContent = total;
            if (terisiOut) terisiOut.textContent = terisi;
            if (sisaOut) sisaOut.textContent = sisa;

            if (bar) {
                bar.style.width = (ratio * 100) + '%';
                bar.classList.toggle('bg-rose-500', sisa < 0);
                bar.classList.toggle('bg-emerald-500', sisa >= 0);
            }

            if (errorOut) {
                errorOut.classList.toggle('hidden', sisa >= 0);
            }
        };

        countInputs.forEach(function(input) {
            input.addEventListener('input', recalc);
        });
        lakiInput.addEventListener('input', recalc);
        perempuanInput.addEventListener('input', recalc);
        recalc();
    }

    document.addEventListener('DOMContentLoaded', function() {
        initSelectClear();
        initNumericInputs();
        initSarprasToggle();
        initSarprasNumericInputs();
        initSdmNumericInputs();
        initSdmMuridTotal();
        initSdmOrtuComposition();
        initFilePreviews();
    });

    function fileIconSvg(type) {
        if (type.startsWith('image/')) {
            return '<svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>';
        }
        if (type === 'application/pdf') {
            return '<svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>';
        }
        if (type.includes('word') || type.includes('document')) {
            return '<svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
        }
        if (type.includes('sheet') || type.includes('excel') || type.includes('csv')) {
            return '<svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
        }
        return '<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    function initFilePreviews() {
        document.querySelectorAll('.file-upload-input').forEach(function(input) {
            var previewId = input.getAttribute('data-preview');
            var container = document.getElementById(previewId);
            if (!container) return;

            input.addEventListener('change', function() {
                container.innerHTML = '';
                var files = Array.from(input.files);
                if (files.length === 0) {
                    container.innerHTML = '';
                    return;
                }

                var heading = document.createElement('p');
                heading.className = 'text-[10px] text-gray-400 font-medium mb-1';
                heading.textContent = files.length + ' file dipilih:';
                container.appendChild(heading);

                files.forEach(function(file, idx) {
                    var row = document.createElement('div');
                    row.className =
                        'flex items-center gap-2 text-xs text-gray-700 bg-blue-50/60 rounded-lg px-3 py-2';

                    var iconHtml = fileIconSvg(file.type);

                    var nameSpan = document.createElement('span');
                    nameSpan.className = 'truncate flex-1 font-medium';
                    nameSpan.textContent = file.name;

                    var sizeSpan = document.createElement('span');
                    sizeSpan.className = 'text-[10px] text-gray-400 flex-shrink-0';
                    sizeSpan.textContent = formatFileSize(file.size);

                    // Image preview thumbnail
                    if (file.type.startsWith('image/')) {
                        var img = document.createElement('img');
                        img.className =
                            'w-8 h-8 rounded object-cover flex-shrink-0 border border-gray-200';
                        var url = URL.createObjectURL(file);
                        img.src = url;
                        img.onload = function() {
                            URL.revokeObjectURL(url);
                        };
                        row.innerHTML = '';
                        row.appendChild(img);
                        var textWrap = document.createElement('div');
                        textWrap.className = 'flex-1 min-w-0 flex items-center gap-2';
                        textWrap.innerHTML = iconHtml + nameSpan.outerHTML;
                        row.appendChild(textWrap);
                        row.appendChild(sizeSpan);
                    } else {
                        row.innerHTML = iconHtml + nameSpan.outerHTML + sizeSpan.outerHTML;
                    }

                    container.appendChild(row);
                });
            });
        });
    }
</script>
