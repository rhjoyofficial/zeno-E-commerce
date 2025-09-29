<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Role;
use App\Models\ShippingAddress;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition()
    {
        $customerRole = Role::where('slug', 'customer')->first() ?? Role::factory()->create(['slug' => 'customer']);
        $adminRole = Role::where('slug', 'admin')->first() ?? Role::factory()->create(['slug' => 'admin']);
        
        // Randomly decide if this is a guest or user order (50% chance each)
        $isGuest = $this->faker->boolean(50);
        $user = $isGuest ? null : User::where('role_id', $customerRole->id)->inRandomOrder()->first() ?? User::factory()->create([
            'role_id' => $customerRole->id
        ]);
        $admin = User::where('role_id', $adminRole->id)->inRandomOrder()->first() ?? User::factory()->create([
            'role_id' => $adminRole->id
        ]);

        $status = $this->faker->randomElement(['pending', 'confirmed', 'processing', 'shipped', 'delivered']);
        $payment_status = $this->faker->randomElement(['pending', 'paid', 'partially_paid']);
        $payment_method = $this->faker->randomElement(['cod', 'bkash', 'mobile-banking', 'card']);
        
        // Will be updated by seeder based on OrderItems
        $subtotal = 0;
        $discount_amount = 0;
        $tax_amount = 0;
        $shipping_amount = $this->faker->randomFloat(2, 0, 50);
        
        return [
            'order_number' => 'ORD-' . $this->faker->unique()->bothify('########'), // Matches your controller
            'invoice_number' => null, // Set by seeder for sequential increment
            'status' => $status,
            'subtotal' => $subtotal,
            'discount_amount' => $discount_amount,
            'tax_amount' => $tax_amount,
            'shipping_amount' => $shipping_amount,
            'total' => $subtotal - $discount_amount + $tax_amount + $shipping_amount,
            'total_paid' => $payment_status === 'paid' ? ($subtotal - $discount_amount + $tax_amount + $shipping_amount) : ($payment_status === 'partially_paid' ? $this->faker->randomFloat(2, ($subtotal * 0.5), $subtotal) : 0),
            'total_refunded' => 0,
            'currency' => config('app.default_currency', 'USD'),
            'payment_status' => $payment_status,
            'payment_method' => $payment_method,
            'transaction_id' => $payment_method !== 'cod' ? $this->faker->uuid : null,
            'payment_notes' => $this->faker->optional()->sentence,
            'shipping_address_id' => ShippingAddress::factory()->create([
                'user_id' => $user?->id,
                'guest_session_id' => $isGuest ? $this->faker->uuid : null,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ])->id,
            'shipping_method' => $this->faker->randomElement(['standard', 'express']),
            'shipping_weight' => $this->faker->randomFloat(2, 0, 10),
            'tracking_number' => $this->faker->optional()->bothify('TRK-#####'),
            'tracking_url' => $this->faker->optional()->url,
            'user_id' => $user?->id,
            'guest_session_id' => $isGuest ? $this->faker->uuid : null,
            'customer_email' => $user?->email ?? $this->faker->safeEmail,
            'customer_phone' => $this->faker->phoneNumber,
            'customer_ip' => $this->faker->ipv4,
            'confirmed_at' => $status !== 'pending' ? $this->faker->dateTimeThisYear : null,
            'paid_at' => $payment_status !== 'pending' ? $this->faker->dateTimeThisYear : null,
            'processing_at' => in_array($status, ['processing', 'shipped', 'delivered']) ? $this->faker->dateTimeThisYear : null,
            'shipped_at' => in_array($status, ['shipped', 'delivered']) ? $this->faker->dateTimeThisYear : null,
            'delivered_at' => $status === 'delivered' ? $this->faker->dateTimeThisYear : null,
            'cancelled_at' => $status === 'cancelled' ? $this->faker->dateTimeThisYear : null,
            'notes' => $this->faker->optional()->sentence,
            'admin_notes' => $this->faker->optional()->sentence,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ];
    }
}