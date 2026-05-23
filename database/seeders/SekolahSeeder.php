<?php

namespace Database\Seeders;

use App\Models\KotaKabupaten;
use App\Models\Provinsi;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SekolahSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Sekolah::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $jakarta  = Provinsi::where('kode', '31')->value('id');
        $jawatim  = Provinsi::where('kode', '35')->value('id');
        $sulsel   = Provinsi::where('kode', '73')->value('id');
        $sulut    = Provinsi::where('kode', '71')->value('id');

        $jktSel   = KotaKabupaten::where('kode', '3171')->value('id');
        $jktUt    = KotaKabupaten::where('kode', '3175')->value('id');
        $surabaya = KotaKabupaten::where('kode', '3578')->value('id');
        $makassar = KotaKabupaten::where('kode', '7371')->value('id');
        $manado   = KotaKabupaten::where('kode', '7171')->value('id');

        $sekolahData = [
            [
                'npsn'                 => '40312156',
                'nama'                 => 'SD Hang Tuah 6 Makassar',
                'jenjang'              => 'SD',
                'alamat'               => 'Jl. Tentara Pelajar No. 6, Makassar',
                'kota_id'              => $makassar,
                'provinsi_id'          => $sulsel,
                'telepon'              => null,
                'email'                => 'sdhangtuah6mks@gmail.com',
                'akreditasi_nilai'     => 88,
                'akreditasi_predikat'  => 'UNGGUL',
                'akreditasi_tahun'     => 2023,
                'kepala_sekolah_nama'  => 'Sri Wahyuni, S.Pd',
                'kepala_sekolah_nip'   => null,
                'tahun_berdiri'        => 1975,
                'status_operasional'   => 'aktif',
                'rapor_literasi'       => null,
                'rapor_numerasi'       => null,
                'rapor_karakter'       => null,
            ],
            [
                'npsn'                 => '20106955',
                'nama'                 => 'SMP Hang Tuah 2 Jakarta',
                'jenjang'              => 'SMP',
                'alamat'               => 'Jl. Ampera Raya No. 5, Jakarta Selatan',
                'kota_id'              => $jktSel,
                'provinsi_id'          => $jakarta,
                'telepon'              => '(021) 7265040',
                'email'                => 'smphangtuah2jkt@gmail.com',
                'akreditasi_nilai'     => 95,
                'akreditasi_predikat'  => 'UNGGUL',
                'akreditasi_tahun'     => 2023,
                'kepala_sekolah_nama'  => 'Drs. Aris Supriyanto',
                'kepala_sekolah_nip'   => null,
                'tahun_berdiri'        => 1968,
                'status_operasional'   => 'aktif',
                'rapor_literasi'       => 97.78,
                'rapor_numerasi'       => 91.11,
                'rapor_karakter'       => 66.70,
            ],
            [
                'npsn'                 => '20532142',
                'nama'                 => 'SMA Hang Tuah 1 Surabaya',
                'jenjang'              => 'SMA',
                'alamat'               => 'Jl. Biliton No. 25, Surabaya',
                'kota_id'              => $surabaya,
                'provinsi_id'          => $jawatim,
                'telepon'              => null,
                'email'                => 'smahangtuah1sby@gmail.com',
                'akreditasi_nilai'     => 92,
                'akreditasi_predikat'  => 'UNGGUL',
                'akreditasi_tahun'     => 2022,
                'kepala_sekolah_nama'  => 'Drs. Bambang Suryadi, M.Pd',
                'kepala_sekolah_nip'   => null,
                'tahun_berdiri'        => 1962,
                'status_operasional'   => 'aktif',
                'rapor_literasi'       => 88.50,
                'rapor_numerasi'       => 85.20,
                'rapor_karakter'       => 72.30,
            ],
            [
                'npsn'                 => '60214578',
                'nama'                 => 'TK Hang Tuah 3 Manado',
                'jenjang'              => 'TK',
                'alamat'               => 'Jl. Diponegoro No. 3, Manado',
                'kota_id'              => $manado,
                'provinsi_id'          => $sulut,
                'telepon'              => null,
                'email'                => null,
                'akreditasi_nilai'     => null,
                'akreditasi_predikat'  => null,
                'akreditasi_tahun'     => null,
                'kepala_sekolah_nama'  => 'Ibu Maria Tangkudung, S.Pd',
                'kepala_sekolah_nip'   => null,
                'tahun_berdiri'        => 1990,
                'status_operasional'   => 'aktif',
                'rapor_literasi'       => null,
                'rapor_numerasi'       => null,
                'rapor_karakter'       => null,
            ],
            [
                'npsn'                 => '20106890',
                'nama'                 => 'SMK Hang Tuah 1 Jakarta',
                'jenjang'              => 'SMK',
                'alamat'               => 'Jl. Enggano No. 10, Jakarta Utara',
                'kota_id'              => $jktUt,
                'provinsi_id'          => $jakarta,
                'telepon'              => null,
                'email'                => 'smkhangtuah1jkt@gmail.com',
                'akreditasi_nilai'     => 94,
                'akreditasi_predikat'  => 'UNGGUL',
                'akreditasi_tahun'     => 2023,
                'kepala_sekolah_nama'  => 'Capt. Hendro Wibowo, M.M',
                'kepala_sekolah_nip'   => null,
                'tahun_berdiri'        => 1960,
                'status_operasional'   => 'aktif',
                'rapor_literasi'       => 82.50,
                'rapor_numerasi'       => 79.30,
                'rapor_karakter'       => null,
            ],
        ];

        foreach ($sekolahData as $item) {
            Sekolah::create($item);
        }
    }
}
