<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sdm extends Model
{
    protected $table = 'sdm';

    protected $fillable = [
        'sekolah_id',
        'tahun_ajaran',
        'guru_pns',
        'guru_honorer',
        'guru_p3k',
        'karyawan_pns',
        'karyawan_honorer',
        'karyawan_p3k',
        'jumlah_rombel',
        'guru_bersertifikasi',
        'guru_s1_keatas',
        'catatan_hambatan',
        'updated_by',
    ];

    protected $casts = [
        'guru_pns'            => 'integer',
        'guru_honorer'        => 'integer',
        'guru_p3k'            => 'integer',
        'karyawan_pns'        => 'integer',
        'karyawan_honorer'    => 'integer',
        'karyawan_p3k'        => 'integer',
        'jumlah_rombel'       => 'integer',
        'guru_bersertifikasi' => 'integer',
        'guru_s1_keatas'      => 'integer',
    ];

    public function getTotalGuruAttribute(): int
    {
        return $this->guru_pns + $this->guru_honorer + $this->guru_p3k;
    }

    public function getTotalKaryawanAttribute(): int
    {
        return $this->karyawan_pns + $this->karyawan_honorer + $this->karyawan_p3k;
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
