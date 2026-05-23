<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use App\Models\KotaKabupaten;
use App\Models\ProgramPendidikan;
use App\Models\Provinsi;
use App\Models\Sekolah;
use App\Models\TeknologiPembelajaran;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    /* ─── LIST ─────────────────────────────────────────── */
    public function index(Request $request)
    {
        $query = Sekolah::with(['kota', 'provinsi'])
            ->where('status_operasional', 'aktif');

        if ($request->filled('jenjang') && $request->jenjang !== 'Semua Jenjang') {
            $query->where('jenjang', $request->jenjang);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('npsn', 'like', "%{$search}%")
                    ->orWhereHas('kota', fn($k) => $k->where('nama', 'like', "%{$search}%"));
            });
        }

        $sekolahList = $query->orderBy('jenjang')->orderBy('nama')->get();
        $total       = $sekolahList->count();

        return view('sekolah.index', compact('sekolahList', 'total'));
    }

    /* ─── CREATE FORM ──────────────────────────────────── */
    public function create()
    {
        $provinsiList = Provinsi::orderBy('nama')->get();
        $kotaList     = collect();
        $tahunList    = range(date('Y'), 1900);

        return view('sekolah.create', compact('provinsiList', 'kotaList', 'tahunList'));
    }

    /* ─── STORE ─────────────────────────────────────────── */
    public function store(Request $request)
    {
        $validated = $request->validate(
            array_merge($this->rules(), $this->programPendidikanRules(), $this->teknologiRules()),
            $this->messages()
        );
        $validated = $this->enrichAkreditasiPredikat($validated);

        $sekolah = Sekolah::create($validated);

        // Simpan data Program Pendidikan jika ada
        $ppInput = $request->input('pp', []);
        if (!empty($ppInput)) {
            $ppData = array_map(fn($v) => ($v === '' ? null : $v), $ppInput);
            ProgramPendidikan::updateOrCreate(
                ['sekolah_id' => $sekolah->id],
                $ppData + ['updated_by' => auth()->id()]
            );
        }

        $this->saveTeknologiPembelajaran($request, $sekolah);

        return redirect()->route('sekolah.index')
            ->with('success', "Sekolah \"{$sekolah->nama}\" berhasil ditambahkan.");
    }

    /* ─── SHOW ──────────────────────────────────────────── */
    public function show(Sekolah $sekolah)
    {
        $sekolah->load(['kota', 'provinsi']);
        return view('sekolah.show', compact('sekolah'));
    }

    /* ─── EDIT FORM ─────────────────────────────────────── */
    public function edit(Sekolah $sekolah)
    {
        $sekolah->load(['kota', 'provinsi']);
        $provinsiList   = Provinsi::orderBy('nama')->get();
        $kotaList       = $sekolah->provinsi_id
            ? KotaKabupaten::where('provinsi_id', $sekolah->provinsi_id)->orderBy('nama')->get()
            : collect();
        $kecamatanList  = $sekolah->kota_id
            ? Kecamatan::where('kota_kabupaten_id', $sekolah->kota_id)->orderBy('nama')->get()
            : collect();
        $kecamatanObj   = ($sekolah->kota_id && $sekolah->kecamatan)
            ? Kecamatan::where('nama', $sekolah->kecamatan)->where('kota_kabupaten_id', $sekolah->kota_id)->first()
            : null;
        $kelurahanList  = $kecamatanObj
            ? \App\Models\Kelurahan::where('kecamatan_id', $kecamatanObj->id)->orderBy('nama')->get()
            : collect();
        $tahunList          = range(date('Y'), 1900);
        $programPendidikan  = $sekolah->programPendidikan()->first();
        $teknologiPembelajaran = $sekolah->teknologiPembelajaran()->first();

        return view('sekolah.edit', compact('sekolah', 'provinsiList', 'kotaList', 'kecamatanList', 'kelurahanList', 'tahunList', 'programPendidikan', 'teknologiPembelajaran'));
    }

    /* ─── UPDATE ────────────────────────────────────────── */
    public function update(Request $request, Sekolah $sekolah)
    {
        $validated = $request->validate(
            array_merge($this->rules($sekolah->id), $this->programPendidikanRules(), $this->teknologiRules()),
            $this->messages()
        );
        $validated = $this->enrichAkreditasiPredikat($validated);

        $sekolah->update($validated);

        // Simpan data Program Pendidikan jika ada
        $ppInput = $request->input('pp', []);
        if (!empty($ppInput)) {
            $ppData = array_map(fn($v) => ($v === '' ? null : $v), $ppInput);
            ProgramPendidikan::updateOrCreate(
                ['sekolah_id' => $sekolah->id],
                $ppData + ['updated_by' => auth()->id()]
            );
        }

        $this->saveTeknologiPembelajaran($request, $sekolah);

        return redirect()->route('sekolah.index')
            ->with('success', "Data \"{$sekolah->nama}\" berhasil diperbarui.");
    }

    /* ─── DESTROY ───────────────────────────────────────── */
    public function destroy(Sekolah $sekolah)
    {
        $nama = $sekolah->nama;
        $sekolah->delete();

        return redirect()->route('sekolah.index')
            ->with('success', "Sekolah \"{$nama}\" berhasil dihapus.");
    }

    /* ─── AJAX: kota by provinsi ─────────────────────────── */
    public function kotaByProvinsi(Request $request)
    {
        $kota = KotaKabupaten::where('provinsi_id', $request->provinsi_id)
            ->orderBy('nama')
            ->get(['id', 'nama', 'jenis']);

        return response()->json($kota);
    }

    /* ─── AJAX: kecamatan by kota ────────────────────────── */
    public function kecamatanByKota(Request $request)
    {
        $kecamatan = Kecamatan::where('kota_kabupaten_id', $request->kota_id)
            ->orderBy('nama')
            ->get(['id', 'nama']);

        return response()->json($kecamatan);
    }

    /* ─── AJAX: kelurahan by kecamatan ───────────────────── */
    public function kelurahanByKecamatan(Request $request)
    {
        $kec = Kecamatan::where('nama', $request->kecamatan_nama)
            ->where('kota_kabupaten_id', $request->kota_id)
            ->first();

        if (! $kec) {
            return response()->json([]);
        }

        return response()->json(
            \App\Models\Kelurahan::where('kecamatan_id', $kec->id)->orderBy('nama')->get(['nama'])
        );
    }

    /* ─── HELPERS ──────────────────────────────────────── */
    private function rules(?int $ignoreId = null): array
    {
        return [
            'npsn'                => ['required', 'string', 'max:20', 'unique:sekolah,npsn' . ($ignoreId ? ",{$ignoreId}" : '')],
            'nama'                => ['required', 'string', 'max:255'],
            'jenjang'             => ['required', 'in:KB,TK,SD,SMP,SMA,SMK'],
            'provinsi_id'         => ['required', 'exists:provinsi,id'],
            'kota_id'             => ['nullable', 'exists:kota_kabupaten,id'],
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
}
