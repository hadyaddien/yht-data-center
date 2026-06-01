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
        'software_aplikasi_pembelajaran_status',
        'lms_kemendikdasmen_status',
        'aplikasi_smart_classroom_status',
        'koleksi_ebook_status',
        'website_sekolah_status',
        'server_pembelajaran_status',
        'tenaga_khusus_it_status',
        'media_sosial',
        'platform_lms',
        'platform_pendidikan',
        'alat_interaktif',
        'platform_komunikasi',
        'aplikasi_manajemen',
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
        'memiliki_lab_komputer' => 'boolean',
        'memiliki_proyektor' => 'boolean',
        'memiliki_internet' => 'boolean',
        'memiliki_lms' => 'boolean',
        'memiliki_e_perpustakaan' => 'boolean',
        'memiliki_smart_classroom' => 'boolean',
        'memiliki_tenaga_it' => 'boolean',
        'media_sosial' => 'array',
        'platform_lms' => 'array',
        'platform_pendidikan' => 'array',
        'alat_interaktif' => 'array',
        'platform_komunikasi' => 'array',
        'aplikasi_manajemen' => 'array',
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
