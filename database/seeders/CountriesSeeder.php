<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // active markets with good shipping infrastructure (Discogs supported countries)
        DB::table('countries')->insert([
            ['name' => 'Austria', 'code' => 'AUT', 'continent' => 'Europe'],
            ['name' => 'Belgium', 'code' => 'BEL', 'continent' => 'Europe'],
            ['name' => 'Bosnia and Herzegovina', 'code' => 'BIH', 'continent' => 'Europe'],
            ['name' => 'Bulgaria', 'code' => 'BGR', 'continent' => 'Europe'],
            ['name' => 'Croatia', 'code' => 'HRV', 'continent' => 'Europe'],
            ['name' => 'Cyprus', 'code' => 'CYP', 'continent' => 'Europe'],
            ['name' => 'Czech Republic', 'code' => 'CZE', 'continent' => 'Europe'],
            ['name' => 'Denmark', 'code' => 'DNK', 'continent' => 'Europe'],
            ['name' => 'Estonia', 'code' => 'EE', 'continent' => 'Europe'],
            ['name' => 'Finland', 'code' => 'FIN', 'continent' => 'Europe'],
            ['name' => 'France', 'code' => 'FRA', 'continent' => 'Europe'],
            ['name' => 'Germany', 'code' => 'DEU', 'continent' => 'Europe'],
            ['name' => 'Greece', 'code' => 'GRC', 'continent' => 'Europe'],
            ['name' => 'Hungary', 'code' => 'HUN', 'continent' => 'Europe'],
            ['name' => 'Iceland', 'code' => 'ISL', 'continent' => 'Europe'],
            ['name' => 'Ireland', 'code' => 'IRL', 'continent' => 'Europe'],
            ['name' => 'Italy', 'code' => 'ITA', 'continent' => 'Europe'],
            ['name' => 'Latvia', 'code' => 'LV', 'continent' => 'Europe'],
            ['name' => 'Lithuania', 'code' => 'LT', 'continent' => 'Europe'],
            ['name' => 'Luxembourg', 'code' => 'LUX', 'continent' => 'Europe'],
            ['name' => 'Malta', 'code' => 'MLT', 'continent' => 'Europe'],
            ['name' => 'Netherlands', 'code' => 'NLD', 'continent' => 'Europe'],
            ['name' => 'Norway', 'code' => 'NOR', 'continent' => 'Europe'],
            ['name' => 'Poland', 'code' => 'POL', 'continent' => 'Europe'],
            ['name' => 'Portugal', 'code' => 'PRT', 'continent' => 'Europe'],
            ['name' => 'Romania', 'code' => 'ROU', 'continent' => 'Europe'],
            ['name' => 'Slovakia', 'code' => 'SVK', 'continent' => 'Europe'],
            ['name' => 'Slovenia', 'code' => 'SVN', 'continent' => 'Europe'],
            ['name' => 'Spain', 'code' => 'ESP', 'continent' => 'Europe'],
            ['name' => 'Sweden', 'code' => 'SWE', 'continent' => 'Europe'],
            ['name' => 'Switzerland', 'code' => 'CHE', 'continent' => 'Europe'],
            ['name' => 'United Kingdom', 'code' => 'GBR', 'continent' => 'Europe'],

            ['name' => 'United States', 'code' => 'USA', 'continent' => 'North America'],
            ['name' => 'Canada', 'code' => 'CAN', 'continent' => 'North America'],
            ['name' => 'Mexico', 'code' => 'MEX', 'continent' => 'North America'],
            ['name' => 'Brazil', 'code' => 'BRA', 'continent' => 'South America'],
            ['name' => 'Argentina', 'code' => 'ARG', 'continent' => 'South America'],
            ['name' => 'Chile', 'code' => 'CHL', 'continent' => 'South America'],
            ['name' => 'Colombia', 'code' => 'COL', 'continent' => 'South America'],

            ['name' => 'Japan', 'code' => 'JPN', 'continent' => 'Asia'],
            ['name' => 'South Korea', 'code' => 'KOR', 'continent' => 'Asia'],
            ['name' => 'Hong Kong', 'code' => 'HKG', 'continent' => 'Asia'],
            ['name' => 'Taiwan', 'code' => 'TWN', 'continent' => 'Asia'],
            ['name' => 'Thailand', 'code' => 'THA', 'continent' => 'Asia'],
        ]);
    }
}
