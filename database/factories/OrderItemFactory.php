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
        $product = Product::inRandomOrder()->first() ?? Product::factory()->create();
        $variant = ProductVariant::where('product_id', $product->id)->inRandomOrder()->first() ?? ProductVariant::factory()->create(['product_id' => $product->id]);
        $admin = User::where('role_id', Role::where('slug', 'admin')->first()->id)->inRandomOrder()->first() ?? User::factory()->create([
            'role_id' => Role::where('slug', 'admin')->first()->id ?? Role::factory()->create(['slug' => 'admin'])->id
        ]);

        $quantity = $this->faker->numberBetween(1, min(5, $variant->stock_quantity ?? $product->stock_quantity ?? 10));
        $vatRate = config('app.vat_rate', 0.05);
        
        // Mimic calculateItemPrices logic
        $basePrice = $variant->price ?? $product->price;
        $effectivePrice = $basePrice;
        if ($variant->discount_price) {
            $effectivePrice = $variant->discount_price;
        } elseif (!$product->has_variants && $product->discount_price) {
            $effectivePrice = $product->discount_price;
        }
        $discountAmount = $basePrice - $effectivePrice;
        $taxAmountItem = $effectivePrice * $vatRate;
        $rowTotal = $effectivePrice * $quantity;
        $rowTotalInclTax = $rowTotal + ($taxAmountItem * $quantity);

        return [
            'order_id' => Order::factory(),
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'name' => $product->title,
            'sku' => $variant->sku ?? $product->sku,
            'description' => $product->short_description,
            'variant_color' => $variant->color ? $variant->color->name : null,
            'variant_size' => $variant->size ? $variant->size->name : null,
            'price' => $effectivePrice,
            'original_price' => $basePrice,
            'discount_amount' => $discountAmount * $quantity,
            'tax_amount' => $taxAmountItem * $quantity,
            'quantity' => $quantity,
            'quantity_shipped' => $this->faker->numberBetween(0, $quantity),
            'quantity_refunded' => $this->faker->numberBetween(0, $quantity),
            'quantity_cancelled' => $this->faker->numberBetween(0, $quantity),
            'row_total' => $rowTotal,
            'row_total_incl_tax' => $rowTotalInclTax,
            'weight' => $this->faker->randomFloat(2, 0, 5),
            'volume' => $this->faker->randomFloat(2, 0, 5),
            'fulfillment_status' => $this->faker->randomElement(['unfulfilled', 'partially_fulfilled', 'fulfilled']),
            'notes' => $this->faker->optional()->sentence,
            'created_by' => $admin->id,
            'updated_by' => $admin->id,
        ];
    }
}