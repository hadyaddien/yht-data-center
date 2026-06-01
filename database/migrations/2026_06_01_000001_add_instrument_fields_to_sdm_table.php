<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sdm', function (Blueprint $table) {
            $table->unsignedSmallInteger('jumlah_guru')->default(0)->after('tahun_ajaran');
            $table->unsignedSmallInteger('guru_tetap_yayasan')->default(0)->after('jumlah_guru');
            $table->unsignedSmallInteger('guru_tidak_tetap')->default(0)->after('guru_tetap_yayasan');
            $table->unsignedSmallInteger('guru_s1_pendidikan')->default(0)->after('guru_tidak_tetap');
            $table->unsignedSmallInteger('guru_s1_non_pendidikan')->default(0)->after('guru_s1_pendidikan');
            $table->unsignedSmallInteger('guru_s2')->default(0)->after('guru_s1_non_pendidikan');
            $table->unsignedSmallInteger('guru_s3')->default(0)->after('guru_s2');
            $table->unsignedSmallInteger('guru_sertifikasi')->default(0)->after('guru_s3');

            $table->unsignedSmallInteger('jumlah_karyawan')->default(0)->after('guru_sertifikasi');
            $table->unsignedSmallInteger('karyawan_tetap')->default(0)->after('jumlah_karyawan');
            $table->unsignedSmallInteger('karyawan_tidak_tetap')->default(0)->after('karyawan_tetap');

            $table->unsignedInteger('rata_gaji_guru')->nullable()->after('murid_ortu_lainnya_jumlah');
            $table->unsignedInteger('rata_gaji_karyawan')->nullable()->after('rata_gaji_guru');
            $table->unsignedSmallInteger('masa_jabatan_kepsek')->nullable()->after('rata_gaji_karyawan');
            $table->text('hambatan_tantangan')->nullable()->after('masa_jabatan_kepsek');
        });
    }

    public function down(): void
    {
        Schema::table('sdm', function (Blueprint $table) {
            $table->dropColumn([
                'jumlah_guru',
                'guru_tetap_yayasan',
                'guru_tidak_tetap',
                'guru_s1_pendidikan',
                'guru_s1_non_pendidikan',
                'guru_s2',
                'guru_s3',
                'guru_sertifikasi',
                'jumlah_karyawan',
                'karyawan_tetap',
                'karyawan_tidak_tetap',
                'rata_gaji_guru',
                'rata_gaji_karyawan',
                'masa_jabatan_kepsek',
                'hambatan_tantangan',
            ]);
        });
    }
};
