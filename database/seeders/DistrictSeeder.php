<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Division;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        $districts = [
            'Dhaka' => ['Dhaka', 'Gazipur', 'Narayanganj'],
            'Chattogram' => ['Chattogram', 'Coxs Bazar', 'Cumilla'],
            'Rajshahi' => ['Rajshahi', 'Bogura'],
            'Khulna' => ['Khulna', 'Jashore'],
            'Barishal' => ['Barishal'],
            'Sylhet' => ['Sylhet'],
            'Rangpur' => ['Rangpur'],
            'Mymensingh' => ['Mymensingh'],
        ];

        foreach ($districts as $divisionName => $names) {
            $division = Division::where('name', $divisionName)->firstOrFail();
            foreach ($names as $name) {
                District::updateOrCreate(
                    ['name' => $name, 'division_id' => $division->id],
                    ['name' => $name, 'division_id' => $division->id]
                );
            }
        }
    }
}
