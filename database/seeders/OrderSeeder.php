<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $admin    = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->firstOrFail();
        $customer = User::where('role_id', Role::where('slug', 'customer')->first()?->id)->firstOrFail();

        DB::transaction(function () use ($admin, $customer) {
            // Determine next invoice number base
            $nextInvoice = (Order::max('invoice_number') ?? 100000) + 1;

            for ($i = 0; $i < 10; $i++) {
                // Alternate orders between admin and customer users
                $orderUser = $i % 2 === 0 ? $customer : $admin;

                $order = Order::factory()->create([
                    'user_id'        => $orderUser->id,
                    'customer_email' => $orderUser->email,
                    'invoice_number' => $nextInvoice + $i,
                    'created_by'     => $admin->id,
                    'updated_by'     => $admin->id,
                ]);

                // Create 1–3 order items for each order
                $items = OrderItem::factory()->count(rand(1, 3))->create([
                    'order_id'   => $order->id,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);

                // Recalculate order totals from actual items
                $subtotal        = $items->sum('row_total');
                $discountAmount  = $items->sum('discount_amount');
                $taxAmount       = $items->sum('tax_amount');
                $shippingAmount  = $order->shipping_amount; // set by factory

                $total = $subtotal - $discountAmount + $taxAmount + $shippingAmount;

                $order->update([
                    'subtotal'        => $subtotal,
                    'discount_amount' => $discountAmount,
                    'tax_amount'      => $taxAmount,
                    'total'           => max(0, $total),
                    'total_paid'      => match ($order->payment_status) {
                        'paid'           => max(0, $total),
                        'partially_paid' => max(0, $total * 0.5),
                        default          => 0,
                    },
                ]);

                // Decrement stock for each item
                foreach ($items as $item) {
                    if ($item->product_variant_id) {
                        ProductVariant::where('id', $item->product_variant_id)
                            ->where('stock_quantity', '>=', $item->quantity)
                            ->decrement('stock_quantity', $item->quantity);
                    } elseif ($item->product_id) {
                        Product::where('id', $item->product_id)
                            ->where('stock_quantity', '>=', $item->quantity)
                            ->decrement('stock_quantity', $item->quantity);
                    }
                }
            }
        });
    }
}
