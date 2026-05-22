<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        User::create([
            'name'     => 'Super Admin YHT',
            'email'    => 'superadmin@yht.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'superadmin',
            'wilayah'  => null,
        ]);

        User::create([
            'name'     => 'Admin Wilayah Jakarta',
            'email'    => 'admin.jakarta@yht.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'admin_wilayah',
            'wilayah'  => 'Jakarta',
        ]);

        User::create([
            'name'     => 'Admin Wilayah Makassar',
            'email'    => 'admin.makassar@yht.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'admin_wilayah',
            'wilayah'  => 'Sulawesi Selatan',
        ]);

        User::create([
            'name'     => 'Kepala Sekolah SD HT 6',
            'email'    => 'kepsek.sd6@yht.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'kepala_sekolah',
            'wilayah'  => 'Makassar',
        ]);
    }
}
