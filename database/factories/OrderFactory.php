<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition()
    {
        // ── Resolve roles without factory fallback ─────────────────────
        $customerRole = Role::where('slug', 'customer')->first();
        $adminRole    = Role::where('slug', 'admin')->first();

        if (!$customerRole || !$adminRole) {
            throw new \RuntimeException('Roles not found. Run RoleSeeder before OrderSeeder.');
        }

        $user  = User::where('role_id', $customerRole->id)->inRandomOrder()->first()
               ?? User::where('role_id', $adminRole->id)->inRandomOrder()->first();
        $admin = User::where('role_id', $adminRole->id)->inRandomOrder()->first();

        if (!$admin) {
            throw new \RuntimeException('No admin user found. Run UserSeeder before OrderSeeder.');
        }

        // ── Randomly decide if guest order ─────────────────────────────
        $isGuest       = $user ? $this->faker->boolean(30) : true; // 30% guest if user exists
        $orderUser     = $isGuest ? null : $user;

        $status         = $this->faker->randomElement(['pending', 'confirmed', 'processing', 'shipped', 'delivered']);
        $paymentStatus  = $this->faker->randomElement(['pending', 'paid', 'partially_paid']);
        $paymentMethod  = $this->faker->randomElement(['cod', 'bkash', 'nagad', 'card']);

        // Totals will be recalculated by the seeder after items are created
        $subtotal       = 0;
        $discountAmount = 0;
        $taxAmount      = 0;
        $shippingAmount = $this->faker->randomElement([60, 80, 100, 120]); // BDT shipping

        // Reuse an existing shipping address or create a new one via factory
        $shippingAddress = ShippingAddress::inRandomOrder()->first()
            ?? ShippingAddress::factory()->create([
                'user_id'    => $orderUser?->id,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ]);

        return [
            'order_number'       => 'ORD-' . strtoupper($this->faker->unique()->bothify('########')),
            'invoice_number'     => null, // Set sequentially by OrderSeeder
            'status'             => $status,
            'subtotal'           => $subtotal,
            'discount_amount'    => $discountAmount,
            'tax_amount'         => $taxAmount,
            'shipping_amount'    => $shippingAmount,
            'total'              => $subtotal - $discountAmount + $taxAmount + $shippingAmount,
            'total_paid'         => 0, // Recalculated by seeder
            'total_refunded'     => 0,
            'currency'           => 'BDT',
            'payment_status'     => $paymentStatus,
            'payment_method'     => $paymentMethod,
            'transaction_id'     => $paymentMethod !== 'cod' ? $this->faker->uuid : null,
            'payment_notes'      => $this->faker->optional()->sentence,
            'shipping_address_id'=> $shippingAddress->id,
            'shipping_method'    => $this->faker->randomElement(['standard', 'express']),
            'shipping_weight'    => $this->faker->randomFloat(2, 0, 10),
            'tracking_number'    => $this->faker->optional()->bothify('TRK-#####'),
            'tracking_url'       => $this->faker->optional()->url,
            'user_id'            => $orderUser?->id,
            'guest_session_id'   => $isGuest ? $this->faker->uuid : null,
            'customer_email'     => $orderUser?->email ?? $this->faker->safeEmail,
            'customer_phone'     => '017' . $this->faker->numerify('########'),
            'customer_ip'        => $this->faker->ipv4,
            'confirmed_at'       => $status !== 'pending' ? $this->faker->dateTimeThisYear : null,
            'paid_at'            => $paymentStatus !== 'pending' ? $this->faker->dateTimeThisYear : null,
            'processing_at'      => in_array($status, ['processing', 'shipped', 'delivered']) ? $this->faker->dateTimeThisYear : null,
            'shipped_at'         => in_array($status, ['shipped', 'delivered']) ? $this->faker->dateTimeThisYear : null,
            'delivered_at'       => $status === 'delivered' ? $this->faker->dateTimeThisYear : null,
            'cancelled_at'       => $status === 'cancelled' ? $this->faker->dateTimeThisYear : null,
            'notes'              => $this->faker->optional()->sentence,
            'admin_notes'        => $this->faker->optional()->sentence,
            'created_by'         => $admin->id,
            'updated_by'         => $admin->id,
        ];
    }
}