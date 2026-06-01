<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
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

        Schema::table('sarana_prasarana', function (Blueprint $table) use ($items) {
            foreach ($items as $item) {
                $table->boolean("{$item}_ada")->default(false);
                $table->unsignedTinyInteger("{$item}_kondisi")->nullable();
            }

            $table->decimal('luas_tanah', 10, 2)->nullable();
            $table->decimal('luas_bangunan', 10, 2)->nullable();
            $table->unsignedBigInteger('biaya_sewa_lahan')->nullable();
        });
    }

    public function down(): void
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

        $columns = [];
        foreach ($items as $item) {
            $columns[] = "{$item}_ada";
            $columns[] = "{$item}_kondisi";
        }

        $columns[] = 'luas_tanah';
        $columns[] = 'luas_bangunan';
        $columns[] = 'biaya_sewa_lahan';

        Schema::table('sarana_prasarana', function (Blueprint $table) use ($columns) {
            $table->dropColumn($columns);
        });
    }
};
