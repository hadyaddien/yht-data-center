<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Sekolah extends Model
{
    protected $table = 'sekolah';

    protected $fillable = [
        'npsn',
        'nama',
        'jenjang',
        'alamat',
        'kecamatan',
        'kelurahan',
        'kode_pos',
        'kota_id',
        'provinsi_id',
        'telepon',
        'fax',
        'email',
        'website',
        'akreditasi_nilai',
        'akreditasi_predikat',
        'akreditasi_tahun',
        'no_sk_akreditasi',
        'kepala_sekolah_nama',
        'kepala_sekolah_nip',
        'kepala_sekolah_hp',
        'operator_nama',
        'operator_hp',
        'tahun_berdiri',
        'luas_tanah',
        'logo',
        'kekuatan',
        'kelemahan',
        'status_operasional',
        'rapor_literasi',
        'rapor_numerasi',
        'rapor_karakter',
    ];

    protected $casts = [
        'akreditasi_nilai' => 'integer',
        'tahun_berdiri'    => 'integer',
        'luas_tanah'       => 'decimal:2',
        'rapor_literasi'   => 'decimal:2',
        'rapor_numerasi'   => 'decimal:2',
        'rapor_karakter'   => 'decimal:2',
    ];

    public function kota(): BelongsTo
    {
        return $this->belongsTo(KotaKabupaten::class, 'kota_id');
    }

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'sekolah_id');
    }

    public function programPendidikan(): HasMany
    {
        return $this->hasMany(ProgramPendidikan::class, 'sekolah_id');
    }

    public function teknologiPembelajaran(): HasMany
    {
        return $this->hasMany(TeknologiPembelajaran::class, 'sekolah_id');
    }

    public function saranaPrasarana(): HasMany
    {
        return $this->hasMany(SaranaPrasarana::class, 'sekolah_id');
    }

    public function sdm(): HasMany
    {
        return $this->hasMany(Sdm::class, 'sekolah_id');
    }

    public function sdmGuru(): HasMany
    {
        return $this->hasMany(SdmGuru::class, 'sekolah_id');
    }

    public function sdmRombel(): HasMany
    {
        return $this->hasMany(SdmRombel::class, 'sekolah_id');
    }

    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'sekolah_id');
    }

    public function getAkreditasiLabelAttribute(): ?string
    {
        if (! $this->akreditasi_nilai) {
            return null;
        }
        return $this->akreditasi_nilai . ' (' . $this->akreditasi_predikat . ')';
    }
}
