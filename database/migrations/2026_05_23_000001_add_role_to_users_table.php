<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['superadmin', 'admin_wilayah', 'kepala_sekolah'])->default('kepala_sekolah')->after('email');
            $table->string('wilayah')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('wilayah');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'wilayah', 'avatar']);
        });
    }
};
