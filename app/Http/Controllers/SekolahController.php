<?php

namespace App\Http\Controllers;

use App\Models\KotaKabupaten;
use App\Models\ProgramPendidikan;
use App\Models\Provinsi;
use App\Models\SaranaPrasarana;
use App\Models\Sdm;
use App\Models\Sekolah;
use App\Models\TeknologiPembelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class SekolahController extends Controller
{
    private function authorizeCreateSekolah(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless(
            $user->canCreateSekolahData(),
            403,
            'Hanya Super Admin yang dapat mengubah data sekolah.'
        );
    }

    private function authorizeManageExistingSekolah(Sekolah $sekolah): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless($user->canManageSekolahData(), 403, 'Anda tidak memiliki akses untuk mengubah data sekolah.');

        $canManage = $user
            ->applySekolahScope(Sekolah::query()->whereKey($sekolah->id))
            ->exists();

        abort_unless($canManage, 403, 'Anda tidak memiliki akses ke data sekolah ini.');
    }

    private function authorizeReferenceDataAccess(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        abort_unless(
            $user->isSuperAdmin() || $user->isKepalaSekolah(),
            403,
            'Anda tidak memiliki akses ke data referensi wilayah.'
        );
    }

    private function authorizeViewSekolah(Sekolah $sekolah): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $canView = $user
            ->applySekolahScope(Sekolah::query()->whereKey($sekolah->id))
            ->exists();

        abort_unless($canView, 403, 'Anda tidak memiliki akses ke data sekolah ini.');
    }

    /* ─── LIST ─────────────────────────────────────────── */
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $query = $user
            ->applySekolahScope(Sekolah::with(['kota', 'provinsi']))
            ->where('status_operasional', 'aktif');

        if ($request->filled('jenjang') && $request->jenjang !== 'Semua Jenjang') {
            $query->where('jenjang', $request->jenjang);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('npsn', 'like', "%{$search}%")
                    ->orWhereHas('kota', fn($k) => $k->where('name', 'like', "%{$search}%"));
            });
        }

        $sekolahList = $query->orderBy('jenjang')->orderBy('nama')->get();
        $total       = $sekolahList->count();

        return view('sekolah.index', compact('sekolahList', 'total'));
    }

    /* ─── CREATE FORM ──────────────────────────────────── */
    public function create()
    {
        $this->authorizeCreateSekolah();

        $provinsiList = Provinsi::orderBy('name')->get();
        $kotaList     = collect();
        $tahunList    = range(date('Y'), 1900);

        return view('sekolah.create', compact('provinsiList', 'kotaList', 'tahunList'));
    }

    /* ─── STORE ─────────────────────────────────────────── */
    public function store(Request $request)
    {
        $this->authorizeCreateSekolah();

        $validated = $request->validate(
            array_merge($this->rules(), $this->programPendidikanRules(), $this->teknologiRules(), $this->sarprasRules(), $this->sdmRules()),
            $this->messages()
        );
        $validated = $this->enrichAkreditasiPredikat($validated);

        $sekolah = Sekolah::create($validated);

        $this->saveProgramPendidikan($request, $sekolah);
        $this->saveTeknologiPembelajaran($request, $sekolah);
        $this->saveSaranaPrasarana($request, $sekolah);
        $this->saveSdm($request, $sekolah);

        return redirect()->route('sekolah.index')
            ->with('success', "Sekolah \"{$sekolah->nama}\" berhasil ditambahkan.");
    }

    /* ─── SHOW ──────────────────────────────────────────── */
    public function show(Sekolah $sekolah)
    {
        $this->authorizeViewSekolah($sekolah);

        $sekolah->load(['kota', 'provinsi']);
        return view('sekolah.show', compact('sekolah'));
    }

    /* ─── EDIT FORM ─────────────────────────────────────── */
    public function edit(Sekolah $sekolah)
    {
        $this->authorizeManageExistingSekolah($sekolah);

        $sekolah->load(['kota', 'provinsi']);
        $provinsiList   = Provinsi::orderBy('name')->get();
        $kotaList       = $sekolah->provinsi
            ? KotaKabupaten::where('province_code', $sekolah->provinsi->code)->orderBy('name')->get()
            : collect();
        $kecamatanList  = $sekolah->kota
            ? $this->districtCollectionByCityCode($sekolah->kota->code)
            : collect();
        $kecamatanObj   = ($sekolah->kota && $sekolah->kecamatan)
            ? $this->findDistrictByNameAndCityCode($sekolah->kecamatan, $sekolah->kota->code)
            : null;
        $kelurahanList  = $kecamatanObj
            ? $this->villageCollectionByDistrictCode($kecamatanObj->code)
            : collect();
        $tahunList          = range(date('Y'), 1900);
        $programPendidikan  = $sekolah->programPendidikan()->where('tahun_ajaran', '2024/2025')->first()
            ?? $sekolah->programPendidikan()->orderBy('tahun_ajaran', 'desc')->first();
        $teknologiPembelajaran = $sekolah->teknologiPembelajaran()->where('tahun_ajaran', '2024/2025')->first()
            ?? $sekolah->teknologiPembelajaran()->orderBy('tahun_ajaran', 'desc')->first();
        $saranaPrasarana = $sekolah->saranaPrasarana()->where('tahun_ajaran', '2024/2025')->first()
            ?? $sekolah->saranaPrasarana()->orderBy('tahun_ajaran', 'desc')->first();
        $sdm = $sekolah->sdm()->orderBy('tahun_ajaran', 'desc')->first();

        return view('sekolah.edit', compact('sekolah', 'provinsiList', 'kotaList', 'kecamatanList', 'kelurahanList', 'tahunList', 'programPendidikan', 'teknologiPembelajaran', 'saranaPrasarana', 'sdm'));
    }

    /* ─── UPDATE ────────────────────────────────────────── */
    public function update(Request $request, Sekolah $sekolah)
    {
        $this->authorizeManageExistingSekolah($sekolah);

        $validated = $request->validate(
            array_merge($this->rules($sekolah->id), $this->programPendidikanRules(), $this->teknologiRules(), $this->sarprasRules(), $this->sdmRules()),
            $this->messages()
        );
        $validated = $this->enrichAkreditasiPredikat($validated);

        $sekolah->update($validated);

        $this->saveProgramPendidikan($request, $sekolah);
        $this->saveTeknologiPembelajaran($request, $sekolah);
        $this->saveSaranaPrasarana($request, $sekolah);
        $this->saveSdm($request, $sekolah);

        return redirect()->route('sekolah.index')
            ->with('success', "Data \"{$sekolah->nama}\" berhasil diperbarui.");
    }

    /* ─── DESTROY ───────────────────────────────────────── */
    public function destroy(Sekolah $sekolah)
    {
        $this->authorizeManageExistingSekolah($sekolah);

        $nama = $sekolah->nama;
        $sekolah->delete();

        return redirect()->route('sekolah.index')
            ->with('success', "Sekolah \"{$nama}\" berhasil dihapus.");
    }

    /* ─── AJAX: kota by provinsi ─────────────────────────── */
    public function kotaByProvinsi(Request $request)
    {
        $this->authorizeReferenceDataAccess();

        $province = Provinsi::query()->find($request->provinsi_id);
        if (! $province) {
            return response()->json([]);
        }

        $kota = KotaKabupaten::query()
            ->where('province_code', $province->code)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn($city) => [
                'id' => $city->id,
                'nama' => $city->name,
                'jenis' => $city->jenis,
            ]);

        return response()->json($kota);
    }

    /* ─── AJAX: kecamatan by kota ────────────────────────── */
    public function kecamatanByKota(Request $request)
    {
        $this->authorizeReferenceDataAccess();

        $city = KotaKabupaten::query()->find($request->kota_id);
        if (! $city) {
            return response()->json([]);
        }

        $kecamatan = $this->districtCollectionByCityCode($city->code)
            ->map(fn($district) => [
                'id' => $district->id,
                'nama' => $district->nama,
            ]);

        return response()->json($kecamatan);
    }

    /* ─── AJAX: kelurahan by kecamatan ───────────────────── */
    public function kelurahanByKecamatan(Request $request)
    {
        $this->authorizeReferenceDataAccess();

        $city = KotaKabupaten::query()->find($request->kota_id);
        if (! $city) {
            return response()->json([]);
        }

        $kec = $this->findDistrictByNameAndCityCode((string) $request->kecamatan_nama, $city->code);

        if (! $kec) {
            return response()->json([]);
        }

        return response()->json($this->villageCollectionByDistrictCode($kec->code)->map(fn($village) => [
            'nama' => $village->nama,
        ]));
    }

    /* ─── HELPERS ──────────────────────────────────────── */
    private function rules(?int $ignoreId = null): array
    {
        return [
            'npsn'                => ['required', 'string', 'max:20', 'unique:sekolah,npsn' . ($ignoreId ? ",{$ignoreId}" : '')],
            'nama'                => ['required', 'string', 'max:255'],
            'jenjang'             => ['required', 'in:KB,TK,SD,SMP,SMA,SMK'],
            'provinsi_id'         => ['required', 'exists:indonesia_provinces,id'],
            'kota_id'             => ['nullable', 'exists:indonesia_cities,id'],
            'kecamatan'           => ['nullable', 'string', 'max:100'],
            'kelurahan'           => ['nullable', 'string', 'max:100'],
            'kode_pos'            => ['nullable', 'string', 'max:10'],
            'alamat'              => ['nullable', 'string'],
            'telepon'             => ['nullable', 'string', 'max:30'],
            'fax'                 => ['nullable', 'string', 'max:30'],
            'email'               => ['nullable', 'email', 'max:255'],
            'website'             => ['nullable', 'url', 'max:255'],
            'akreditasi_nilai'    => ['nullable', 'integer', 'min:0', 'max:100'],
            'akreditasi_tahun'    => ['nullable', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'no_sk_akreditasi'    => ['nullable', 'string', 'max:100'],
            'kepala_sekolah_nama' => ['nullable', 'string', 'max:255'],
            'kepala_sekolah_nip'  => ['nullable', 'string', 'max:30'],
            'kepala_sekolah_hp'   => ['nullable', 'string', 'max:30'],
            'operator_nama'       => ['nullable', 'string', 'max:255'],
            'operator_hp'         => ['nullable', 'string', 'max:30'],
            'tahun_berdiri'       => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'luas_tanah'          => ['nullable', 'numeric', 'min:0'],
            'kekuatan'            => ['nullable', 'string'],
            'kelemahan'           => ['nullable', 'string'],
        ];
    }

    private function programPendidikanRules(): array
    {
        return [
            'pp' => ['sometimes', 'array'],
            'pp.nilai_ujian_ta1' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pp.nilai_ujian_ta2' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pp.pbd_literasi' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pp.pbd_numerasi' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pp.pbd_karakter' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pp.pbd_kualitas_pembelajaran' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pp.pbd_iklim_keamanan' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pp.pbd_iklim_kebhinekaan' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'pp.prestasi_akad_2025_kota' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_akad_2025_provinsi' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_akad_2025_nasional' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_akad_2025_internasional' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_akad_2026_kota' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_akad_2026_provinsi' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_akad_2026_nasional' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_akad_2026_internasional' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_non_2025_kota' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_non_2025_provinsi' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_non_2025_nasional' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_non_2025_internasional' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_non_2026_kota' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_non_2026_provinsi' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_non_2026_nasional' => ['nullable', 'integer', 'min:0'],
            'pp.prestasi_non_2026_internasional' => ['nullable', 'integer', 'min:0'],
            'pp.kurikulum' => ['nullable', 'in:K-13,Kurikulum Merdeka,K-13 dan Merdeka'],
            'pp.kurikulum_kebaharian' => ['nullable', 'in:Sudah berjalan,Belum berjalan,Tidak ada'],
            'pp.jumlah_guru_kebaharian' => ['nullable', 'integer', 'min:0'],
            'pp.penerimaan_bos' => ['nullable', 'in:Menerima,Belum menerima,Tidak menerima'],
            'pp.penerimaan_bop' => ['nullable', 'in:Menerima,Belum menerima,Tidak menerima'],
        ];
    }

    private function teknologiRules(): array
    {
        return [
            'tp' => ['sometimes', 'array'],
            'tp.software_aplikasi_pembelajaran' => ['nullable', 'in:Sudah,Proses uji coba,Belum menggunakan'],
            'tp.lms_kemendikdasmen' => ['nullable', 'in:Sudah,Proses pelatihan,Belum tahu'],
            'tp.aplikasi_smart_classroom' => ['nullable', 'in:Sudah,Dalam proses pemasangan,Belum ada'],
            'tp.koleksi_ebook' => ['nullable', 'in:Sudah ada,Ada tapi sedikit,Belum ada'],
            'tp.website_sekolah' => ['nullable', 'in:Ada,Sedang proses,Belum ada'],
            'tp.server_pembelajaran' => ['nullable', 'in:Sudah ada,Proses pembangunan,Belum ada'],
            'tp.tenaga_khusus_it' => ['nullable', 'in:Ada khusus IT,Ada namun paruh waktu,Belum ada'],
            'tp.media_sosial' => ['nullable', 'array'],
            'tp.media_sosial.*' => ['string', 'in:Instagram,TikTok,YouTube,Facebook'],
            'tp.platform_lms' => ['nullable', 'array'],
            'tp.platform_lms.*' => ['string', 'in:Google Classroom,Moodle,Schoology,Quipper'],
            'tp.platform_pendidikan' => ['nullable', 'array'],
            'tp.platform_pendidikan.*' => ['string', 'in:PMM (Platform Merdeka Mengajar),Rumah Belajar,Ruang Guru & Zenius'],
            'tp.alat_interaktif' => ['nullable', 'array'],
            'tp.alat_interaktif.*' => ['string', 'in:Wordwall,Interactive Flat Panel (IFP),Kahoot,Quiziz'],
            'tp.platform_komunikasi' => ['nullable', 'array'],
            'tp.platform_komunikasi.*' => ['string', 'in:WhatsApp,Zoom Meet,Google Meet'],
            'tp.aplikasi_manajemen' => ['nullable', 'array'],
            'tp.aplikasi_manajemen.*' => ['string', 'in:Portal khusus SPMB,EdConnect,Stela,Si Aka'],
        ];
    }

    private function sarprasRules(): array
    {
        $items = [
            'perpustakaan',
            'laboratorium_ipa',
            'laboratorium_bahasa',
            'laboratorium_komputer',
            'ruang_keterampilan',
            'ruang_seni',
            'ruang_osis',
            'uks_klinik_kesehatan',
            'ruang_kepala_sekolah',
            'ruang_wakil_kepala_sekolah',
            'ruang_tata_usaha',
            'ruang_bendahara',
            'ruang_guru',
            'ruang_bk_konseling',
            'aula_pertemuan',
            'kantin_sekolah',
            'lapangan_olahraga',
            'lab_studio_kebaharian',
            'toilet_terpisah',
            'taman_hijau',
            'tempat_parkir',
            'ruang_ibadah',
            'ape_kb_tk',
            'ifp_dari_pemerintah',
            'laptop_ext_hd_dari_pemerintah',
        ];

        $rules = [
            'sp' => ['sometimes', 'array'],
            'sp.luas_tanah' => ['nullable', 'numeric', 'min:0'],
            'sp.luas_bangunan' => ['nullable', 'numeric', 'min:0'],
            'sp.biaya_sewa_lahan' => ['nullable', 'integer', 'min:0'],
        ];

        foreach ($items as $item) {
            $rules["sp.{$item}_ada"] = ['nullable', 'boolean'];
            $rules["sp.{$item}_kondisi"] = ['nullable', 'integer', 'min:0', 'max:100'];
        }

        return $rules;
    }

    private function sdmRules(): array
    {
        return [
            'sdm' => ['sometimes', 'array'],
            'sdm.jumlah_guru' => ['nullable', 'integer', 'min:0'],
            'sdm.guru_tetap_yayasan' => ['nullable', 'integer', 'min:0'],
            'sdm.guru_tidak_tetap' => ['nullable', 'integer', 'min:0'],
            'sdm.guru_s1_pendidikan' => ['nullable', 'integer', 'min:0'],
            'sdm.guru_s1_non_pendidikan' => ['nullable', 'integer', 'min:0'],
            'sdm.guru_s2' => ['nullable', 'integer', 'min:0'],
            'sdm.guru_s3' => ['nullable', 'integer', 'min:0'],
            'sdm.guru_sertifikasi' => ['nullable', 'integer', 'min:0'],
            'sdm.jumlah_karyawan' => ['nullable', 'integer', 'min:0'],
            'sdm.karyawan_tetap' => ['nullable', 'integer', 'min:0'],
            'sdm.karyawan_tidak_tetap' => ['nullable', 'integer', 'min:0'],
            'sdm.jumlah_rombel' => ['nullable', 'integer', 'min:0'],
            'sdm.jumlah_murid_total' => ['nullable', 'integer', 'min:0'],
            'sdm.jumlah_murid_laki' => ['nullable', 'integer', 'min:0'],
            'sdm.jumlah_murid_perempuan' => ['nullable', 'integer', 'min:0'],
            'sdm.murid_ortu_tni_al' => ['nullable', 'integer', 'min:0'],
            'sdm.murid_ortu_tni' => ['nullable', 'integer', 'min:0'],
            'sdm.murid_ortu_polisi' => ['nullable', 'integer', 'min:0'],
            'sdm.murid_ortu_pns' => ['nullable', 'integer', 'min:0'],
            'sdm.murid_ortu_pengusaha' => ['nullable', 'integer', 'min:0'],
            'sdm.murid_ortu_wiraswasta' => ['nullable', 'integer', 'min:0'],
            'sdm.murid_ortu_buruh' => ['nullable', 'integer', 'min:0'],
            'sdm.murid_ortu_guru' => ['nullable', 'integer', 'min:0'],
            'sdm.murid_ortu_lainnya_jumlah' => ['nullable', 'integer', 'min:0'],
            'sdm.rata_gaji_guru' => ['nullable', 'integer', 'min:0'],
            'sdm.rata_gaji_karyawan' => ['nullable', 'integer', 'min:0'],
            'sdm.masa_jabatan_kepsek' => ['nullable', 'integer', 'min:0'],
            'sdm.hambatan_tantangan' => ['nullable', 'string'],
        ];
    }

    private function messages(): array
    {
        return [
            'npsn.required'        => 'NPSN wajib diisi.',
            'npsn.unique'          => 'NPSN sudah terdaftar.',
            'nama.required'        => 'Nama sekolah wajib diisi.',
            'jenjang.required'     => 'Jenjang wajib dipilih.',
            'jenjang.in'           => 'Jenjang tidak valid.',
            'provinsi_id.required' => 'Provinsi wajib dipilih.',
            'email.email'          => 'Format email tidak valid.',
            'website.url'          => 'Format website tidak valid (harus diawali https://).',
        ];
    }

    private function enrichAkreditasiPredikat(array $data): array
    {
        if (isset($data['akreditasi_nilai']) && $data['akreditasi_nilai'] !== null) {
            $nilai = (int) $data['akreditasi_nilai'];
            $data['akreditasi_predikat'] = match (true) {
                $nilai >= 91 => 'UNGGUL',
                $nilai >= 71 => 'BAIK SEKALI',
                $nilai >= 51 => 'BAIK',
                default      => 'CUKUP',
            };
        }
        return $data;
    }

    private function saveTeknologiPembelajaran(Request $request, Sekolah $sekolah): void
    {
        $tpInput = $request->input('tp', []);
        if (empty($tpInput)) {
            return;
        }

        $toArrayOrNull = static fn($values) => empty($values)
            ? null
            : array_values(array_filter((array) $values, static fn($item) => $item !== null && $item !== ''));

        $softwareStatus = $tpInput['software_aplikasi_pembelajaran'] ?? null;
        $lmsStatus = $tpInput['lms_kemendikdasmen'] ?? null;
        $smartClassStatus = $tpInput['aplikasi_smart_classroom'] ?? null;
        $ebookStatus = $tpInput['koleksi_ebook'] ?? null;
        $websiteStatus = $tpInput['website_sekolah'] ?? null;
        $serverStatus = $tpInput['server_pembelajaran'] ?? null;
        $tenagaItStatus = $tpInput['tenaga_khusus_it'] ?? null;

        $tpData = [
            'tahun_ajaran' => '2024/2025',
            'software_aplikasi_pembelajaran_status' => $softwareStatus ?: null,
            'lms_kemendikdasmen_status' => $lmsStatus ?: null,
            'aplikasi_smart_classroom_status' => $smartClassStatus ?: null,
            'koleksi_ebook_status' => $ebookStatus ?: null,
            'website_sekolah_status' => $websiteStatus ?: null,
            'server_pembelajaran_status' => $serverStatus ?: null,
            'tenaga_khusus_it_status' => $tenagaItStatus ?: null,
            'media_sosial' => $toArrayOrNull($tpInput['media_sosial'] ?? []),
            'platform_lms' => $toArrayOrNull($tpInput['platform_lms'] ?? []),
            'platform_pendidikan' => $toArrayOrNull($tpInput['platform_pendidikan'] ?? []),
            'alat_interaktif' => $toArrayOrNull($tpInput['alat_interaktif'] ?? []),
            'platform_komunikasi' => $toArrayOrNull($tpInput['platform_komunikasi'] ?? []),
            'aplikasi_manajemen' => $toArrayOrNull($tpInput['aplikasi_manajemen'] ?? []),
            'aplikasi_pembelajaran' => $softwareStatus ?: null,
            'memiliki_lms' => $lmsStatus === 'Sudah',
            'memiliki_smart_classroom' => $smartClassStatus === 'Sudah',
            'memiliki_e_perpustakaan' => $ebookStatus === 'Sudah ada',
            'memiliki_tenaga_it' => in_array($tenagaItStatus, ['Ada khusus IT', 'Ada namun paruh waktu'], true),
            'updated_by' => auth()->id(),
        ];

        TeknologiPembelajaran::updateOrCreate(
            ['sekolah_id' => $sekolah->id, 'tahun_ajaran' => '2024/2025'],
            $tpData
        );
    }

    private function saveProgramPendidikan(Request $request, Sekolah $sekolah): void
    {
        $ppInput = $request->input('pp', []);
        if (empty($ppInput)) {
            return;
        }

        $ppData = array_map(fn($v) => ($v === '' ? null : $v), $ppInput);
        $hasInput = collect($ppData)->contains(fn($v) => $v !== null && $v !== '' && $v !== []);

        if (! $hasInput) {
            return;
        }

        ProgramPendidikan::updateOrCreate(
            ['sekolah_id' => $sekolah->id],
            $ppData + [
                'tahun_ajaran' => '2024/2025',
                'updated_by' => auth()->id(),
            ]
        );
    }

    private function saveSaranaPrasarana(Request $request, Sekolah $sekolah): void
    {
        $spInput = $request->input('sp', []);
        if (empty($spInput)) {
            return;
        }

        $items = [
            'perpustakaan',
            'laboratorium_ipa',
            'laboratorium_bahasa',
            'laboratorium_komputer',
            'ruang_keterampilan',
            'ruang_seni',
            'ruang_osis',
            'uks_klinik_kesehatan',
            'ruang_kepala_sekolah',
            'ruang_wakil_kepala_sekolah',
            'ruang_tata_usaha',
            'ruang_bendahara',
            'ruang_guru',
            'ruang_bk_konseling',
            'aula_pertemuan',
            'kantin_sekolah',
            'lapangan_olahraga',
            'lab_studio_kebaharian',
            'toilet_terpisah',
            'taman_hijau',
            'tempat_parkir',
            'ruang_ibadah',
            'ape_kb_tk',
            'ifp_dari_pemerintah',
            'laptop_ext_hd_dari_pemerintah',
        ];

        $toBool = static fn($value): bool => filter_var($value, FILTER_VALIDATE_BOOLEAN) || (string) $value === '1';
        $toNullableInt = static fn($value): ?int => ($value === null || $value === '') ? null : (int) $value;
        $toNullableFloat = static fn($value): ?float => ($value === null || $value === '') ? null : (float) $value;

        $data = [
            'tahun_ajaran' => '2024/2025',
            'updated_by' => auth()->id(),
        ];

        $hasInput = false;
        $scores = [];

        foreach ($items as $item) {
            $adaField = "{$item}_ada";
            $kondisiField = "{$item}_kondisi";

            $isAvailable = $toBool($spInput[$adaField] ?? false);
            $condition = $toNullableInt($spInput[$kondisiField] ?? null);
            $condition = $isAvailable ? $condition : null;

            $data[$adaField] = $isAvailable;
            $data[$kondisiField] = $condition;

            if ($isAvailable || $condition !== null) {
                $hasInput = true;
            }

            if ($isAvailable && $condition !== null) {
                $scores[] = $condition;
            }
        }

        $luasTanah = $toNullableFloat($spInput['luas_tanah'] ?? null);
        $luasBangunan = $toNullableFloat($spInput['luas_bangunan'] ?? null);
        $biayaSewaLahan = $toNullableInt($spInput['biaya_sewa_lahan'] ?? null);

        if ($luasTanah !== null || $luasBangunan !== null || $biayaSewaLahan !== null) {
            $hasInput = true;
        }

        if (! $hasInput) {
            return;
        }

        $jenisLaboratorium = [];
        if ($data['laboratorium_ipa_ada'] ?? false) {
            $jenisLaboratorium[] = 'IPA';
        }
        if ($data['laboratorium_bahasa_ada'] ?? false) {
            $jenisLaboratorium[] = 'Bahasa';
        }
        if ($data['laboratorium_komputer_ada'] ?? false) {
            $jenisLaboratorium[] = 'Komputer';
        }
        if ($data['lab_studio_kebaharian_ada'] ?? false) {
            $jenisLaboratorium[] = 'Kebaharian';
        }

        $data += [
            'luas_tanah' => $luasTanah,
            'luas_bangunan' => $luasBangunan,
            'biaya_sewa_lahan' => $biayaSewaLahan,
            // Mapping backward-compatible untuk laporan lama.
            'memiliki_perpustakaan' => (bool) ($data['perpustakaan_ada'] ?? false),
            'kondisi_perpustakaan' => $this->sarprasLegacyConditionFromPercent($data['perpustakaan_kondisi'] ?? null),
            'memiliki_laboratorium' => ! empty($jenisLaboratorium),
            'jenis_laboratorium' => empty($jenisLaboratorium) ? null : implode(', ', $jenisLaboratorium),
            'memiliki_uks' => (bool) ($data['uks_klinik_kesehatan_ada'] ?? false),
            'kondisi_uks' => $this->sarprasLegacyConditionFromPercent($data['uks_klinik_kesehatan_kondisi'] ?? null),
            'memiliki_lapangan' => (bool) ($data['lapangan_olahraga_ada'] ?? false),
            'kondisi_lapangan' => $this->sarprasLegacyConditionFromPercent($data['lapangan_olahraga_kondisi'] ?? null),
            'luas_bangunan_m2' => $luasBangunan,
            'status_kepemilikan' => ($biayaSewaLahan !== null && $biayaSewaLahan > 0) ? 'sewa' : null,
            'skor_rata_rata' => $this->sarprasAverageScore($scores),
        ];

        SaranaPrasarana::updateOrCreate(
            ['sekolah_id' => $sekolah->id, 'tahun_ajaran' => '2024/2025'],
            $data
        );
    }

    private function sarprasLegacyConditionFromPercent(?int $percent): ?string
    {
        if ($percent === null) {
            return null;
        }

        return match (true) {
            $percent >= 85 => 'baik',
            $percent >= 65 => 'rusak_ringan',
            $percent >= 40 => 'rusak_sedang',
            default => 'rusak_berat',
        };
    }

    private function sarprasAverageScore(array $scores): ?float
    {
        if (empty($scores)) {
            return null;
        }

        return round(array_sum($scores) / count($scores), 2);
    }

    private function saveSdm(Request $request, Sekolah $sekolah): void
    {
        $sdmInput = $request->input('sdm', []);
        if (empty($sdmInput)) {
            return;
        }

        $hasInput = collect($sdmInput)->contains(function ($value) {
            if (is_array($value)) {
                return collect($value)->contains(fn($item) => $item !== null && $item !== '');
            }

            return $value !== null && $value !== '';
        });

        if (! $hasInput) {
            return;
        }

        $toInt = static fn($value): int => ($value === null || $value === '') ? 0 : (int) $value;
        $toNullableInt = static fn($value): ?int => ($value === null || $value === '') ? null : (int) $value;
        $toNullableString = static fn($value): ?string => ($value === null || trim((string) $value) === '') ? null : trim((string) $value);

        $guruTetapYayasan = $toNullableInt($sdmInput['guru_tetap_yayasan'] ?? null);
        $guruTidakTetap = $toNullableInt($sdmInput['guru_tidak_tetap'] ?? null);
        $jumlahGuru = $toNullableInt($sdmInput['jumlah_guru'] ?? null);

        if ($jumlahGuru === null) {
            $jumlahGuru = ($guruTetapYayasan ?? 0) + ($guruTidakTetap ?? 0);
        }

        $karyawanTetap = $toNullableInt($sdmInput['karyawan_tetap'] ?? null);
        $karyawanTidakTetap = $toNullableInt($sdmInput['karyawan_tidak_tetap'] ?? null);
        $jumlahKaryawan = $toNullableInt($sdmInput['jumlah_karyawan'] ?? null);

        if ($jumlahKaryawan === null) {
            $jumlahKaryawan = ($karyawanTetap ?? 0) + ($karyawanTidakTetap ?? 0);
        }

        $muridLaki = $toInt($sdmInput['jumlah_murid_laki'] ?? 0);
        $muridPerempuan = $toInt($sdmInput['jumlah_murid_perempuan'] ?? 0);
        $muridTotal = $muridLaki + $muridPerempuan;

        if ($muridTotal === 0) {
            $muridTotal = $toInt($sdmInput['jumlah_murid_total'] ?? 0);
        }

        $ortuFields = [
            'murid_ortu_tni_al',
            'murid_ortu_tni',
            'murid_ortu_polisi',
            'murid_ortu_pns',
            'murid_ortu_pengusaha',
            'murid_ortu_wiraswasta',
            'murid_ortu_buruh',
            'murid_ortu_guru',
        ];

        $jumlahOrtu = 0;
        foreach ($ortuFields as $field) {
            $jumlahOrtu += (int) ($sdmInput[$field] ?? 0);
        }

        if ($jumlahOrtu > $muridTotal) {
            throw ValidationException::withMessages([
                'sdm.murid_ortu_lainnya_jumlah' => 'Jumlah pekerjaan orang tua melebihi total murid.',
            ]);
        }

        $lainnyaJumlah = max(0, $muridTotal - $jumlahOrtu);

        $guruS1Pendidikan = $toInt($sdmInput['guru_s1_pendidikan'] ?? 0);
        $guruS1NonPendidikan = $toInt($sdmInput['guru_s1_non_pendidikan'] ?? 0);
        $guruS2 = $toInt($sdmInput['guru_s2'] ?? 0);
        $guruS3 = $toInt($sdmInput['guru_s3'] ?? 0);
        $guruSertifikasi = $toInt($sdmInput['guru_sertifikasi'] ?? 0);
        $hambatanTantangan = $toNullableString($sdmInput['hambatan_tantangan'] ?? null);

        $data = [
            'tahun_ajaran' => '2024/2025',
            'jumlah_guru' => (int) ($jumlahGuru ?? 0),
            'guru_tetap_yayasan' => (int) ($guruTetapYayasan ?? 0),
            'guru_tidak_tetap' => (int) ($guruTidakTetap ?? 0),
            'guru_s1_pendidikan' => $guruS1Pendidikan,
            'guru_s1_non_pendidikan' => $guruS1NonPendidikan,
            'guru_s2' => $guruS2,
            'guru_s3' => $guruS3,
            'guru_sertifikasi' => $guruSertifikasi,
            'jumlah_karyawan' => (int) ($jumlahKaryawan ?? 0),
            'karyawan_tetap' => (int) ($karyawanTetap ?? 0),
            'karyawan_tidak_tetap' => (int) ($karyawanTidakTetap ?? 0),
            'jumlah_rombel' => $toInt($sdmInput['jumlah_rombel'] ?? 0),
            'jumlah_murid_total' => $muridTotal,
            'jumlah_murid_laki' => $muridLaki,
            'jumlah_murid_perempuan' => $muridPerempuan,
            'murid_ortu_tni_al' => $toInt($sdmInput['murid_ortu_tni_al'] ?? 0),
            'murid_ortu_tni' => $toInt($sdmInput['murid_ortu_tni'] ?? 0),
            'murid_ortu_polisi' => $toInt($sdmInput['murid_ortu_polisi'] ?? 0),
            'murid_ortu_pns' => $toInt($sdmInput['murid_ortu_pns'] ?? 0),
            'murid_ortu_pengusaha' => $toInt($sdmInput['murid_ortu_pengusaha'] ?? 0),
            'murid_ortu_wiraswasta' => $toInt($sdmInput['murid_ortu_wiraswasta'] ?? 0),
            'murid_ortu_buruh' => $toInt($sdmInput['murid_ortu_buruh'] ?? 0),
            'murid_ortu_guru' => $toInt($sdmInput['murid_ortu_guru'] ?? 0),
            'murid_ortu_lainnya_label' => null,
            'murid_ortu_lainnya_jumlah' => $lainnyaJumlah,
            'rata_gaji_guru' => $toNullableInt($sdmInput['rata_gaji_guru'] ?? null),
            'rata_gaji_karyawan' => $toNullableInt($sdmInput['rata_gaji_karyawan'] ?? null),
            'masa_jabatan_kepsek' => $toNullableInt($sdmInput['masa_jabatan_kepsek'] ?? null),
            'hambatan_tantangan' => $hambatanTantangan,
            // Mapping backward-compatible agar modul rekap/cetak lama tetap terbaca.
            'guru_pns' => (int) ($guruTetapYayasan ?? 0),
            'guru_honorer' => (int) ($guruTidakTetap ?? max(0, (int) ($jumlahGuru ?? 0) - (int) ($guruTetapYayasan ?? 0))),
            'guru_p3k' => 0,
            'karyawan_pns' => (int) ($karyawanTetap ?? 0),
            'karyawan_honorer' => (int) ($karyawanTidakTetap ?? max(0, (int) ($jumlahKaryawan ?? 0) - (int) ($karyawanTetap ?? 0))),
            'karyawan_p3k' => 0,
            'guru_bersertifikasi' => $guruSertifikasi,
            'guru_s1_keatas' => $guruS1Pendidikan + $guruS1NonPendidikan + $guruS2 + $guruS3,
            'catatan_hambatan' => $hambatanTantangan,
            'updated_by' => auth()->id(),
        ];

        Sdm::updateOrCreate(
            ['sekolah_id' => $sekolah->id, 'tahun_ajaran' => '2024/2025'],
            $data
        );
    }

    private function districtCollectionByCityCode(string $cityCode): Collection
    {
        return District::query()
            ->where('city_code', $cityCode)
            ->orderBy('name')
            ->get(['id', 'code', 'name'])
            ->map(fn($district) => (object) [
                'id' => $district->id,
                'code' => $district->code,
                'nama' => $district->name,
            ]);
    }

    private function findDistrictByNameAndCityCode(string $name, string $cityCode): ?District
    {
        $normalized = strtolower(trim($name));
        if ($normalized === '') {
            return null;
        }

        return District::query()
            ->where('city_code', $cityCode)
            ->whereRaw('LOWER(name) = ?', [$normalized])
            ->first();
    }

    private function villageCollectionByDistrictCode(string $districtCode): Collection
    {
        return Village::query()
            ->where('district_code', $districtCode)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn($village) => (object) [
                'id' => $village->id,
                'nama' => $village->name,
            ]);
    }
}
