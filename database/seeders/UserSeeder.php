<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole    = Role::where('slug', 'admin')->firstOrFail();
        $customerRole = Role::where('slug', 'customer')->firstOrFail();

        // ── Admin User ─────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name'                  => 'Admin User',
                'email_verified_at'     => now(),
                'password'              => Hash::make('password'),
                'otp'                   => null,
                'otp_attempts'          => 0,
                'otp_last_attempt'      => null,
                'otp_blocked_until'     => null,
                'otp_requests_today'    => 0,
                'last_otp_request_date' => null,
                'status'                => 'active',
                'role_id'               => $adminRole->id,
                'entry_user_id'         => null,
            ]
        );

        CustomerProfile::firstOrCreate(
            ['user_id' => $admin->id],
            [
                'name'          => 'Admin User',
                'address'       => '123 Admin Street, Dhaka',
                'city'          => 'Dhaka',
                'state'         => 'Dhaka Division',
                'postal_code'   => '1200',
                'phone'         => '01700000001',
                'entry_user_id' => $admin->id,
            ]
        );

        // ── Customer User ──────────────────────────────────────────────
        $customer = User::firstOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name'                  => 'Customer User',
                'email_verified_at'     => now(),
                'password'              => Hash::make('password'),
                'otp'                   => null,
                'otp_attempts'          => 0,
                'otp_last_attempt'      => null,
                'otp_blocked_until'     => null,
                'otp_requests_today'    => 0,
                'last_otp_request_date' => null,
                'status'                => 'active',
                'role_id'               => $customerRole->id,
                'entry_user_id'         => $admin->id,
            ]
        );

        CustomerProfile::firstOrCreate(
            ['user_id' => $customer->id],
            [
                'name'          => 'Customer User',
                'address'       => '456 Green Road, Chittagong',
                'city'          => 'Chittagong',
                'state'         => 'Chittagong Division',
                'postal_code'   => '4000',
                'phone'         => '01800000002',
                'entry_user_id' => $admin->id,
            ]
        );

        // ── Extra Customer Users via factory ───────────────────────────
        User::factory()->count(8)->create([
            'role_id'       => $customerRole->id,
            'entry_user_id' => $admin->id,
        ])->each(function ($user) use ($admin) {
            CustomerProfile::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'name'          => $user->name,
                    'address'       => fake()->streetAddress(),
                    'city'          => fake()->city(),
                    'state'         => 'Dhaka Division',
                    'postal_code'   => fake()->postcode(),
                        'phone'         => '017' . fake()->numerify('########'),
                        'entry_user_id' => $admin->id,
                ]
            );
        });
    }
}