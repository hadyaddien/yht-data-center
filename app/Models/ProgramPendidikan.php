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
        'updated_by',
        'kurikulum',
        'kurikulum_kebaharian',
        'jumlah_guru_kebaharian',
        'program_unggulan',
        'ekstrakurikuler',
        'prestasi_siswa',
        'visi',
        'misi',
        'catatan',
        'nilai_ujian_ta1',
        'nilai_ujian_ta2',
        'pbd_literasi',
        'pbd_numerasi',
        'pbd_karakter',
        'pbd_kualitas_pembelajaran',
        'pbd_iklim_keamanan',
        'pbd_iklim_kebhinekaan',
        'prestasi_akad_2025_kota',
        'prestasi_akad_2025_provinsi',
        'prestasi_akad_2025_nasional',
        'prestasi_akad_2025_internasional',
        'prestasi_akad_2026_kota',
        'prestasi_akad_2026_provinsi',
        'prestasi_akad_2026_nasional',
        'prestasi_akad_2026_internasional',
        'prestasi_non_2025_kota',
        'prestasi_non_2025_provinsi',
        'prestasi_non_2025_nasional',
        'prestasi_non_2025_internasional',
        'prestasi_non_2026_kota',
        'prestasi_non_2026_provinsi',
        'prestasi_non_2026_nasional',
        'prestasi_non_2026_internasional',
        'penerimaan_bos',
        'penerimaan_bop',
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
