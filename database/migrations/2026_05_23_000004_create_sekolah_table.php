<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sekolah', function (Blueprint $table) {
            $table->id();
            $table->string('npsn', 20)->unique();
            $table->string('nss', 20)->nullable();
            $table->string('nama');
            $table->enum('jenjang', ['KB', 'TK', 'SD', 'SMP', 'SMA', 'SMK']);
            $table->enum('status', ['negeri', 'swasta'])->default('swasta');
            $table->text('alamat')->nullable();
            $table->foreignId('kota_id')->nullable()->constrained('kota_kabupaten')->nullOnDelete();
            $table->foreignId('provinsi_id')->constrained('provinsi')->cascadeOnDelete();
            $table->string('telepon', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->unsignedSmallInteger('akreditasi_nilai')->nullable();
            $table->string('akreditasi_predikat', 20)->nullable();
            $table->unsignedSmallInteger('akreditasi_tahun')->nullable();
            $table->string('kepala_sekolah_nama')->nullable();
            $table->string('kepala_sekolah_nip', 30)->nullable();
            $table->unsignedSmallInteger('tahun_berdiri')->nullable();
            $table->decimal('luas_tanah', 10, 2)->nullable();
            $table->string('logo')->nullable();
            $table->enum('status_operasional', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->decimal('rapor_literasi', 5, 2)->nullable();
            $table->decimal('rapor_numerasi', 5, 2)->nullable();
            $table->decimal('rapor_karakter', 5, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolah');
    }
};
