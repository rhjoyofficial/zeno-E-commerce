<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            ['name' => 'Bangladesh',      'code' => 'BD'],
            ['name' => 'India',           'code' => 'IN'],
            ['name' => 'United States',   'code' => 'US'],
            ['name' => 'United Kingdom',  'code' => 'GB'],
            ['name' => 'Canada',          'code' => 'CA'],
            ['name' => 'Australia',       'code' => 'AU'],
            ['name' => 'Germany',         'code' => 'DE'],
            ['name' => 'France',          'code' => 'FR'],
            ['name' => 'Pakistan',        'code' => 'PK'],
            ['name' => 'Saudi Arabia',    'code' => 'SA'],
        ];

        Country::upsert($countries, ['code'], ['name']);
    }
}
