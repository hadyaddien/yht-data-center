<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom-kolom ini sudah ada di database dari sesi migrasi sebelumnya.
        // Migration ini hanya sebagai catatan skema — tidak mengeksekusi query.
        Schema::table('sekolah', function (Blueprint $table) {
            if (! Schema::hasColumn('sekolah', 'kecamatan')) {
                $table->string('kecamatan')->nullable()->after('alamat');
            }
            if (! Schema::hasColumn('sekolah', 'kelurahan')) {
                $table->string('kelurahan')->nullable()->after('kecamatan');
            }
            if (! Schema::hasColumn('sekolah', 'kode_pos')) {
                $table->string('kode_pos', 10)->nullable()->after('kelurahan');
            }
            if (! Schema::hasColumn('sekolah', 'kepala_sekolah_hp')) {
                $table->string('kepala_sekolah_hp', 30)->nullable()->after('kepala_sekolah_nip');
            }
            if (! Schema::hasColumn('sekolah', 'operator_nama')) {
                $table->string('operator_nama')->nullable()->after('kepala_sekolah_hp');
            }
            if (! Schema::hasColumn('sekolah', 'operator_hp')) {
                $table->string('operator_hp', 30)->nullable()->after('operator_nama');
            }
            if (! Schema::hasColumn('sekolah', 'no_sk_akreditasi')) {
                $table->string('no_sk_akreditasi')->nullable()->after('akreditasi_tahun');
            }
            if (! Schema::hasColumn('sekolah', 'kekuatan')) {
                $table->text('kekuatan')->nullable()->after('luas_tanah');
            }
            if (! Schema::hasColumn('sekolah', 'kelemahan')) {
                $table->text('kelemahan')->nullable()->after('kekuatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            $table->dropColumn([
                'kecamatan', 'kelurahan', 'kode_pos',
                'kepala_sekolah_hp', 'operator_nama', 'operator_hp',
                'no_sk_akreditasi', 'kekuatan', 'kelemahan',
            ]);
        });
    }
};
