<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductCart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ShippingAddress;
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
     * Process the order.
     */
    public function processOrder(array $validatedData, array $selectedIds, string $customerIp, ?string $sessionId): array
    {
        $cartItems = $this->getCheckoutItems($selectedIds);

        if ($cartItems->count() !== count($selectedIds)) {
            throw new Exception('Some selected items are no longer available. Please check your cart.');
        }

        $vatRate = config('app.vat_rate', 0.05);
        $totals = $this->calculateOrderTotals($cartItems, $vatRate);
        $orderItemsData = collect();

        // Stock check and items formatting
        foreach ($cartItems as $item) {
            $calculation = $this->calculateItemPrices($item, $vatRate);
            $orderItemsData->push($calculation['data']);

            $availableQty = $item->variant ? ($item->variant->stock_quantity ?? 0) : ($item->product->stock_quantity ?? 0);
            if ($item->qty > $availableQty) {
                throw new Exception("Insufficient stock for product: {$item->product->title}. Available: {$availableQty}, Requested: {$item->qty}");
            }
        }

        $checkoutSession = session('checkout');
        if (isset($checkoutSession['grand_total']) && abs($checkoutSession['grand_total'] - $totals['grandTotal']) > 0.01) {
            session()->put('checkout.grand_total', $totals['grandTotal']);
            throw new Exception('PRICE_CHANGED');
        }

        $userId = Auth::id();

        DB::beginTransaction();
        try {
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

            // Order Creation
            $lastInvoice = Order::max('invoice_number') ?? 0;
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

            // Create Order Items
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

            // Clear Cart
            $this->clearProcessedCartItems($selectedIds);

            DB::commit();

            if ($validatedData['payment'] === 'cod') {
                $order->update(['status' => 'confirmed']);
            }

            return ['success' => true, 'order' => $order];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order store failed: ' . $e->getMessage());
            throw new Exception('Failed to place order. Please try again later.');
        }
    }

    protected function calculateOrderTotals($cartItems, float $vatRate): array
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
