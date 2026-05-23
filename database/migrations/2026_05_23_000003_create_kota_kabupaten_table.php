<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kota_kabupaten', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provinsi_id')->constrained('provinsi')->cascadeOnDelete();
            $table->string('kode', 10)->unique();
            $table->string('nama');
            $table->enum('jenis', ['kota', 'kabupaten'])->default('kota');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kota_kabupaten');
    }
};
