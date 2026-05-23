<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    public $timestamps = false;
    protected $table = 'kelurahan';
    protected $fillable = ['kecamatan_id', 'nama'];

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }
}
