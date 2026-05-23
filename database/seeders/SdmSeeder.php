<?php

namespace Database\Seeders;

use App\Models\Sdm;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;

class SdmSeeder extends Seeder
{
    public function run(): void
    {
        Sdm::truncate();

        $tahun = '2024/2025';

        $sekolahData = [
            'SD Hang Tuah 6 Makassar' => [
                'guru_pns'            => 8,
                'guru_honorer'        => 8,
                'guru_p3k'            => 2,
                'karyawan_pns'        => 2,
                'karyawan_honorer'    => 5,
                'karyawan_p3k'        => 1,
                'jumlah_rombel'       => 12,
                'guru_bersertifikasi' => 10,
                'guru_s1_keatas'      => 16,
                'catatan_hambatan'    => null,
            ],
            'SMP Hang Tuah 2 Jakarta' => [
                'guru_pns'            => 12,
                'guru_honorer'        => 7,
                'guru_p3k'            => 3,
                'karyawan_pns'        => 5,
                'karyawan_honorer'    => 6,
                'karyawan_p3k'        => 1,
                'jumlah_rombel'       => 17,
                'guru_bersertifikasi' => 15,
                'guru_s1_keatas'      => 21,
                'catatan_hambatan'    => null,
            ],
            'SMA Hang Tuah 1 Surabaya' => [
                'guru_pns'            => 20,
                'guru_honorer'        => 10,
                'guru_p3k'            => 5,
                'karyawan_pns'        => 8,
                'karyawan_honorer'    => 5,
                'karyawan_p3k'        => 2,
                'jumlah_rombel'       => 24,
                'guru_bersertifikasi' => 28,
                'guru_s1_keatas'      => 34,
                'catatan_hambatan'    => null,
            ],
            'TK Hang Tuah 3 Manado' => [
                'guru_pns'            => 2,
                'guru_honorer'        => 5,
                'guru_p3k'            => 1,
                'karyawan_pns'        => 1,
                'karyawan_honorer'    => 3,
                'karyawan_p3k'        => 0,
                'jumlah_rombel'       => 6,
                'guru_bersertifikasi' => 3,
                'guru_s1_keatas'      => 5,
                'catatan_hambatan'    => 'Kekurangan tenaga pendidik bersertifikasi PAUD.',
            ],
            'SMK Hang Tuah 1 Jakarta' => [
                'guru_pns'            => 25,
                'guru_honorer'        => 15,
                'guru_p3k'            => 5,
                'karyawan_pns'        => 10,
                'karyawan_honorer'    => 8,
                'karyawan_p3k'        => 2,
                'jumlah_rombel'       => 30,
                'guru_bersertifikasi' => 30,
                'guru_s1_keatas'      => 44,
                'catatan_hambatan'    => null,
            ],
        ];

        foreach ($sekolahData as $namaSekolah => $data) {
            $sekolah = Sekolah::where('nama', $namaSekolah)->first();
            if ($sekolah) {
                Sdm::create(array_merge(['sekolah_id' => $sekolah->id, 'tahun_ajaran' => $tahun], $data));
            }
        }
    }
}
