<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teknologi_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->string('tahun_ajaran', 10)->default('2024/2025');
            $table->boolean('memiliki_lab_komputer')->default(false);
            $table->unsignedSmallInteger('jumlah_komputer_lab')->default(0);
            $table->unsignedSmallInteger('jumlah_komputer_admin')->default(0);
            $table->unsignedSmallInteger('jumlah_laptop_guru')->default(0);
            $table->boolean('memiliki_proyektor')->default(false);
            $table->unsignedSmallInteger('jumlah_proyektor')->default(0);
            $table->boolean('memiliki_internet')->default(false);
            $table->string('jenis_internet', 30)->nullable();
            $table->unsignedSmallInteger('bandwidth_mbps')->nullable();
            $table->boolean('memiliki_lms')->default(false);
            $table->string('nama_lms')->nullable();
            $table->boolean('memiliki_e_perpustakaan')->default(false);
            $table->boolean('memiliki_smart_classroom')->default(false);
            $table->boolean('memiliki_tenaga_it')->default(false);
            $table->text('aplikasi_pembelajaran')->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['sekolah_id', 'tahun_ajaran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teknologi_pembelajaran');
    }
};
