<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        // Bangladesh is the primary country for this e-commerce platform
        $bangladesh = Country::where('code', 'BD')->first();

        if (!$bangladesh) {
            $this->command->warn('Bangladesh country not found. Run CountrySeeder first.');
            return;
        }

        $divisions = [
            ['name' => 'Dhaka',        'country_id' => $bangladesh->id],
            ['name' => 'Chittagong',   'country_id' => $bangladesh->id],
            ['name' => 'Rajshahi',     'country_id' => $bangladesh->id],
            ['name' => 'Khulna',       'country_id' => $bangladesh->id],
            ['name' => 'Barisal',      'country_id' => $bangladesh->id],
            ['name' => 'Sylhet',       'country_id' => $bangladesh->id],
            ['name' => 'Rangpur',      'country_id' => $bangladesh->id],
            ['name' => 'Mymensingh',   'country_id' => $bangladesh->id],
        ];

        foreach ($divisions as $division) {
            Division::firstOrCreate(
                ['name' => $division['name'], 'country_id' => $division['country_id']],
                $division
            );
        }
    }
}
