<?php

namespace Database\Seeders;

use App\Models\ShippingAddress;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ShippingAddressSeeder extends Seeder
{
    public function run(): void
    {
        $customerRole = Role::where('slug', 'customer')->firstOrFail();
        $adminRole    = Role::where('slug', 'admin')->firstOrFail();

        $customer = User::where('role_id', $customerRole->id)->firstOrFail();
        $admin    = User::where('role_id', $adminRole->id)->firstOrFail();

        $addresses = [
            [
                'user_id'     => $customer->id,
                'name'        => 'Customer User',
                'address'     => '456 Green Road, Agrabad',
                'city'        => 'Chittagong',
                'state'       => 'Chittagong Division',
                'country'     => 'Bangladesh',
                'postal_code' => '4000',
                'phone'       => '01800000002',
                'is_default'  => true,
                'created_by'  => $admin->id,
                'updated_by'  => $admin->id,
            ],
            [
                'user_id'     => $customer->id,
                'name'        => 'Customer User',
                'address'     => '88 Mirpur Road, Section-6',
                'city'        => 'Dhaka',
                'state'       => 'Dhaka Division',
                'country'     => 'Bangladesh',
                'postal_code' => '1216',
                'phone'       => '01800000002',
                'is_default'  => false,
                'created_by'  => $admin->id,
                'updated_by'  => $admin->id,
            ],
            [
                'user_id'     => $admin->id,
                'name'        => 'Admin User',
                'address'     => '123 Admin Street, Dhanmondi',
                'city'        => 'Dhaka',
                'state'       => 'Dhaka Division',
                'country'     => 'Bangladesh',
                'postal_code' => '1205',
                'phone'       => '01700000001',
                'is_default'  => true,
                'created_by'  => $admin->id,
                'updated_by'  => $admin->id,
            ],
        ];

        ShippingAddress::upsert(
            $addresses,
            ['user_id', 'address'],
            ['name', 'city', 'state', 'country', 'postal_code', 'phone', 'is_default', 'created_by', 'updated_by']
        );

        // Extra addresses via factory for other users
        ShippingAddress::factory()->count(8)->create([
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ]);
    }
}
