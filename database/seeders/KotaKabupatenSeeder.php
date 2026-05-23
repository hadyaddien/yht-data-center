<?php

namespace Database\Seeders;

use App\Models\KotaKabupaten;
use App\Models\Provinsi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KotaKabupatenSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        KotaKabupaten::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $jakartaId       = Provinsi::where('kode', '31')->value('id');
        $jawatimurId     = Provinsi::where('kode', '35')->value('id');
        $sulselId        = Provinsi::where('kode', '73')->value('id');
        $sulutId         = Provinsi::where('kode', '71')->value('id');
        $jawatengahId    = Provinsi::where('kode', '33')->value('id');
        $jawaBarat       = Provinsi::where('kode', '32')->value('id');

        $data = [
            // DKI Jakarta
            ['provinsi_id' => $jakartaId, 'kode' => '3171', 'nama' => 'Kota Jakarta Selatan', 'jenis' => 'kota'],
            ['provinsi_id' => $jakartaId, 'kode' => '3172', 'nama' => 'Kota Jakarta Timur',   'jenis' => 'kota'],
            ['provinsi_id' => $jakartaId, 'kode' => '3173', 'nama' => 'Kota Jakarta Pusat',   'jenis' => 'kota'],
            ['provinsi_id' => $jakartaId, 'kode' => '3174', 'nama' => 'Kota Jakarta Barat',   'jenis' => 'kota'],
            ['provinsi_id' => $jakartaId, 'kode' => '3175', 'nama' => 'Kota Jakarta Utara',   'jenis' => 'kota'],
            // Jawa Timur
            ['provinsi_id' => $jawatimurId, 'kode' => '3578', 'nama' => 'Kota Surabaya',      'jenis' => 'kota'],
            ['provinsi_id' => $jawatimurId, 'kode' => '3573', 'nama' => 'Kota Malang',         'jenis' => 'kota'],
            // Sulawesi Selatan
            ['provinsi_id' => $sulselId,  'kode' => '7371', 'nama' => 'Kota Makassar',         'jenis' => 'kota'],
            ['provinsi_id' => $sulselId,  'kode' => '7372', 'nama' => 'Kota Parepare',         'jenis' => 'kota'],
            // Sulawesi Utara
            ['provinsi_id' => $sulutId,   'kode' => '7171', 'nama' => 'Kota Manado',           'jenis' => 'kota'],
            ['provinsi_id' => $sulutId,   'kode' => '7172', 'nama' => 'Kota Bitung',           'jenis' => 'kota'],
            // Jawa Tengah
            ['provinsi_id' => $jawatengahId, 'kode' => '3374', 'nama' => 'Kota Semarang',      'jenis' => 'kota'],
            // Jawa Barat
            ['provinsi_id' => $jawaBarat, 'kode' => '3273', 'nama' => 'Kota Bandung',          'jenis' => 'kota'],
        ];

        foreach ($data as $item) {
            KotaKabupaten::create($item);
        }
    }
}

