<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SdmRombel extends Model
{
    protected $table = 'sdm_rombel';

    protected $fillable = [
        'sekolah_id',
        'tahun_ajaran',
        'tingkat',
        'nama_rombel',
        'siswa_laki',
        'siswa_perempuan',
    ];

    protected $casts = [
        'siswa_laki' => 'integer',
        'siswa_perempuan' => 'integer',
    ];

    public function getTotalSiswaAttribute(): int
    {
        return $this->siswa_laki + $this->siswa_perempuan;
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
