<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SdmGuru extends Model
{
    protected $table = 'sdm_guru';

    protected $fillable = [
        'sekolah_id',
        'nama',
        'nip',
        'nuptk',
        'status_kepegawaian',
        'mata_pelajaran',
        'kualifikasi',
        'program_studi',
        'sertifikasi',
        'tahun_sertifikasi',
        'jenis_kelamin',
        'tanggal_lahir',
        'no_hp',
    ];

    protected $casts = [
        'sertifikasi' => 'boolean',
        'tanggal_lahir' => 'date',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }
}
