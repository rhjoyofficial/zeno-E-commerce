<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition()
    {
        // ── Pick a random product that actually exists ─────────────────
        $product = Product::inRandomOrder()->first();

        if (!$product) {
            throw new \RuntimeException('No products found. Run ProductSeeder before OrderSeeder.');
        }

        // ── Try to find an existing variant; use null if none exist ────
        $variant = ProductVariant::where('product_id', $product->id)
            ->inRandomOrder()
            ->first(); // null if no variants — that's fine for simple products

        // ── Resolve admin user without factory fallback ────────────────
        $adminRoleId = Role::where('slug', 'admin')->value('id');
        $admin = User::where('role_id', $adminRoleId)->inRandomOrder()->first();

        if (!$admin) {
            throw new \RuntimeException('No admin user found. Run UserSeeder before OrderSeeder.');
        }

        // ── Price calculation ──────────────────────────────────────────
        $vatRate       = config('app.vat_rate', 0.05);
        $basePrice     = $variant?->price ?? $product->price;
        $effectivePrice = $basePrice;

        // Apply discount if available
        if ($product->discount && $product->discount_price && !$variant) {
            $effectivePrice = $product->discount_price;
        }

        $discountAmount    = max(0, $basePrice - $effectivePrice);
        $quantity          = $this->faker->numberBetween(1, 5);
        $taxAmountItem     = $effectivePrice * $vatRate;
        $rowTotal          = $effectivePrice * $quantity;
        $rowTotalInclTax   = $rowTotal + ($taxAmountItem * $quantity);

        // ── SKU ────────────────────────────────────────────────────────
        $sku = $variant?->sku ?? $product->sku ?? ('ITEM-' . $this->faker->bothify('####??'));

        return [
            'order_id'           => Order::factory(),
            'product_id'         => $product->id,
            'product_variant_id' => $variant?->id,
            'name'               => $product->title,
            'sku'                => $sku,
            'description'        => $product->short_description,
            'variant_color'      => $variant?->color?->name,
            'variant_size'       => $variant?->size?->name,
            'price'              => $effectivePrice,
            'original_price'     => $basePrice,
            'discount_amount'    => $discountAmount * $quantity,
            'tax_amount'         => $taxAmountItem * $quantity,
            'quantity'           => $quantity,
            'quantity_shipped'   => $this->faker->numberBetween(0, $quantity),
            'quantity_refunded'  => $this->faker->numberBetween(0, $quantity),
            'quantity_cancelled' => $this->faker->numberBetween(0, $quantity),
            'row_total'          => $rowTotal,
            'row_total_incl_tax' => $rowTotalInclTax,
            'weight'             => $this->faker->randomFloat(2, 0, 5),
            'volume'             => $this->faker->randomFloat(2, 0, 5),
            'fulfillment_status' => $this->faker->randomElement(['unfulfilled', 'partially_fulfilled', 'fulfilled']),
            'notes'              => $this->faker->optional()->sentence,
            'created_by'         => $admin->id,
            'updated_by'         => $admin->id,
        ];
    }
}