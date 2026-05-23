<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Langkah 1: Tambah unique index baru pada sekolah_id saja dulu
        // (agar FK tetap valid saat unique lama di-drop)
        Schema::table('program_pendidikan', function (Blueprint $table) {
            $table->unique('sekolah_id');
        });

        // Langkah 2: Drop unique lama (sekolah_id, tahun_ajaran)
        Schema::table('program_pendidikan', function (Blueprint $table) {
            $table->dropUnique(['sekolah_id', 'tahun_ajaran']);
        });

        // Langkah 3: Tambah kolom-kolom baru
        Schema::table('program_pendidikan', function (Blueprint $table) {

            // Nilai Ujian Sekolah
            $table->decimal('nilai_ujian_ta1', 5, 2)->nullable()->after('catatan');
            $table->decimal('nilai_ujian_ta2', 5, 2)->nullable()->after('nilai_ujian_ta1');

            // Rapor Pendidikan (Skor PBD)
            $table->decimal('pbd_literasi',              5, 2)->nullable()->after('nilai_ujian_ta2');
            $table->decimal('pbd_numerasi',              5, 2)->nullable()->after('pbd_literasi');
            $table->decimal('pbd_karakter',              5, 2)->nullable()->after('pbd_numerasi');
            $table->decimal('pbd_kualitas_pembelajaran', 5, 2)->nullable()->after('pbd_karakter');
            $table->decimal('pbd_iklim_keamanan',        5, 2)->nullable()->after('pbd_kualitas_pembelajaran');
            $table->decimal('pbd_iklim_kebhinekaan',     5, 2)->nullable()->after('pbd_iklim_keamanan');

            // Prestasi Akademik
            $table->unsignedSmallInteger('prestasi_akad_2025_kota')->nullable()->after('pbd_iklim_kebhinekaan');
            $table->unsignedSmallInteger('prestasi_akad_2025_provinsi')->nullable();
            $table->unsignedSmallInteger('prestasi_akad_2025_nasional')->nullable();
            $table->unsignedSmallInteger('prestasi_akad_2025_internasional')->nullable();
            $table->unsignedSmallInteger('prestasi_akad_2026_kota')->nullable();
            $table->unsignedSmallInteger('prestasi_akad_2026_provinsi')->nullable();
            $table->unsignedSmallInteger('prestasi_akad_2026_nasional')->nullable();
            $table->unsignedSmallInteger('prestasi_akad_2026_internasional')->nullable();

            // Prestasi Non Akademik
            $table->unsignedSmallInteger('prestasi_non_2025_kota')->nullable();
            $table->unsignedSmallInteger('prestasi_non_2025_provinsi')->nullable();
            $table->unsignedSmallInteger('prestasi_non_2025_nasional')->nullable();
            $table->unsignedSmallInteger('prestasi_non_2025_internasional')->nullable();
            $table->unsignedSmallInteger('prestasi_non_2026_kota')->nullable();
            $table->unsignedSmallInteger('prestasi_non_2026_provinsi')->nullable();
            $table->unsignedSmallInteger('prestasi_non_2026_nasional')->nullable();
            $table->unsignedSmallInteger('prestasi_non_2026_internasional')->nullable();

            // Kurikulum Kebaharian
            $table->string('kurikulum_kebaharian', 50)->nullable()->after('kurikulum');
            $table->unsignedSmallInteger('jumlah_guru_kebaharian')->nullable();

            // Sumber Dana
            $table->string('penerimaan_bos', 50)->nullable();
            $table->string('penerimaan_bop', 50)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('program_pendidikan', function (Blueprint $table) {
            $table->dropUnique(['sekolah_id']);
            $table->unique(['sekolah_id', 'tahun_ajaran']);
            $table->dropColumn([
                'nilai_ujian_ta1',
                'nilai_ujian_ta2',
                'pbd_literasi',
                'pbd_numerasi',
                'pbd_karakter',
                'pbd_kualitas_pembelajaran',
                'pbd_iklim_keamanan',
                'pbd_iklim_kebhinekaan',
                'prestasi_akad_2025_kota',
                'prestasi_akad_2025_provinsi',
                'prestasi_akad_2025_nasional',
                'prestasi_akad_2025_internasional',
                'prestasi_akad_2026_kota',
                'prestasi_akad_2026_provinsi',
                'prestasi_akad_2026_nasional',
                'prestasi_akad_2026_internasional',
                'prestasi_non_2025_kota',
                'prestasi_non_2025_provinsi',
                'prestasi_non_2025_nasional',
                'prestasi_non_2025_internasional',
                'prestasi_non_2026_kota',
                'prestasi_non_2026_provinsi',
                'prestasi_non_2026_nasional',
                'prestasi_non_2026_internasional',
                'kurikulum_kebaharian',
                'jumlah_guru_kebaharian',
                'penerimaan_bos',
                'penerimaan_bop',
            ]);
        });
    }
};
