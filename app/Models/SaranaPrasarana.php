<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaranaPrasarana extends Model
{
    protected $table = 'sarana_prasarana';

    protected $fillable = [
        'sekolah_id',
        'tahun_ajaran',
        'jumlah_ruang_kelas',
        'kondisi_ruang_kelas',
        'memiliki_perpustakaan',
        'kondisi_perpustakaan',
        'memiliki_laboratorium',
        'jenis_laboratorium',
        'memiliki_uks',
        'kondisi_uks',
        'memiliki_lapangan',
        'kondisi_lapangan',
        'luas_bangunan_m2',
        'status_kepemilikan',
        'skor_rata_rata',
        'catatan',
        'updated_by',
    ];

    protected $casts = [
        'memiliki_perpustakaan' => 'boolean',
        'memiliki_laboratorium' => 'boolean',
        'memiliki_uks'          => 'boolean',
        'memiliki_lapangan'     => 'boolean',
        'skor_rata_rata'        => 'decimal:2',
        'luas_bangunan_m2'      => 'decimal:2',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
