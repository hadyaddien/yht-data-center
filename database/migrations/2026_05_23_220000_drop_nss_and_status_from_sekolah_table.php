<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            if (Schema::hasColumn('sekolah', 'nss')) {
                $table->dropColumn('nss');
            }

            if (Schema::hasColumn('sekolah', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sekolah', function (Blueprint $table) {
            if (!Schema::hasColumn('sekolah', 'nss')) {
                $table->string('nss', 20)->nullable()->after('npsn');
            }

            if (!Schema::hasColumn('sekolah', 'status')) {
                $table->enum('status', ['negeri', 'swasta'])->nullable()->after('jenjang');
            }
        });
    }
};
