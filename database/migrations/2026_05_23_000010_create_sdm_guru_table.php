<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sdm_guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->string('nama');
            $table->string('nip', 30)->nullable();
            $table->string('nuptk', 20)->nullable();
            $table->enum('status_kepegawaian', ['pns', 'honorer', 'p3k']);
            $table->string('mata_pelajaran')->nullable();
            $table->enum('kualifikasi', ['SMA', 'D3', 'S1', 'S2', 'S3'])->default('S1');
            $table->string('program_studi')->nullable();
            $table->boolean('sertifikasi')->default(false);
            $table->unsignedSmallInteger('tahun_sertifikasi')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sdm_guru');
    }
};
