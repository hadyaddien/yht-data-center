<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provinsi extends Model
{
    protected $table = 'indonesia_provinces';

    protected $fillable = ['code', 'name'];

    protected $appends = ['kode', 'nama'];

    public function kotaKabupaten(): HasMany
    {
        return $this->hasMany(KotaKabupaten::class, 'province_code', 'code');
    }

    public function sekolah(): HasMany
    {
        return $this->hasMany(Sekolah::class, 'provinsi_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'provinsi_id');
    }

    public function getNamaAttribute(): ?string
    {
        return $this->attributes['name'] ?? null;
    }

    public function getKodeAttribute(): ?string
    {
        return $this->attributes['code'] ?? null;
    }
}
