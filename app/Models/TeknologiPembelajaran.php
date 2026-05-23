<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeknologiPembelajaran extends Model
{
    protected $table = 'teknologi_pembelajaran';

    protected $fillable = [
        'sekolah_id',
        'tahun_ajaran',
        'memiliki_lab_komputer',
        'jumlah_komputer_lab',
        'jumlah_komputer_admin',
        'jumlah_laptop_guru',
        'memiliki_proyektor',
        'jumlah_proyektor',
        'memiliki_internet',
        'jenis_internet',
        'bandwidth_mbps',
        'memiliki_lms',
        'nama_lms',
        'memiliki_e_perpustakaan',
        'memiliki_smart_classroom',
        'memiliki_tenaga_it',
        'aplikasi_pembelajaran',
        'catatan',
        'updated_by',
    ];

    protected $casts = [
        'memiliki_lab_komputer'    => 'boolean',
        'memiliki_proyektor'       => 'boolean',
        'memiliki_internet'        => 'boolean',
        'memiliki_lms'             => 'boolean',
        'memiliki_e_perpustakaan'  => 'boolean',
        'memiliki_smart_classroom' => 'boolean',
        'memiliki_tenaga_it'       => 'boolean',
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
