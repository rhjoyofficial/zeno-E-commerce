<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCart;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;


class CheckoutController extends Controller
{

    public function index(Request $request)
    {
        try {
            // Decode selected items safely
            $selectedItems = json_decode($request->input('selected_items', '[]'), true);

            // Validation - must be array of integers
            if (!is_array($selectedItems) || empty($selectedItems)) {
                return redirect()->back()->with('warning', 'No items selected for checkout.');
            }

            if (Auth::check()) {
                // Sanitize ids (force int, remove invalid)
                $selectedItems = array_map('intval', $selectedItems);

                // Retrieve only current user's cart items
                $cartItems = ProductCart::with(['product', 'variant'])
                    ->whereIn('id', $selectedItems)
                    ->where('user_id', Auth::id())
                    ->get();
            } else {
                $cart = Session::get('cart', []);
                $cartItems = collect();
                // dd($cart);
                foreach ($cart as $item) {
                    if (in_array($item['uniqueId'], $selectedItems)) {
                        $product = Product::find($item['product_id']);
                        $variant = $item['variant_id'] ? ProductVariant::find($item['variant_id']) : null;

                        $cartItems->push((object) [
                            'id' => $item['uniqueId'],
                            'product_id' => $item['product_id'],
                            'product' => $product,
                            'variant' => $variant,
                            'variant_id' => $item['variant_id'],
                            'qty' => $item['qty'],
                        ]);
                    }
                }
            }


            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with(['error', 'Your selected items are invalid or not available.']);
            }

            // --- Calculation Logic ---
            $subtotal = 0;
            $discountTotal = 0;
            $vatRate = 0.05;

            foreach ($cartItems as $cartItem) {
                $basePrice = $cartItem->variant ? $cartItem->variant->price : $cartItem->product->price;
                $discountedPrice = $cartItem->variant ? $cartItem->variant->discount_price : $cartItem->product->discount_price;
                $effectivePrice = $discountedPrice ?? $basePrice;

                $itemDiscount = ($basePrice - $effectivePrice) * $cartItem->qty;

                $subtotal += $effectivePrice * $cartItem->qty;
                $discountTotal += $itemDiscount;
            }

            $taxAmount = $subtotal * $vatRate;
            $grandTotal = $subtotal + $taxAmount;

            // 🟢 Store checkout data in session for later confirmation
            session([
                'checkout' => [
                    'items' => $cartItems->pluck('id')->toArray(),
                    'subtotal' => $subtotal,
                    'discount' => $discountTotal,
                    'vat' => $taxAmount,
                    'grand_total' => $grandTotal,
                ],
            ]);
            return view('customer.checkout', compact('cartItems', 'subtotal', 'discountTotal', 'taxAmount', 'grandTotal'));
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage(), [
                'userId' => Auth::id(),
                'selectedItems' => $request->input('selected_items'),
            ]);
            return redirect()->route('cart.index')->withErrors(['error' => 'Something went wrong, please try again.']);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone'     => 'required|string|max:30',
            'address'   => 'required|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'city'      => 'required|string|max:255',
            'postcode'  => 'nullable|string|max:50',
            'email'     => Auth::check() ? 'nullable|email|max:255' : 'required|email|max:255',
            'payment'   => 'required|string|in:cod,bkash,mobile-banking,card',
        ]);

        $customerIp = $request->ip();
        $checkout = session('checkout');

        if (empty($checkout) || empty($checkout['items']) || !is_array($checkout['items'])) {
            session()->forget('checkout');
            return redirect()->route('cart.index')->with('warning', 'No items selected for checkout.');
        }

        $userId = Auth::id() ?? null;
        $sessionId = session()->getId() ?? null;

        if (Auth::check()) {
            $selectedIds = array_map('intval', $checkout['items']);
            $cartItems = ProductCart::with(['product', 'variant.color', 'variant.size'])
                ->whereIn('id', $selectedIds)
                ->where('user_id', $userId)
                ->lockForUpdate()
                ->get();
        } else {
            $selectedIds = $checkout['items'];
            $cart = collect(Session::get('cart', []));
            $selectedCart = $cart->whereIn('uniqueId', $selectedIds);

            $productIds = $selectedCart->pluck('product_id')->unique();
            $variantIds = $selectedCart->pluck('variant_id')->filter()->unique();

            $products = Product::with('variants')->whereIn('id', $productIds)->get();
            $variants = ProductVariant::with(['color', 'size'])->whereIn('id', $variantIds)->get();

            $cartItems = $selectedCart->map(function ($item) use ($products, $variants) {
                $product = $products->firstWhere('id', $item['product_id']);
                $variant = $item['variant_id'] ? $variants->firstWhere('id', $item['variant_id']) : null;

                return (object) [
                    'id' => $item['uniqueId'],
                    'product_id' => $item['product_id'],
                    'product' => $product,
                    'variant' => $variant,
                    'variant_id' => $item['variant_id'],
                    'qty' => $item['qty'],
                ];
            });
        }

        if ($cartItems->count() !== count($selectedIds)) {
            Log::warning('Checkout: selected items mismatch', [
                'expected' => $selectedIds,
                'found' => $cartItems->pluck('id')->toArray(),
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]);
            session()->forget('checkout');
            return redirect()->route('cart.index')->with('error', 'Some selected items are no longer available. Please check your cart.');
        }

        $grandTotal = 0;
        $subtotal = 0;
        $discountTotal = 0;
        $taxTotal = 0;
        $orderItemsData = collect();
        $vatRate = config('app.vat_rate', 0.05);

        foreach ($cartItems as $item) {
            $calculation = $this->calculateItemPrices($item, $vatRate);
            $orderItemsData->push($calculation['data']);

            $availableQty = $item->variant ? ($item->variant->stock_quantity ?? 0) : ($item->product->stock_quantity ?? 0);
            if ($item->qty > $availableQty) {
                session()->forget('checkout');
                return redirect()->route('cart.index')->with(
                    'error',
                    "Insufficient stock for product: {$item->product->title}. Available: {$availableQty}, Requested: {$item->qty}"
                );
            }

            $totals = $calculation['totals'];
            $subtotal += $totals['subtotal'];
            $discountTotal += $totals['discount_total'];
            $taxTotal += $totals['tax_total'];
            $grandTotal += $totals['total'];
        }

        if (isset($checkout['grand_total']) && abs($checkout['grand_total'] - $grandTotal) > 0.01) {
            session()->put('checkout.grand_total', $grandTotal);
            return redirect()->back()->with(['warning' => 'Product prices have changed. Please review the updated totals.']);
        }

        DB::beginTransaction();

        try {
            $shippingData = [
                'user_id' => $userId,
                'guest_session_id' => $userId ? null : $sessionId,
                'name' => $validated['full_name'],
                'address' => $validated['address'] . ($validated['apartment'] ? ', ' . $validated['apartment'] : ''),
                'city' => $validated['city'],
                'postal_code' => $validated['postcode'] ?? null,
                'phone' => $validated['phone'],
                'country' => config('app.default_country', 'USA'),
                'state' => null,
                'is_default' => $userId ? false : null,
            ];
            $shippingAddress = ShippingAddress::create($shippingData);

            $orderNumber = 'ORD-' . Str::upper(Str::random(8));
            $lastInvoice = Order::max('invoice_number') ?? 72873; // Start at 72874
            $nextInvoice = $lastInvoice + 1;

            $order = Order::create([
                'user_id' => $userId,
                'guest_session_id' => $sessionId,
                'order_number' => $orderNumber,
                'invoice_number' => $nextInvoice, // Store numeric
                'customer_email' => Auth::check() ? Auth::user()->email : $validated['email'],
                'customer_phone' => $validated['phone'],
                'customer_ip' => $customerIp,
                'shipping_address_id' => $shippingAddress->id,
                'payment_method' => $validated['payment'],
                'subtotal' => $subtotal,
                'discount_amount' => $discountTotal,
                'tax_amount' => $taxTotal,
                'total' => $grandTotal,
                'status' => 'pending',
                'currency' => config('app.default_currency', 'USD'),
            ]);

            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            foreach ($cartItems as $item) {
                if ($item->variant) {
                    $item->variant->decrement('stock_quantity', $item->qty);
                } else {
                    $item->product->decrement('stock_quantity', $item->qty);
                }
            }

            if (Auth::check()) {
                ProductCart::whereIn('id', $selectedIds)->delete();
            } else {
                $cart = collect(Session::get('cart', []));
                $remainingCart = $cart->whereNotIn('uniqueId', $selectedIds);
                Session::put('cart', $remainingCart->values()->all());
            }

            DB::commit();
            session()->forget('checkout');

            if ($validated['payment'] === 'cod') {
                $order->update(['status' => 'confirmed']);
            }
            return redirect()->route('cart.index')->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order store failed: ' . $e->getMessage(), [
                'userId' => $userId,
                'sessionId' => $sessionId,
                'selected' => $selectedIds,
            ]);
            return redirect()->back()->withErrors(['order' => 'Failed to place order. Please try again later.']);
        }
    }

    protected function calculateItemPrices($item, $vatRate)
    {
        $basePrice = $item->variant ? $item->variant->price : $item->product->price;

        // Improved discount: Check variant first, then product
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
                'product_id' => $item->product_id,
                'product_variant_id' => $item->variant_id,
                'name' => $item->product->title,
                'sku' => $item->variant ? $item->variant->sku : $item->product->sku,
                'variant_color' => $item->variant && $item->variant->color ? $item->variant->color->name : null,
                'variant_size' => $item->variant && $item->variant->size ? $item->variant->size->name : null,
                'price' => $effectivePrice,
                'original_price' => $basePrice,
                'discount_amount' => $discountAmount * $item->qty,
                'tax_amount' => $taxAmountItem * $item->qty,
                'quantity' => $item->qty,
                'row_total' => $rowTotal,
                'row_total_incl_tax' => $rowTotalInclTax,
            ],
            'totals' => [
                'subtotal' => $rowTotal,
                'discount_total' => $discountAmount * $item->qty,
                'tax_total' => $taxAmountItem * $item->qty,
                'total' => $rowTotal + ($taxAmountItem * $item->qty),
            ]
        ];
    }

    public function success(Order $order)
    {
        return view('cart.index', compact('order'));
    }
    /**
     * Cart update helper
     */
    private function updateCartItem($itemId, $variantId = null, $qty = 1, $color = null, $size = null)
    {
        if (Auth::check()) {
            $cartItem = ProductCart::where('user_id', Auth::id())->find($itemId);
            if ($cartItem) {
                $cartItem->variant_id = $variantId;
                $cartItem->qty = $qty;
                $cartItem->color = $color;
                $cartItem->size = $size;

                // variant এর price set করি
                if ($variantId) {
                    $variant = ProductVariant::find($variantId);
                    if ($variant) {
                        $cartItem->price = $variant->price;
                    }
                } else {
                    $cartItem->price = $cartItem->product->sale_price ?: $cartItem->product->price;
                }

                $cartItem->save();
            }
        } else {
            $cart = Session::get('cart', []);
            if (isset($cart[$itemId])) {
                $cart[$itemId]['variant_id'] = $variantId;
                $cart[$itemId]['qty'] = $qty;
                $cart[$itemId]['color'] = $color;
                $cart[$itemId]['size'] = $size;

                if ($variantId) {
                    $variant = ProductVariant::find($variantId);
                    if ($variant) {
                        $cart[$itemId]['price'] = $variant->price;
                    }
                } else {
                    $product = Product::find($cart[$itemId]['product_id']);
                    $cart[$itemId]['price'] = $product->sale_price ?: $product->price;
                }

                Session::put('cart', $cart);
            }
        }
    }

    private function calculateCheckoutTotals($cartItems)
    {
        $orderValue = 0;
        $totalDiscount = 0;
        $vatRate = 0.05;

        foreach ($cartItems as $item) {
            $itemTotal = $item->price * $item->qty;
            $orderValue += $itemTotal;

            if ($item->product->discount && $item->product->discount_price) {
                $originalTotal = $item->product->price * $item->qty;
                $discountedTotal = $item->product->discount_price * $item->qty;
                $totalDiscount += ($originalTotal - $discountedTotal);
            }
        }

        $vatAmount = $orderValue * $vatRate;
        $totalPrice = $orderValue + $vatAmount;

        return [
            'order_value' => $orderValue,
            'total_discount' => $totalDiscount,
            'vat_amount' => $vatAmount,
            'total_price' => $totalPrice
        ];
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'payment_method' => 'required|in:cod,bkash,mobile-banking,card'
        ]);

        // Order process (এখন dummy)
        return response()->json([
            'success' => true,
            'message' => 'Order placed successfully',
            'order_id' => 'ORD123456'
        ]);
    }
}
