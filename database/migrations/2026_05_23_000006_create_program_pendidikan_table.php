<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('program_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->string('tahun_ajaran', 10)->default('2024/2025');
            $table->string('kurikulum')->nullable();
            $table->text('program_unggulan')->nullable();
            $table->text('ekstrakurikuler')->nullable();
            $table->text('prestasi_siswa')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['sekolah_id', 'tahun_ajaran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_pendidikan');
    }
};
