<?php

namespace Database\Seeders;

use App\Models\Provinsi;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        $jakartaId  = Provinsi::where('kode', '31')->value('id');
        $sulselId   = Provinsi::where('kode', '73')->value('id');

        $sdMakassar = Sekolah::where('npsn', '40312156')->value('id');
        $smpJakarta = Sekolah::where('npsn', '20106955')->value('id');
        $smaSuby    = Sekolah::where('npsn', '20532142')->value('id');
        $tkManado   = Sekolah::where('npsn', '60214578')->value('id');
        $smkJakarta = Sekolah::where('npsn', '20106890')->value('id');

        User::create([
            'name'        => 'Super Admin YHT',
            'email'       => 'superadmin@yht.ac.id',
            'password'    => Hash::make('password'),
            'role'        => 'superadmin',
            'provinsi_id' => null,
            'sekolah_id'  => null,
        ]);

        User::create([
            'name'        => 'Admin Wilayah Jakarta',
            'email'       => 'admin.jakarta@yht.ac.id',
            'password'    => Hash::make('password'),
            'role'        => 'admin_wilayah',
            'provinsi_id' => $jakartaId,
            'sekolah_id'  => null,
        ]);

        User::create([
            'name'        => 'Admin Wilayah Sulawesi Selatan',
            'email'       => 'admin.sulsel@yht.ac.id',
            'password'    => Hash::make('password'),
            'role'        => 'admin_wilayah',
            'provinsi_id' => $sulselId,
            'sekolah_id'  => null,
        ]);

        User::create([
            'name'        => 'Sri Wahyuni, S.Pd',
            'email'       => 'kepsek.sd6mks@yht.ac.id',
            'password'    => Hash::make('password'),
            'role'        => 'kepala_sekolah',
            'provinsi_id' => null,
            'sekolah_id'  => $sdMakassar,
        ]);

        User::create([
            'name'        => 'Drs. Aris Supriyanto',
            'email'       => 'kepsek.smp2jkt@yht.ac.id',
            'password'    => Hash::make('password'),
            'role'        => 'kepala_sekolah',
            'provinsi_id' => null,
            'sekolah_id'  => $smpJakarta,
        ]);

        User::create([
            'name'        => 'Drs. Bambang Suryadi, M.Pd',
            'email'       => 'kepsek.sma1sby@yht.ac.id',
            'password'    => Hash::make('password'),
            'role'        => 'kepala_sekolah',
            'provinsi_id' => null,
            'sekolah_id'  => $smaSuby,
        ]);

        User::create([
            'name'        => 'Ibu Maria Tangkudung, S.Pd',
            'email'       => 'kepsek.tk3mnd@yht.ac.id',
            'password'    => Hash::make('password'),
            'role'        => 'kepala_sekolah',
            'provinsi_id' => null,
            'sekolah_id'  => $tkManado,
        ]);

        User::create([
            'name'        => 'Capt. Hendro Wibowo, M.M',
            'email'       => 'kepsek.smk1jkt@yht.ac.id',
            'password'    => Hash::make('password'),
            'role'        => 'kepala_sekolah',
            'provinsi_id' => null,
            'sekolah_id'  => $smkJakarta,
        ]);
    }
}
