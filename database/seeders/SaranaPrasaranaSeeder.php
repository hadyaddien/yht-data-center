<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use App\Models\SaranaPrasarana;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SaranaPrasaranaSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        SaranaPrasarana::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $tahun = '2024/2025';

        $sekolahData = [
            'SD Hang Tuah 6 Makassar' => [
                'jumlah_ruang_kelas'    => 12,
                'kondisi_ruang_kelas'   => 'baik',
                'memiliki_perpustakaan' => true,
                'kondisi_perpustakaan'  => 'baik',
                'memiliki_laboratorium' => false,
                'jenis_laboratorium'    => null,
                'memiliki_uks'          => true,
                'kondisi_uks'           => 'rusak_ringan',
                'memiliki_lapangan'     => true,
                'kondisi_lapangan'      => 'baik',
                'luas_bangunan_m2'      => 1200.00,
                'status_kepemilikan'    => 'milik_sendiri',
                'skor_rata_rata'        => 72.50,
                'catatan'               => 'Gedung dalam kondisi baik, perlu renovasi UKS.',
            ],
            'SMP Hang Tuah 2 Jakarta' => [
                'jumlah_ruang_kelas'    => 17,
                'kondisi_ruang_kelas'   => 'baik',
                'memiliki_perpustakaan' => true,
                'kondisi_perpustakaan'  => 'baik',
                'memiliki_laboratorium' => true,
                'jenis_laboratorium'    => 'Laboratorium IPA, Laboratorium Komputer',
                'memiliki_uks'          => true,
                'kondisi_uks'           => 'baik',
                'memiliki_lapangan'     => true,
                'kondisi_lapangan'      => 'baik',
                'luas_bangunan_m2'      => 2400.00,
                'status_kepemilikan'    => 'milik_sendiri',
                'skor_rata_rata'        => 85.00,
                'catatan'               => null,
            ],
            'SMA Hang Tuah 1 Surabaya' => [
                'jumlah_ruang_kelas'    => 24,
                'kondisi_ruang_kelas'   => 'baik',
                'memiliki_perpustakaan' => true,
                'kondisi_perpustakaan'  => 'baik',
                'memiliki_laboratorium' => true,
                'jenis_laboratorium'    => 'Laboratorium Fisika, Laboratorium Kimia, Laboratorium Biologi, Laboratorium Komputer',
                'memiliki_uks'          => true,
                'kondisi_uks'           => 'baik',
                'memiliki_lapangan'     => true,
                'kondisi_lapangan'      => 'baik',
                'luas_bangunan_m2'      => 3800.00,
                'status_kepemilikan'    => 'milik_sendiri',
                'skor_rata_rata'        => 90.00,
                'catatan'               => null,
            ],
            'TK Hang Tuah 3 Manado' => [
                'jumlah_ruang_kelas'    => 4,
                'kondisi_ruang_kelas'   => 'rusak_ringan',
                'memiliki_perpustakaan' => false,
                'kondisi_perpustakaan'  => null,
                'memiliki_laboratorium' => false,
                'jenis_laboratorium'    => null,
                'memiliki_uks'          => false,
                'kondisi_uks'           => null,
                'memiliki_lapangan'     => true,
                'kondisi_lapangan'      => 'rusak_ringan',
                'luas_bangunan_m2'      => 450.00,
                'status_kepemilikan'    => 'sewa',
                'skor_rata_rata'        => 48.00,
                'catatan'               => 'Gedung sewa, beberapa ruangan perlu perbaikan.',
            ],
            'SMK Hang Tuah 1 Jakarta' => [
                'jumlah_ruang_kelas'    => 30,
                'kondisi_ruang_kelas'   => 'baik',
                'memiliki_perpustakaan' => true,
                'kondisi_perpustakaan'  => 'baik',
                'memiliki_laboratorium' => true,
                'jenis_laboratorium'    => 'Laboratorium Komputer (3 ruang), Bengkel Otomotif, Bengkel Listrik, Laboratorium Nautika',
                'memiliki_uks'          => true,
                'kondisi_uks'           => 'baik',
                'memiliki_lapangan'     => true,
                'kondisi_lapangan'      => 'baik',
                'luas_bangunan_m2'      => 5200.00,
                'status_kepemilikan'    => 'milik_sendiri',
                'skor_rata_rata'        => 88.00,
                'catatan'               => null,
            ],
        ];

        foreach ($sekolahData as $namaSekolah => $data) {
            $sekolah = Sekolah::where('nama', $namaSekolah)->first();
            if ($sekolah) {
                SaranaPrasarana::create(array_merge(['sekolah_id' => $sekolah->id, 'tahun_ajaran' => $tahun], $data));
            }
        }
    }
}

