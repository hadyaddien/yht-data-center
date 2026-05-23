<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provinsi extends Model
{
    protected $table = 'provinsi';

    protected $fillable = ['kode', 'nama'];

    public function kotaKabupaten(): HasMany
    {
        return $this->hasMany(KotaKabupaten::class, 'provinsi_id');
    }

    public function sekolah(): HasMany
    {
        return $this->hasMany(Sekolah::class, 'provinsi_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'provinsi_id');
    }
}
