<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\ProductCart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingAddress;
use App\Models\TaxRate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class CheckoutService
{
    /**
     * Prepare cart items for checkout calculation and validation.
     */
    public function getCheckoutItems(array $selectedIds): \Illuminate\Support\Collection
    {
        if (empty($selectedIds)) {
            return collect();
        }

        $userId = Auth::id();

        if ($userId) {
            return ProductCart::with(['product', 'variant.color', 'variant.size'])
                ->whereIn('id', $selectedIds)
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->get();
        }

        $cart = collect(Session::get('cart', []));
        $selectedCart = $cart->whereIn('uniqueId', $selectedIds);

        $productIds = $selectedCart->pluck('product_id')->unique();
        $variantIds = $selectedCart->pluck('variant_id')->filter()->unique();

        $products = Product::with('variants')->whereIn('id', $productIds)->get();
        $variants = ProductVariant::with(['color', 'size'])->whereIn('id', $variantIds)->get();

        return $selectedCart->map(function ($item) use ($products, $variants) {
            $product = $products->firstWhere('id', $item['product_id']);
            $variant = $item['variant_id'] ? $variants->firstWhere('id', $item['variant_id']) : null;

            return (object) [
                'id'         => $item['uniqueId'],
                'product_id' => $item['product_id'],
                'product'    => $product,
                'variant'    => $variant,
                'variant_id' => $item['variant_id'],
                'qty'        => $item['qty'],
            ];
        });
    }

    /**
     * Resolve the active VAT rate from the tax_rates table, with a config fallback.
     * Rate is stored as a percentage (e.g. 5.00 = 5%), returned as a decimal (0.05).
     */
    public function getVatRate(): float
    {
        $taxRate = TaxRate::active()->first();

        return $taxRate ? (float) ($taxRate->rate / 100) : (float) config('app.vat_rate', 0.05);
    }

    /**
     * Validate a coupon code and return the discount amount against the given subtotal.
     * Throws an Exception with a user-facing message if the coupon is invalid.
     */
    public function applyCoupon(string $code, float $subtotal): float
    {
        $coupon = Coupon::active()->where('code', strtoupper(trim($code)))->first();

        if (!$coupon) {
            throw new Exception('Invalid or expired coupon code.');
        }

        if ($coupon->min_order_amount && $subtotal < (float) $coupon->min_order_amount) {
            throw new Exception(
                "This coupon requires a minimum order of {$coupon->min_order_amount}."
            );
        }

        if ($coupon->type === 'percentage') {
            return round($subtotal * ((float) $coupon->value / 100), 2);
        }

        // Fixed discount — cannot exceed the subtotal
        return min((float) $coupon->value, $subtotal);
    }

    /**
     * Process the order.
     */
    public function processOrder(array $validatedData, array $selectedIds, string $customerIp, ?string $sessionId): array
    {
        // Validate coupon before opening the transaction
        $coupon = null;
        $couponDiscount = 0.0;
        if (!empty($validatedData['coupon_code'])) {
            // applyCoupon throws on invalid codes — do this before we touch the DB
            $vatRate     = $this->getVatRate();
            $preItems    = $this->getCheckoutItems($selectedIds);
            $preTotals   = $this->calculateOrderTotals($preItems, $vatRate);
            $couponDiscount = $this->applyCoupon($validatedData['coupon_code'], $preTotals['subtotal']);
            $coupon = Coupon::active()->where('code', strtoupper(trim($validatedData['coupon_code'])))->first();
        }

        $userId = Auth::id();

        DB::beginTransaction();
        try {
            // Re-fetch cart items inside transaction so lockForUpdate() actually locks
            $cartItems = $this->getCheckoutItems($selectedIds);

            if ($cartItems->count() !== count($selectedIds)) {
                throw new Exception('Some selected items are no longer available. Please check your cart.');
            }

            $vatRate = $this->getVatRate();
            $totals  = $this->calculateOrderTotals($cartItems, $vatRate);

            if ($coupon) {
                $totals['discountTotal'] += $couponDiscount;
                $totals['grandTotal']    -= $couponDiscount;
                $totals['grandTotal']     = max(0, $totals['grandTotal']);
            }

            // Price change guard
            $checkoutSession = session('checkout');
            if (isset($checkoutSession['grand_total']) && abs($checkoutSession['grand_total'] - $totals['grandTotal']) > 0.01) {
                session()->put('checkout.grand_total', $totals['grandTotal']);
                throw new Exception('PRICE_CHANGED');
            }

            // Build order items and validate stock while rows are locked
            $orderItemsData = collect();
            foreach ($cartItems as $item) {
                $calculation = $this->calculateItemPrices($item, $vatRate);
                $orderItemsData->push($calculation['data']);

                $availableQty = $item->variant
                    ? ($item->variant->stock_quantity ?? 0)
                    : ($item->product->stock_quantity ?? 0);

                if ($item->qty > $availableQty) {
                    throw new Exception("Insufficient stock for \"{$item->product->title}\". Available: {$availableQty}, Requested: {$item->qty}");
                }
            }

            // Shipping Address
            $shippingAddress = ShippingAddress::create([
                'user_id'          => $userId,
                'guest_session_id' => $userId ? null : $sessionId,
                'name'             => $validatedData['full_name'],
                'address'          => $validatedData['address'] . (!empty($validatedData['apartment']) ? ', ' . $validatedData['apartment'] : ''),
                'city'             => $validatedData['city'],
                'postal_code'      => $validatedData['postcode'] ?? null,
                'phone'            => $validatedData['phone'],
                'country'          => config('app.default_country', 'USA'),
                'state'            => null,
            ]);

            // Locked max prevents duplicate invoice numbers under concurrency
            $lastInvoice = Order::lockForUpdate()->max('invoice_number') ?? 0;
            $order = Order::create([
                'user_id'             => $userId,
                'guest_session_id'    => $sessionId,
                'order_number'        => 'ORD-' . Str::upper(Str::random(8)),
                'invoice_number'      => $lastInvoice + 1,
                'customer_email'      => $userId ? Auth::user()->email : ($validatedData['email'] ?? null),
                'customer_phone'      => $validatedData['phone'],
                'customer_ip'         => $customerIp,
                'shipping_address_id' => $shippingAddress->id,
                'payment_method'      => $validatedData['payment'],
                'subtotal'            => $totals['subtotal'],
                'discount_amount'     => $totals['discountTotal'],
                'tax_amount'          => $totals['taxTotal'],
                'total'               => $totals['grandTotal'],
                'status'              => 'pending',
                'currency'            => config('app.default_currency', 'USD'),
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            // Deduct Stock
            foreach ($cartItems as $item) {
                if ($item->variant) {
                    $item->variant->decrement('stock_quantity', $item->qty);
                } else {
                    $item->product->decrement('stock_quantity', $item->qty);
                }
            }

            $this->clearProcessedCartItems($selectedIds);

            if ($validatedData['payment'] === 'cod') {
                $order->update(['status' => 'confirmed']);
            }

            DB::commit();

            // Increment coupon usage outside transaction (non-critical)
            if ($coupon) {
                $coupon->increment('used_count');
            }

            return ['success' => true, 'order' => $order];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order store failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function calculateOrderTotals($cartItems, float $vatRate): array
    {
        $subtotal = 0;
        $discountTotal = 0;
        $taxTotal = 0;
        $grandTotal = 0;

        foreach ($cartItems as $item) {
            $calc = $this->calculateItemPrices($item, $vatRate)['totals'];
            $subtotal += $calc['subtotal'];
            $discountTotal += $calc['discount_total'];
            $taxTotal += $calc['tax_total'];
            $grandTotal += $calc['total'];
        }

        return compact('subtotal', 'discountTotal', 'taxTotal', 'grandTotal');
    }

    protected function calculateItemPrices($item, float $vatRate): array
    {
        $basePrice = $item->variant ? $item->variant->price : $item->product->price;

        $effectivePrice = $basePrice;
        if ($item->variant && $item->variant->discount_price) {
            $effectivePrice = $item->variant->discount_price;
        } elseif (!$item->product->has_variants && $item->product->discount_price) {
            $effectivePrice = $item->product->discount_price;
        }

        $discountAmount = $basePrice - $effectivePrice;
        $taxAmountItem = $effectivePrice * $vatRate;
        $rowTotal = $effectivePrice * $item->qty;
        $rowTotalInclTax = $rowTotal + ($taxAmountItem * $item->qty);

        return [
            'data' => [
                'product_id'         => $item->product_id,
                'product_variant_id' => $item->variant_id,
                'name'               => $item->product->title,
                'sku'                => $item->variant ? $item->variant->sku : $item->product->sku,
                'variant_color'      => $item->variant && $item->variant->color ? $item->variant->color->name : null,
                'variant_size'       => $item->variant && $item->variant->size ? $item->variant->size->name : null,
                'price'              => $effectivePrice,
                'original_price'     => $basePrice,
                'discount_amount'    => $discountAmount * $item->qty,
                'tax_amount'         => $taxAmountItem * $item->qty,
                'quantity'           => $item->qty,
                'row_total'          => $rowTotal,
                'row_total_incl_tax' => $rowTotalInclTax,
            ],
            'totals' => [
                'subtotal'       => $rowTotal,
                'discount_total' => $discountAmount * $item->qty,
                'tax_total'      => $taxAmountItem * $item->qty,
                'total'          => $rowTotalInclTax,
            ]
        ];
    }

    protected function clearProcessedCartItems(array $selectedIds): void
    {
        if (Auth::check()) {
            ProductCart::whereIn('id', $selectedIds)->delete();
        } else {
            $cart = collect(Session::get('cart', []));
            $remainingCart = $cart->whereNotIn('uniqueId', $selectedIds);
            Session::put('cart', $remainingCart->values()->all());
        }
    }
}
