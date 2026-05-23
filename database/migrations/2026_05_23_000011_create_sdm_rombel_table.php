<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sdm_rombel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->string('tahun_ajaran', 10)->default('2024/2025');
            $table->string('tingkat', 20);
            $table->string('nama_rombel', 50);
            $table->unsignedSmallInteger('siswa_laki')->default(0);
            $table->unsignedSmallInteger('siswa_perempuan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdm_rombel');
    }
};
