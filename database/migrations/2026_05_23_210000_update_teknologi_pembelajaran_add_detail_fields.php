<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('teknologi_pembelajaran')) {
            return;
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'software_aplikasi_pembelajaran_status')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->string('software_aplikasi_pembelajaran_status', 40)->nullable()->after('tahun_ajaran');
            });
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'lms_kemendikdasmen_status')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->string('lms_kemendikdasmen_status', 40)->nullable()->after('software_aplikasi_pembelajaran_status');
            });
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'aplikasi_smart_classroom_status')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->string('aplikasi_smart_classroom_status', 50)->nullable()->after('lms_kemendikdasmen_status');
            });
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'koleksi_ebook_status')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->string('koleksi_ebook_status', 40)->nullable()->after('aplikasi_smart_classroom_status');
            });
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'website_sekolah_status')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->string('website_sekolah_status', 30)->nullable()->after('koleksi_ebook_status');
            });
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'server_pembelajaran_status')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->string('server_pembelajaran_status', 40)->nullable()->after('website_sekolah_status');
            });
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'tenaga_khusus_it_status')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->string('tenaga_khusus_it_status', 50)->nullable()->after('server_pembelajaran_status');
            });
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'media_sosial')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->json('media_sosial')->nullable()->after('catatan');
            });
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'platform_lms')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->json('platform_lms')->nullable()->after('media_sosial');
            });
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'platform_pendidikan')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->json('platform_pendidikan')->nullable()->after('platform_lms');
            });
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'alat_interaktif')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->json('alat_interaktif')->nullable()->after('platform_pendidikan');
            });
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'platform_komunikasi')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->json('platform_komunikasi')->nullable()->after('alat_interaktif');
            });
        }

        if (!Schema::hasColumn('teknologi_pembelajaran', 'aplikasi_manajemen')) {
            Schema::table('teknologi_pembelajaran', function (Blueprint $table) {
                $table->json('aplikasi_manajemen')->nullable()->after('platform_komunikasi');
            });
        }
    }

    public function down(): void
    {
        if (!Schema::hasTable('teknologi_pembelajaran')) {
            return;
        }

        $columns = [
            'software_aplikasi_pembelajaran_status',
            'lms_kemendikdasmen_status',
            'aplikasi_smart_classroom_status',
            'koleksi_ebook_status',
            'website_sekolah_status',
            'server_pembelajaran_status',
            'tenaga_khusus_it_status',
            'media_sosial',
            'platform_lms',
            'platform_pendidikan',
            'alat_interaktif',
            'platform_komunikasi',
            'aplikasi_manajemen',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('teknologi_pembelajaran', $column)) {
                Schema::table('teknologi_pembelajaran', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};
