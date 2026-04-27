<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\CustomerProfile;
use App\Models\District;
use App\Models\Division;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'admin')->firstOrFail();
        $customerRole = Role::where('slug', 'customer')->firstOrFail();

        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            $this->userData('Admin User', 'admin@example.com', $adminRole->id)
        );

        $customers = [
            ['Rahim Ahmed', 'rahim@example.com', 'Gulshan Avenue, Dhaka', 'Dhaka', 'Dhaka', '1212', '01710000001'],
            ['Nusrat Jahan', 'nusrat@example.com', 'Nasirabad Housing, Chattogram', 'Chattogram', 'Chattogram', '4203', '01810000002'],
            ['Sadia Islam', 'sadia@example.com', 'Dhanmondi 27, Dhaka', 'Dhaka', 'Dhaka', '1209', '01910000003'],
            ['Tanvir Hasan', 'tanvir@example.com', 'Uposhohor, Sylhet', 'Sylhet', 'Sylhet', '3100', '01610000004'],
        ];

        $this->profile($admin, $admin, 'Admin User', 'Zeno Office, Banani', 'Dhaka', 'Dhaka', '1213', '01700000000');

        foreach ($customers as [$name, $email, $address, $division, $district, $postcode, $phone]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                $this->userData($name, $email, $customerRole->id, $admin->id)
            );
            $this->profile($user, $admin, $name, $address, $division, $district, $postcode, $phone);
        }
    }

    private function userData(string $name, string $email, int $roleId, ?int $entryUserId = null): array
    {
        return [
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role_id' => $roleId,
            'otp' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
            'otp_last_attempt' => null,
            'otp_verification_token' => Str::random(64),
            'last_otp_request_at' => null,
            'otp_blocked_until' => null,
            'otp_requests_today' => 0,
            'last_otp_request_date' => null,
            'status' => 'active',
            'entry_user_id' => $entryUserId,
        ];
    }

    private function profile(User $user, User $admin, string $name, string $address, string $divisionName, string $districtName, string $postcode, string $phone): void
    {
        $country = Country::where('code', 'BD')->first();
        $division = Division::where('name', $divisionName)->first();
        $district = District::where('name', $districtName)->first();

        CustomerProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'name' => $name,
                'address' => $address,
                'city' => $districtName,
                'state' => $divisionName,
                'postal_code' => $postcode,
                'phone' => $phone,
                'country_id' => $country?->id,
                'division_id' => $division?->id,
                'district_id' => $district?->id,
                'entry_user_id' => $admin->id,
            ]
        );
    }
}
