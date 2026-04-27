<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $country = Country::where('code', 'BD')->firstOrFail();

        foreach (['Dhaka', 'Chattogram', 'Rajshahi', 'Khulna', 'Barishal', 'Sylhet', 'Rangpur', 'Mymensingh'] as $name) {
            Division::updateOrCreate(
                ['name' => $name, 'country_id' => $country->id],
                ['name' => $name, 'country_id' => $country->id]
            );
        }
    }
}
