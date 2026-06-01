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
        'jumlah_guru',
        'guru_tetap_yayasan',
        'guru_tidak_tetap',
        'guru_s1_pendidikan',
        'guru_s1_non_pendidikan',
        'guru_s2',
        'guru_s3',
        'guru_sertifikasi',
        'jumlah_karyawan',
        'karyawan_tetap',
        'karyawan_tidak_tetap',
        'guru_pns',
        'guru_honorer',
        'guru_p3k',
        'karyawan_pns',
        'karyawan_honorer',
        'karyawan_p3k',
        'jumlah_rombel',
        'jumlah_murid_total',
        'jumlah_murid_laki',
        'jumlah_murid_perempuan',
        'murid_ortu_tni_al',
        'murid_ortu_tni',
        'murid_ortu_polisi',
        'murid_ortu_pns',
        'murid_ortu_pengusaha',
        'murid_ortu_wiraswasta',
        'murid_ortu_buruh',
        'murid_ortu_guru',
        'murid_ortu_lainnya_label',
        'murid_ortu_lainnya_jumlah',
        'rata_gaji_guru',
        'rata_gaji_karyawan',
        'masa_jabatan_kepsek',
        'hambatan_tantangan',
        'guru_bersertifikasi',
        'guru_s1_keatas',
        'catatan_hambatan',
        'updated_by',
    ];

    protected $casts = [
        'jumlah_guru'          => 'integer',
        'guru_tetap_yayasan'   => 'integer',
        'guru_tidak_tetap'     => 'integer',
        'guru_s1_pendidikan'   => 'integer',
        'guru_s1_non_pendidikan' => 'integer',
        'guru_s2'              => 'integer',
        'guru_s3'              => 'integer',
        'guru_sertifikasi'     => 'integer',
        'jumlah_karyawan'      => 'integer',
        'karyawan_tetap'       => 'integer',
        'karyawan_tidak_tetap' => 'integer',
        'guru_pns'            => 'integer',
        'guru_honorer'        => 'integer',
        'guru_p3k'            => 'integer',
        'karyawan_pns'        => 'integer',
        'karyawan_honorer'    => 'integer',
        'karyawan_p3k'        => 'integer',
        'jumlah_rombel'       => 'integer',
        'jumlah_murid_total'  => 'integer',
        'jumlah_murid_laki'   => 'integer',
        'jumlah_murid_perempuan' => 'integer',
        'murid_ortu_tni_al'    => 'integer',
        'murid_ortu_tni'       => 'integer',
        'murid_ortu_polisi'    => 'integer',
        'murid_ortu_pns'       => 'integer',
        'murid_ortu_pengusaha' => 'integer',
        'murid_ortu_wiraswasta' => 'integer',
        'murid_ortu_buruh'     => 'integer',
        'murid_ortu_guru'      => 'integer',
        'murid_ortu_lainnya_jumlah' => 'integer',
        'rata_gaji_guru'      => 'integer',
        'rata_gaji_karyawan'  => 'integer',
        'masa_jabatan_kepsek' => 'integer',
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
