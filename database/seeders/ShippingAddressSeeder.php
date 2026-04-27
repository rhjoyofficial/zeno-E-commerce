<?php

namespace Database\Seeders;

use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Database\Seeder;

class ShippingAddressSeeder extends Seeder
{
    public function run(): void
    {
        $addresses = [
            'rahim@example.com' => ['Rahim Ahmed', 'Gulshan Avenue, Dhaka', 'Dhaka', 'Dhaka', '1212', '01710000001'],
            'nusrat@example.com' => ['Nusrat Jahan', 'Nasirabad Housing, Chattogram', 'Chattogram', 'Chattogram', '4203', '01810000002'],
            'sadia@example.com' => ['Sadia Islam', 'Dhanmondi 27, Dhaka', 'Dhaka', 'Dhaka', '1209', '01910000003'],
            'tanvir@example.com' => ['Tanvir Hasan', 'Uposhohor, Sylhet', 'Sylhet', 'Sylhet', '3100', '01610000004'],
        ];

        foreach ($addresses as $email => [$name, $address, $city, $state, $postalCode, $phone]) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                continue;
            }

            ShippingAddress::updateOrCreate(
                ['user_id' => $user->id, 'address' => $address],
                [
                    'user_id' => $user->id,
                    'guest_session_id' => null,
                    'name' => $name,
                    'address' => $address,
                    'city' => $city,
                    'state' => $state,
                    'country' => 'Bangladesh',
                    'postal_code' => $postalCode,
                    'phone' => $phone,
                    'is_default' => true,
                ]
            );
        }
    }
}
