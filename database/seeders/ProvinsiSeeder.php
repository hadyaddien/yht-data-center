<?php

namespace Database\Seeders;

use App\Models\Provinsi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinsiSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Provinsi::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $data = [
            ['kode' => '31', 'nama' => 'DKI Jakarta'],
            ['kode' => '32', 'nama' => 'Jawa Barat'],
            ['kode' => '33', 'nama' => 'Jawa Tengah'],
            ['kode' => '34', 'nama' => 'DI Yogyakarta'],
            ['kode' => '35', 'nama' => 'Jawa Timur'],
            ['kode' => '36', 'nama' => 'Banten'],
            ['kode' => '51', 'nama' => 'Bali'],
            ['kode' => '61', 'nama' => 'Kalimantan Barat'],
            ['kode' => '63', 'nama' => 'Kalimantan Selatan'],
            ['kode' => '71', 'nama' => 'Sulawesi Utara'],
            ['kode' => '73', 'nama' => 'Sulawesi Selatan'],
            ['kode' => '74', 'nama' => 'Sulawesi Tenggara'],
            ['kode' => '76', 'nama' => 'Sulawesi Barat'],
            ['kode' => '81', 'nama' => 'Maluku'],
            ['kode' => '91', 'nama' => 'Papua'],
        ];

        foreach ($data as $item) {
            Provinsi::create($item);
        }
    }
}

