<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->migrateLegacyRegionReferences();

        $this->dropForeignIfExists('sekolah', 'sekolah_provinsi_id_foreign');
        $this->dropForeignIfExists('sekolah', 'sekolah_kota_id_foreign');
        $this->dropForeignIfExists('users', 'users_provinsi_id_foreign');

        Schema::table('sekolah', function (Blueprint $table) {
            $table->foreign('provinsi_id')->references('id')->on('indonesia_provinces')->cascadeOnDelete();
            $table->foreign('kota_id')->references('id')->on('indonesia_cities')->nullOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('provinsi_id')->references('id')->on('indonesia_provinces')->nullOnDelete();
        });
    }

    public function down(): void
    {
        $this->dropForeignIfExists('sekolah', 'sekolah_provinsi_id_foreign');
        $this->dropForeignIfExists('sekolah', 'sekolah_kota_id_foreign');
        $this->dropForeignIfExists('users', 'users_provinsi_id_foreign');

        Schema::table('sekolah', function (Blueprint $table) {
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->cascadeOnDelete();
            $table->foreign('kota_id')->references('id')->on('kota_kabupaten')->nullOnDelete();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('provinsi_id')->references('id')->on('provinsi')->nullOnDelete();
        });
    }

    private function dropForeignIfExists(string $tableName, string $foreignKey): void
    {
        try {
            Schema::table($tableName, function (Blueprint $table) use ($foreignKey) {
                $table->dropForeign($foreignKey);
            });
        } catch (Throwable) {
            // Abaikan jika constraint tidak ada agar migrasi tetap aman dijalankan.
        }
    }

    private function migrateLegacyRegionReferences(): void
    {
        if (! Schema::hasTable('sekolah') || ! Schema::hasTable('users')) {
            return;
        }

        if (! Schema::hasTable('indonesia_provinces') || ! Schema::hasTable('indonesia_cities')) {
            return;
        }

        if (! Schema::hasTable('provinsi') || ! Schema::hasTable('kota_kabupaten')) {
            return;
        }

        // Pastikan data referensi minimal tersedia di tabel Laravolt untuk mapping existing records.
        $legacyProvinces = DB::table('provinsi')->select(['kode', 'nama'])->get();
        foreach ($legacyProvinces as $province) {
            DB::table('indonesia_provinces')->updateOrInsert(
                ['code' => (string) $province->kode],
                ['name' => (string) $province->nama, 'meta' => null, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        $legacyCities = DB::table('kota_kabupaten as k')
            ->join('provinsi as p', 'p.id', '=', 'k.provinsi_id')
            ->select(['k.kode as code', 'p.kode as province_code', 'k.nama as name'])
            ->get();

        foreach ($legacyCities as $city) {
            DB::table('indonesia_cities')->updateOrInsert(
                ['code' => (string) $city->code],
                [
                    'province_code' => (string) $city->province_code,
                    'name' => (string) $city->name,
                    'meta' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Remap FK sekolah/user lama (id lokal) -> id tabel Laravolt berdasarkan kode wilayah.
        DB::statement(
            'UPDATE sekolah s
             JOIN provinsi p ON p.id = s.provinsi_id
             JOIN indonesia_provinces ip ON ip.code = p.kode
             SET s.provinsi_id = ip.id'
        );

        DB::statement(
            'UPDATE sekolah s
             JOIN kota_kabupaten k ON k.id = s.kota_id
             JOIN indonesia_cities ic ON ic.code = k.kode
             SET s.kota_id = ic.id'
        );

        DB::statement(
            'UPDATE users u
             JOIN provinsi p ON p.id = u.provinsi_id
             JOIN indonesia_provinces ip ON ip.code = p.kode
             SET u.provinsi_id = ip.id'
        );

        // Kolom nullable dibersihkan agar tidak menabrak FK baru.
        DB::statement(
            'UPDATE sekolah s
             LEFT JOIN indonesia_cities ic ON ic.id = s.kota_id
             SET s.kota_id = NULL
             WHERE s.kota_id IS NOT NULL AND ic.id IS NULL'
        );

        DB::statement(
            'UPDATE users u
             LEFT JOIN indonesia_provinces ip ON ip.id = u.provinsi_id
             SET u.provinsi_id = NULL
             WHERE u.provinsi_id IS NOT NULL AND ip.id IS NULL'
        );

        $orphanProvinsiInSekolah = DB::table('sekolah as s')
            ->leftJoin('indonesia_provinces as ip', 'ip.id', '=', 's.provinsi_id')
            ->whereNull('ip.id')
            ->exists();

        if ($orphanProvinsiInSekolah) {
            throw new RuntimeException(
                'Terdapat data sekolah dengan provinsi_id yang tidak dapat dipetakan ke indonesia_provinces. '
                    .'Perbaiki data wilayah sekolah terlebih dahulu sebelum menjalankan migrasi ini.'
            );
        }
    }
};
