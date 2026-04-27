<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\Product;
use App\Models\ShippingAddress;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::whereHas('role', fn ($query) => $query->where('slug', 'customer'))->get();
        $coupon = Coupon::where('code', 'WELCOME10')->first();
        $invoice = 1000;

        foreach ($customers->take(3) as $index => $user) {
            $address = ShippingAddress::where('user_id', $user->id)->first();
            if (!$address) {
                continue;
            }

            $items = Product::with(['variants.color', 'variants.size'])
                ->where('status', 'active')
                ->skip($index * 3)
                ->take(3)
                ->get();

            $subtotal = 0;
            $itemRows = [];

            foreach ($items as $product) {
                $variant = $product->variants->first();
                $price = (float) ($variant?->final_price ?? $product->final_price);
                $originalPrice = (float) ($variant?->price ?? $product->price);
                $quantity = 1 + ($index % 2);
                $rowTotal = $price * $quantity;
                $taxAmount = round($rowTotal * 0.05, 2);
                $subtotal += $rowTotal;

                $itemRows[] = [
                    'product_id' => $product->id,
                    'product_variant_id' => $variant?->id,
                    'name' => $product->title,
                    'sku' => $variant?->sku ?? $product->sku,
                    'description' => $product->short_description,
                    'variant_size' => $variant?->size?->name,
                    'variant_color' => $variant?->color?->name,
                    'price' => $price,
                    'original_price' => $originalPrice,
                    'discount_amount' => max(0, $originalPrice - $price) * $quantity,
                    'tax_amount' => $taxAmount,
                    'quantity' => $quantity,
                    'quantity_shipped' => $index === 2 ? $quantity : 0,
                    'quantity_refunded' => 0,
                    'quantity_cancelled' => 0,
                    'row_total' => $rowTotal,
                    'row_total_incl_tax' => $rowTotal + $taxAmount,
                    'weight' => 0.50,
                    'volume' => null,
                    'fulfillment_status' => $index === 2 ? 'fulfilled' : 'unfulfilled',
                    'notes' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $discount = $coupon ? round($subtotal * 0.10, 2) : 0;
            $tax = array_sum(array_column($itemRows, 'tax_amount'));
            $shipping = $subtotal >= 5000 ? 0 : 120;
            $total = $subtotal - $discount + $tax + $shipping;
            $status = ['pending', 'processing', 'delivered'][$index];
            $invoice++;

            $orderId = DB::table('orders')->insertGetId([
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'invoice_number' => $invoice,
                'status' => $status,
                'subtotal' => $subtotal,
                'discount_amount' => $discount,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'total' => $total,
                'coupon_id' => $coupon?->id,
                'coupon_code' => $coupon?->code,
                'total_paid' => $status === 'pending' ? 0 : $total,
                'total_refunded' => 0,
                'currency' => 'BDT',
                'payment_status' => $status === 'pending' ? 'pending' : 'paid',
                'payment_method' => $index === 0 ? 'cod' : 'sslcommerz',
                'transaction_id' => $index === 0 ? null : 'TXN-' . strtoupper(Str::random(10)),
                'payment_notes' => null,
                'shipping_address_id' => $address->id,
                'shipping_method' => 'standard',
                'shipping_weight' => count($itemRows) * 0.50,
                'tracking_number' => $status === 'delivered' ? 'ZENO-' . $invoice : null,
                'tracking_url' => null,
                'user_id' => $user->id,
                'guest_session_id' => null,
                'customer_email' => $user->email,
                'customer_phone' => $address->phone,
                'customer_ip' => '127.0.0.1',
                'confirmed_at' => now(),
                'paid_at' => $status === 'pending' ? null : now(),
                'processing_at' => in_array($status, ['processing', 'delivered'], true) ? now() : null,
                'shipped_at' => $status === 'delivered' ? now() : null,
                'delivered_at' => $status === 'delivered' ? now() : null,
                'cancelled_at' => null,
                'notes' => 'Demo order generated from fashion catalog seed data.',
                'admin_notes' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($itemRows as $row) {
                DB::table('order_items')->insert($row + ['order_id' => $orderId]);
            }
        }

        DB::table('sequences')->updateOrInsert(
            ['name' => 'invoice_number'],
            ['value' => $invoice]
        );
    }
}
