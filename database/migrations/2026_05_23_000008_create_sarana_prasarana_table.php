<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sarana_prasarana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')->constrained('sekolah')->cascadeOnDelete();
            $table->string('tahun_ajaran', 10)->default('2024/2025');
            $table->unsignedSmallInteger('jumlah_ruang_kelas')->default(0);
            $table->enum('kondisi_ruang_kelas', ['baik', 'rusak_ringan', 'rusak_sedang', 'rusak_berat'])->nullable();
            $table->boolean('memiliki_perpustakaan')->default(false);
            $table->enum('kondisi_perpustakaan', ['baik', 'rusak_ringan', 'rusak_sedang', 'rusak_berat'])->nullable();
            $table->boolean('memiliki_laboratorium')->default(false);
            $table->text('jenis_laboratorium')->nullable();
            $table->boolean('memiliki_uks')->default(false);
            $table->enum('kondisi_uks', ['baik', 'rusak_ringan', 'rusak_sedang', 'rusak_berat'])->nullable();
            $table->boolean('memiliki_lapangan')->default(false);
            $table->enum('kondisi_lapangan', ['baik', 'rusak_ringan', 'rusak_sedang', 'rusak_berat'])->nullable();
            $table->decimal('luas_bangunan_m2', 10, 2)->nullable();
            $table->enum('status_kepemilikan', ['milik_sendiri', 'sewa', 'pinjam_pakai'])->nullable();
            $table->decimal('skor_rata_rata', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['sekolah_id', 'tahun_ajaran']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sarana_prasarana');
    }
};
