<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('wilayah');
            $table->foreignId('provinsi_id')->nullable()->after('role')
                ->constrained('provinsi')->nullOnDelete();
            $table->foreignId('sekolah_id')->nullable()->after('provinsi_id')
                ->constrained('sekolah')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['provinsi_id']);
            $table->dropForeign(['sekolah_id']);
            $table->dropColumn(['provinsi_id', 'sekolah_id']);
            $table->string('wilayah')->nullable()->after('role');
        });
    }
};
