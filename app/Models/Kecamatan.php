<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    public $timestamps = false;

    protected $table = 'kecamatan';

    protected $fillable = ['kota_kabupaten_id', 'nama'];

    public function kota()
    {
        return $this->belongsTo(KotaKabupaten::class, 'kota_kabupaten_id');
    }

    public function kelurahan()
    {
        return $this->hasMany(Kelurahan::class, 'kecamatan_id');
    }
}
