<?php

namespace Database\Seeders;

use App\Models\Sdm;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SdmSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Sdm::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

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
                'jumlah_murid_total'  => 420,
                'jumlah_murid_laki'   => 210,
                'jumlah_murid_perempuan' => 210,
                'murid_ortu_tni_al'   => 6,
                'murid_ortu_tni'      => 8,
                'murid_ortu_polisi'   => 4,
                'murid_ortu_pns'      => 30,
                'murid_ortu_pengusaha' => 18,
                'murid_ortu_wiraswasta' => 120,
                'murid_ortu_buruh'    => 150,
                'murid_ortu_guru'     => 10,
                'murid_ortu_lainnya_label' => 'Nelayan',
                'murid_ortu_lainnya_jumlah' => 5,
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
                'jumlah_murid_total'  => 520,
                'jumlah_murid_laki'   => 260,
                'jumlah_murid_perempuan' => 260,
                'murid_ortu_tni_al'   => 5,
                'murid_ortu_tni'      => 7,
                'murid_ortu_polisi'   => 6,
                'murid_ortu_pns'      => 40,
                'murid_ortu_pengusaha' => 30,
                'murid_ortu_wiraswasta' => 140,
                'murid_ortu_buruh'    => 200,
                'murid_ortu_guru'     => 12,
                'murid_ortu_lainnya_label' => 'Pekerja Migran',
                'murid_ortu_lainnya_jumlah' => 8,
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
                'jumlah_murid_total'  => 680,
                'jumlah_murid_laki'   => 330,
                'jumlah_murid_perempuan' => 350,
                'murid_ortu_tni_al'   => 4,
                'murid_ortu_tni'      => 10,
                'murid_ortu_polisi'   => 8,
                'murid_ortu_pns'      => 60,
                'murid_ortu_pengusaha' => 45,
                'murid_ortu_wiraswasta' => 160,
                'murid_ortu_buruh'    => 260,
                'murid_ortu_guru'     => 18,
                'murid_ortu_lainnya_label' => 'Nelayan',
                'murid_ortu_lainnya_jumlah' => 15,
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
                'jumlah_murid_total'  => 90,
                'jumlah_murid_laki'   => 45,
                'jumlah_murid_perempuan' => 45,
                'murid_ortu_tni_al'   => 2,
                'murid_ortu_tni'      => 1,
                'murid_ortu_polisi'   => 1,
                'murid_ortu_pns'      => 8,
                'murid_ortu_pengusaha' => 3,
                'murid_ortu_wiraswasta' => 20,
                'murid_ortu_buruh'    => 40,
                'murid_ortu_guru'     => 2,
                'murid_ortu_lainnya_label' => 'Petani',
                'murid_ortu_lainnya_jumlah' => 5,
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
                'jumlah_murid_total'  => 720,
                'jumlah_murid_laki'   => 420,
                'jumlah_murid_perempuan' => 300,
                'murid_ortu_tni_al'   => 6,
                'murid_ortu_tni'      => 12,
                'murid_ortu_polisi'   => 10,
                'murid_ortu_pns'      => 50,
                'murid_ortu_pengusaha' => 60,
                'murid_ortu_wiraswasta' => 200,
                'murid_ortu_buruh'    => 300,
                'murid_ortu_guru'     => 22,
                'murid_ortu_lainnya_label' => 'Pekerja Migran',
                'murid_ortu_lainnya_jumlah' => 20,
                'guru_bersertifikasi' => 30,
                'guru_s1_keatas'      => 44,
                'catatan_hambatan'    => null,
            ],
        ];

        foreach ($sekolahData as $namaSekolah => $data) {
            $sekolah = Sekolah::where('nama', $namaSekolah)->first();
            if ($sekolah) {
                $numericFields = [
                    'guru_pns',
                    'guru_honorer',
                    'guru_p3k',
                    'karyawan_pns',
                    'karyawan_honorer',
                    'karyawan_p3k',
                    'jumlah_rombel',
                    'jumlah_murid_total',
                    'jumlah_murid_laki',
                    'jumlah_murid_perempuan',
                    'murid_ortu_tni_al',
                    'murid_ortu_tni',
                    'murid_ortu_polisi',
                    'murid_ortu_pns',
                    'murid_ortu_pengusaha',
                    'murid_ortu_wiraswasta',
                    'murid_ortu_buruh',
                    'murid_ortu_guru',
                    'guru_bersertifikasi',
                    'guru_s1_keatas',
                ];

                foreach ($numericFields as $field) {
                    $data[$field] = (int) ($data[$field] ?? 0);
                }

                $knownOrtuTotal =
                    $data['murid_ortu_tni_al'] +
                    $data['murid_ortu_tni'] +
                    $data['murid_ortu_polisi'] +
                    $data['murid_ortu_pns'] +
                    $data['murid_ortu_pengusaha'] +
                    $data['murid_ortu_wiraswasta'] +
                    $data['murid_ortu_buruh'] +
                    $data['murid_ortu_guru'];

                // Selaraskan seeder dengan aturan terbaru: "Lainnya" otomatis dari sisa komposisi.
                $data['murid_ortu_lainnya_label'] = null;
                $data['murid_ortu_lainnya_jumlah'] = max(0, $data['jumlah_murid_total'] - $knownOrtuTotal);

                Sdm::create(array_merge([
                    'sekolah_id' => $sekolah->id,
                    'tahun_ajaran' => $tahun,
                    'updated_by' => 1,
                ], $data));
            }
        }
    }
}
