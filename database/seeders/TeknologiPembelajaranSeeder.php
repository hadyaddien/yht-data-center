<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use App\Models\TeknologiPembelajaran;
use Illuminate\Database\Seeder;

class TeknologiPembelajaranSeeder extends Seeder
{
    public function run(): void
    {
        TeknologiPembelajaran::truncate();

        $tahun = '2024/2025';

        $sekolahData = [
            'SD Hang Tuah 6 Makassar' => [
                'memiliki_lab_komputer'    => true,
                'jumlah_komputer_lab'      => 20,
                'jumlah_komputer_admin'    => 5,
                'jumlah_laptop_guru'       => 10,
                'memiliki_proyektor'       => true,
                'jumlah_proyektor'         => 6,
                'memiliki_internet'        => true,
                'jenis_internet'           => 'WiFi',
                'bandwidth_mbps'           => 20,
                'memiliki_lms'             => true,
                'nama_lms'                 => 'LMS Kemendikdasmen',
                'memiliki_e_perpustakaan'  => false,
                'memiliki_smart_classroom' => false,
                'memiliki_tenaga_it'       => false,
                'aplikasi_pembelajaran'    => 'Google Classroom, Canva for Education',
                'catatan'                  => null,
            ],
            'SMP Hang Tuah 2 Jakarta' => [
                'memiliki_lab_komputer'    => true,
                'jumlah_komputer_lab'      => 30,
                'jumlah_komputer_admin'    => 8,
                'jumlah_laptop_guru'       => 15,
                'memiliki_proyektor'       => true,
                'jumlah_proyektor'         => 17,
                'memiliki_internet'        => true,
                'jenis_internet'           => 'WiFi & LAN',
                'bandwidth_mbps'           => 50,
                'memiliki_lms'             => true,
                'nama_lms'                 => 'LMS Kemendikdasmen',
                'memiliki_e_perpustakaan'  => false,
                'memiliki_smart_classroom' => false,
                'memiliki_tenaga_it'       => true,
                'aplikasi_pembelajaran'    => 'Google Classroom, Microsoft 365, Canva, Quizizz',
                'catatan'                  => null,
            ],
            'SMA Hang Tuah 1 Surabaya' => [
                'memiliki_lab_komputer'    => true,
                'jumlah_komputer_lab'      => 40,
                'jumlah_komputer_admin'    => 12,
                'jumlah_laptop_guru'       => 20,
                'memiliki_proyektor'       => true,
                'jumlah_proyektor'         => 24,
                'memiliki_internet'        => true,
                'jenis_internet'           => 'Fiber Optik',
                'bandwidth_mbps'           => 100,
                'memiliki_lms'             => true,
                'nama_lms'                 => 'LMS Kemendikdasmen, Moodle',
                'memiliki_e_perpustakaan'  => true,
                'memiliki_smart_classroom' => true,
                'memiliki_tenaga_it'       => true,
                'aplikasi_pembelajaran'    => 'Google Workspace, Edmodo, Canva, Quizizz, Kahoot',
                'catatan'                  => null,
            ],
            'TK Hang Tuah 3 Manado' => [
                'memiliki_lab_komputer'    => false,
                'jumlah_komputer_lab'      => 0,
                'jumlah_komputer_admin'    => 2,
                'jumlah_laptop_guru'       => 2,
                'memiliki_proyektor'       => true,
                'jumlah_proyektor'         => 2,
                'memiliki_internet'        => true,
                'jenis_internet'           => 'WiFi',
                'bandwidth_mbps'           => 10,
                'memiliki_lms'             => false,
                'nama_lms'                 => null,
                'memiliki_e_perpustakaan'  => false,
                'memiliki_smart_classroom' => false,
                'memiliki_tenaga_it'       => false,
                'aplikasi_pembelajaran'    => null,
                'catatan'                  => 'Keterbatasan perangkat teknologi pembelajaran.',
            ],
            'SMK Hang Tuah 1 Jakarta' => [
                'memiliki_lab_komputer'    => true,
                'jumlah_komputer_lab'      => 60,
                'jumlah_komputer_admin'    => 15,
                'jumlah_laptop_guru'       => 25,
                'memiliki_proyektor'       => true,
                'jumlah_proyektor'         => 30,
                'memiliki_internet'        => true,
                'jenis_internet'           => 'Fiber Optik',
                'bandwidth_mbps'           => 100,
                'memiliki_lms'             => true,
                'nama_lms'                 => 'LMS Kemendikdasmen',
                'memiliki_e_perpustakaan'  => false,
                'memiliki_smart_classroom' => true,
                'memiliki_tenaga_it'       => true,
                'aplikasi_pembelajaran'    => 'Google Classroom, Microsoft 365, Cisco Packet Tracer, AutoCAD',
                'catatan'                  => null,
            ],
        ];

        foreach ($sekolahData as $namaSekolah => $data) {
            $sekolah = Sekolah::where('nama', $namaSekolah)->first();
            if ($sekolah) {
                TeknologiPembelajaran::create(array_merge(['sekolah_id' => $sekolah->id, 'tahun_ajaran' => $tahun], $data));
            }
        }
    }
}
