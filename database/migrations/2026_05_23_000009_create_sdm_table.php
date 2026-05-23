<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sdm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->string('tahun_ajaran', 10)->default('2024/2025');
            $table->unsignedSmallInteger('guru_pns')->default(0);
            $table->unsignedSmallInteger('guru_honorer')->default(0);
            $table->unsignedSmallInteger('guru_p3k')->default(0);
            $table->unsignedSmallInteger('karyawan_pns')->default(0);
            $table->unsignedSmallInteger('karyawan_honorer')->default(0);
            $table->unsignedSmallInteger('karyawan_p3k')->default(0);
            $table->unsignedSmallInteger('jumlah_rombel')->default(0);
            $table->unsignedSmallInteger('guru_bersertifikasi')->default(0);
            $table->unsignedSmallInteger('guru_s1_keatas')->default(0);
            $table->text('catatan_hambatan')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['sekolah_id', 'tahun_ajaran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdm');
    }
};
