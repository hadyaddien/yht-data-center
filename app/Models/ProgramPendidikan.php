<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramPendidikan extends Model
{
    protected $table = 'program_pendidikan';

    protected $fillable = [
        'sekolah_id',
        'tahun_ajaran',
        'kurikulum',
        'program_unggulan',
        'ekstrakurikuler',
        'prestasi_siswa',
        'visi',
        'misi',
        'catatan',
        'updated_by',
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
