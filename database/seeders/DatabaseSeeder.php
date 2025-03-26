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
        if (DB::table('countries')->count() === 0) {
            $this->call(CountriesSeeder::class);
        }

        // I redid logic here, so i could add new release to the DB via seeder
        $this->call(VinylSeeder::class); 
    }
}
