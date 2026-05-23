<?php

namespace Database\Seeders;

use App\Models\ProgramPendidikan;
use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramPendidikanSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        ProgramPendidikan::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $tahun = '2024/2025';

        $sekolahData = [
            'SD Hang Tuah 6 Makassar' => [
                'kurikulum'          => 'Kurikulum Merdeka',
                'program_unggulan'   => 'Program Literasi Bahari, Pendidikan Karakter Berbasis Kemaritiman, Kelas Bilingual',
                'ekstrakurikuler'    => 'Pramuka, Seni Tari, Paduan Suara, Olahraga (Renang, Badminton), Robotika',
                'prestasi_siswa'     => 'Juara 1 Olimpiade Matematika Tingkat Kota Makassar 2024, Juara 2 Lomba Sains Nasional Yayasan Hang Tuah 2024',
                'visi'               => 'Mewujudkan peserta didik yang beriman, berprestasi, berkarakter bahari, dan berwawasan global',
                'misi'               => 'Menyelenggarakan pembelajaran bermutu berbasis nilai-nilai kemaritiman; Membina karakter disiplin dan cinta tanah air; Mengembangkan potensi akademik dan non-akademik siswa',
                'catatan'            => null,
            ],
            'SMP Hang Tuah 2 Jakarta' => [
                'kurikulum'          => 'Kurikulum Merdeka',
                'program_unggulan'   => 'Program STEM, Kelas Unggulan, Pendidikan Kemaritiman Terpadu',
                'ekstrakurikuler'    => 'Pramuka, PMR, KIR, Seni Musik, Basket, Sepak Bola, Renang, English Club',
                'prestasi_siswa'     => 'Juara 1 Olimpiade Sains Nasional Bidang IPA 2024, Juara 2 Futsal Antar SMP se-DKI Jakarta 2024, Nilai Rapor Literasi 97.78 (Peringkat 1 Yayasan)',
                'visi'               => 'Menjadi sekolah unggulan yang menghasilkan generasi berkarakter, berprestasi, dan peduli lingkungan maritim',
                'misi'               => 'Melaksanakan pembelajaran inovatif berbasis STEM; Menumbuhkan semangat kemaritiman dan cinta bahari; Memfasilitasi pengembangan minat dan bakat siswa',
                'catatan'            => null,
            ],
            'SMA Hang Tuah 1 Surabaya' => [
                'kurikulum'          => 'Kurikulum Merdeka (Mandiri Berubah)',
                'program_unggulan'   => 'Program Kelas Akselerasi, IPA Plus, Pendidikan Kemaritiman, Program Beasiswa Perguruan Tinggi Kelautan',
                'ekstrakurikuler'    => 'Pramuka, OSIS, KIR, Robotika, Seni Tari, Band, Basket, Voli, Renang, English Debate Club, Jurnalistik',
                'prestasi_siswa'     => 'Juara 1 OSN Bidang Kimia Provinsi Jawa Timur 2024, 3 siswa lolos SNBP PTN Favorit 2024, Juara 2 Lomba Esai Maritim Nasional 2024',
                'visi'               => 'Unggul dalam prestasi, berkarakter bangsa, berwawasan bahari, dan siap bersaing di era global',
                'misi'               => 'Menyelenggarakan pendidikan berkualitas tinggi berstandar nasional; Membangun karakter siswa yang berintegritas dan berjiwa maritim; Mendorong inovasi melalui penelitian dan pengembangan ilmu pengetahuan',
                'catatan'            => null,
            ],
            'TK Hang Tuah 3 Manado' => [
                'kurikulum'          => 'Kurikulum Merdeka (PAUD)',
                'program_unggulan'   => 'Stimulasi Tumbuh Kembang Anak, Pengenalan Budaya Bahari Sejak Dini',
                'ekstrakurikuler'    => 'Seni Tari Tradisional, Menggambar & Mewarnai, Senam Ceria, Bernyanyi',
                'prestasi_siswa'     => 'Juara Harapan 1 Lomba Mewarnai Tingkat Kota Manado 2024',
                'visi'               => 'Mewujudkan anak usia dini yang ceria, cerdas, berkarakter, dan mencintai lingkungan bahari',
                'misi'               => 'Memberikan stimulasi tumbuh kembang yang optimal; Menanamkan nilai-nilai karakter sejak dini; Mengenalkan budaya dan lingkungan bahari kepada anak',
                'catatan'            => 'Keterbatasan ruang bermain outdoor.',
            ],
            'SMK Hang Tuah 1 Jakarta' => [
                'kurikulum'          => 'Kurikulum Merdeka (SMK Pusat Keunggulan)',
                'program_unggulan'   => 'Program Keahlian Teknik Kapal, Nautika, Pelayaran, Teknik Komputer & Jaringan, Link and Match dengan BUMN Maritim',
                'ekstrakurikuler'    => 'Pramuka, PMR, Basket, Voli, Drum Band, Robotika, English Club, Seni Bela Diri',
                'prestasi_siswa'     => 'Juara 1 LKS (Lomba Kompetensi Siswa) Bidang Nautika Tingkat Nasional 2024, 85% lulusan terserap industri maritim, Kerjasama magang dengan PT Pelindo dan TNI AL',
                'visi'               => 'Menjadi SMK Maritim terkemuka yang menghasilkan tenaga ahli profesional, berkarakter, dan siap bersaing di dunia kerja nasional dan internasional',
                'misi'               => 'Menyelenggarakan pendidikan vokasi berkualitas di bidang kemaritiman; Membangun kemitraan strategis dengan industri dan institusi maritim; Mengembangkan kompetensi siswa sesuai standar nasional dan internasional',
                'catatan'            => null,
            ],
        ];

        foreach ($sekolahData as $namaSekolah => $data) {
            $sekolah = Sekolah::where('nama', $namaSekolah)->first();
            if ($sekolah) {
                ProgramPendidikan::create(array_merge(['sekolah_id' => $sekolah->id, 'tahun_ajaran' => $tahun], $data));
            }
        }
    }
}

