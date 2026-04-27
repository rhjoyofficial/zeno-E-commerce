<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\District;
use Illuminate\Database\Seeder;

class DistrictSeeder extends Seeder
{
    public function run(): void
    {
        // Map of division name => districts
        $districtMap = [
            'Dhaka' => [
                'Dhaka', 'Gazipur', 'Narayanganj', 'Narsingdi', 'Manikganj',
                'Munshiganj', 'Kishoreganj', 'Tangail', 'Faridpur', 'Rajbari',
                'Madaripur', 'Gopalganj', 'Shariatpur',
            ],
            'Chittagong' => [
                'Chittagong', "Cox's Bazar", 'Feni', 'Lakshmipur', 'Noakhali',
                'Chandpur', 'Comilla', 'Brahmanbaria', 'Rangamati', 'Khagrachhari', 'Bandarban',
            ],
            'Rajshahi' => [
                'Rajshahi', 'Bogra', 'Joypurhat', 'Naogaon', 'Natore',
                'Chapainawabganj', 'Pabna', 'Sirajganj',
            ],
            'Khulna' => [
                'Khulna', 'Bagerhat', 'Satkhira', 'Jessore', 'Jhenaidah',
                'Magura', 'Narail', 'Chuadanga', 'Meherpur', 'Kushtia',
            ],
            'Barisal' => [
                'Barisal', 'Bhola', 'Jhalokati', 'Patuakhali', 'Pirojpur', 'Barguna',
            ],
            'Sylhet' => [
                'Sylhet', 'Habiganj', 'Moulvibazar', 'Sunamganj',
            ],
            'Rangpur' => [
                'Rangpur', 'Dinajpur', 'Gaibandha', 'Kurigram', 'Lalmonirhat',
                'Nilphamari', 'Panchagarh', 'Thakurgaon',
            ],
            'Mymensingh' => [
                'Mymensingh', 'Jamalpur', 'Netrokona', 'Sherpur',
            ],
        ];

        foreach ($districtMap as $divisionName => $districts) {
            $division = Division::where('name', $divisionName)->first();

            if (!$division) {
                $this->command->warn("Division '{$divisionName}' not found. Skipping its districts.");
                continue;
            }

            foreach ($districts as $districtName) {
                District::firstOrCreate(
                    ['name' => $districtName, 'division_id' => $division->id],
                    ['name' => $districtName, 'division_id' => $division->id]
                );
            }
        }
    }
}
