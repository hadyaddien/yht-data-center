<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE dokumen MODIFY COLUMN kategori VARCHAR(30) DEFAULT 'lainnya' NOT NULL");

        // Drop FK jika masih ada (abaikan error jika tidak ada)
        try {
            DB::statement('ALTER TABLE dokumen DROP FOREIGN KEY dokumen_uploaded_by_foreign');
        } catch (Exception) {
            // FK sudah di-drop sebelumnya
        }

        try {
            DB::statement('ALTER TABLE dokumen DROP INDEX dokumen_uploaded_by_foreign');
        } catch (Exception) {
            // Index sudah di-drop
        }

        DB::statement('ALTER TABLE dokumen MODIFY COLUMN uploaded_by BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE dokumen ADD CONSTRAINT dokumen_uploaded_by_foreign FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL');
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE dokumen MODIFY COLUMN kategori ENUM('foto','akreditasi','laporan','sarpras','sdm','lainnya') DEFAULT 'lainnya' NOT NULL");

        try {
            DB::statement('ALTER TABLE dokumen DROP FOREIGN KEY dokumen_uploaded_by_foreign');
        } catch (Exception) {
        }
        try {
            DB::statement('ALTER TABLE dokumen DROP INDEX dokumen_uploaded_by_foreign');
        } catch (Exception) {
        }

        DB::statement('ALTER TABLE dokumen MODIFY COLUMN uploaded_by BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE dokumen ADD CONSTRAINT dokumen_uploaded_by_foreign FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE');
    }
};
