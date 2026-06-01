<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class KotaKabupaten extends Model
{
    protected $table = 'indonesia_cities';

    protected $fillable = ['province_code', 'code', 'name'];

    protected $appends = ['nama', 'kode', 'jenis'];

    public function provinsi(): BelongsTo
    {
        return $this->belongsTo(Provinsi::class, 'province_code', 'code');
    }

    public function sekolah(): HasMany
    {
        return $this->hasMany(Sekolah::class, 'kota_id');
    }

    public function getNamaAttribute(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    public function getKodeAttribute(): ?string
    {
        return $this->attributes['code'] ?? null;
    }

    public function getJenisAttribute(): string
    {
        $name = strtoupper(trim((string) ($this->attributes['name'] ?? '')));

        return Str::startsWith($name, 'KOTA ') ? 'kota' : 'kabupaten';
    }
}
