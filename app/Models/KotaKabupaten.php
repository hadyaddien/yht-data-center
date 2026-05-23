<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KotaKabupaten extends Model
{
    protected $table = 'kota_kabupaten';

    protected $fillable = ['provinsi_id', 'kode', 'nama', 'jenis'];

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function sekolah(): HasMany
    {
        return $this->hasMany(Sekolah::class, 'kota_id');
    }
}
