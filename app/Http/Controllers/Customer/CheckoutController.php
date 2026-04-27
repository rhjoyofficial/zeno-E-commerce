<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use App\Services\CheckoutService;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;

    public function __construct(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function index(Request $request)
    {
        try {
            $selectedItems = json_decode($request->input('selected_items', '[]'), true);

            if (!is_array($selectedItems) || empty($selectedItems)) {
                return redirect()->back()->with('warning', 'No items selected for checkout.');
            }

            $selectedItems = array_map('intval', $selectedItems);
            $cartItems = $this->checkoutService->getCheckoutItems($selectedItems);

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Your selected items are invalid or not available.');
            }

            $vatRate = config('app.vat_rate', 0.05);
            $subtotal = 0;
            $discountTotal = 0;
            $taxAmount = 0;
            $grandTotal = 0;

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

            session([
                'checkout' => [
                    'items'       => $cartItems->pluck('id')->toArray(),
                    'subtotal'    => $subtotal,
                    'discount'    => $discountTotal,
                    'vat'         => $taxAmount,
                    'grand_total' => $grandTotal,
                ],
            ]);

            return view('customer.checkout', compact('cartItems', 'subtotal', 'discountTotal', 'taxAmount', 'grandTotal'));
        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage());
            return redirect()->route('cart.index')->withErrors(['error' => 'Something went wrong, please try again.']);
        }
    }

    public function store(CheckoutRequest $request)
    {
        $checkoutSession = session('checkout');

        if (empty($checkoutSession) || empty($checkoutSession['items']) || !is_array($checkoutSession['items'])) {
            session()->forget('checkout');
            return redirect()->route('cart.index')->with('warning', 'No items selected for checkout.');
        }

        try {
            $result = $this->checkoutService->processOrder(
                $request->validated(),
                $checkoutSession['items'],
                $request->ip(),
                session()->getId()
            );

            session()->forget('checkout');

            return redirect()->route('cart.index')->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            if ($e->getMessage() === 'PRICE_CHANGED') {
                return redirect()->back()->with('warning', 'Product prices have changed. Please review the updated totals.');
            }
            
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    public function placeOrder(CheckoutRequest $request)
    {
        return response()->json([
            'success'  => true,
            'message'  => 'Order placed successfully',
            'order_id' => 'ORD123456'
        ]);
    }
}
