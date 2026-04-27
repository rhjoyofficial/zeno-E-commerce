<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        $customerRole = Role::firstOrCreate(
            ['slug' => 'customer'],
            ['name' => 'Customer']
        );

        $adminRoleId = Role::where('slug', 'admin')->value('id');
        $adminId = $adminRoleId
            ? User::where('role_id', $adminRoleId)->inRandomOrder()->value('id')
            : null;

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'otp' => null,
            'otp_attempts' => 0,
            'otp_last_attempt' => null,
            'otp_blocked_until' => null,
            'otp_requests_today' => 0,
            'last_otp_request_date' => null,
            'last_otp_request_at' => null,
            'otp_expires_at' => null,
            'otp_verification_token' => Str::random(64),
            'status' => 'active',
            'role_id' => $customerRole->id,
            'entry_user_id' => $adminId,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
