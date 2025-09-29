<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', Role::where('slug', 'admin')->first()->id)->first() ?? User::factory()->create([
            'role_id' => Role::where('slug', 'admin')->first()->id ?? Role::factory()->create(['slug' => 'admin'])->id
        ]);

        DB::transaction(function () use ($admin) {
            // Create 10 orders
            for ($i = 0; $i < 10; $i++) {
                $order = Order::factory()->create([
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);

                // Generate sequential invoice_number
                $lastInvoice = Order::max('invoice_number') ?? 00000; // Start at 00000
                $order->update(['invoice_number' => $lastInvoice + 1]);

                // Create 1-3 order items
                $items = OrderItem::factory()->count(rand(1, 3))->create([
                    'order_id' => $order->id,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);

                // Calculate totals (mimics controller logic)
                $subtotal = $items->sum('row_total');
                $discount_amount = $items->sum('discount_amount');
                $tax_amount = $items->sum('tax_amount');
                $shipping_amount = $order->shipping_amount; // From factory

                $order->update([
                    'subtotal' => $subtotal,
                    'discount_amount' => $discount_amount,
                    'tax_amount' => $tax_amount,
                    'total' => $subtotal - $discount_amount + $tax_amount + $shipping_amount,
                    'total_paid' => $order->payment_status === 'paid' ? ($subtotal - $discount_amount + $tax_amount + $shipping_amount) : ($order->payment_status === 'partially_paid' ? $subtotal * 0.5 : 0),
                ]);

                // Update stock quantities (mimics controller)
                foreach ($items as $item) {
                    if ($item->product_variant_id) {
                        ProductVariant::where('id', $item->product_variant_id)->decrement('stock_quantity', $item->quantity);
                    } else {
                        Product::where('id', $item->product_id)->decrement('stock_quantity', $item->quantity);
                    }
                }
            }
        });
    }
}
