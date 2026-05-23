<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $this->call([
            ProvinsiSeeder::class,
            KotaKabupatenSeeder::class,
            SekolahSeeder::class,
            UserSeeder::class,
            ProgramPendidikanSeeder::class,
            TeknologiPembelajaranSeeder::class,
            SaranaPrasaranaSeeder::class,
            SdmSeeder::class,
        ]);

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
