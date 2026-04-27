<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use App\Services\CheckoutService;
use Illuminate\Support\Facades\Auth;
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

            // Auth users use integer DB IDs; guests use MD5 string uniqueIds
            if (auth()->check()) {
                $selectedItems = array_map('intval', $selectedItems);
            }
            $cartItems = $this->checkoutService->getCheckoutItems($selectedItems);

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Your selected items are invalid or not available.');
            }

            $vatRate = $this->checkoutService->getVatRate();
            $totals  = $this->checkoutService->calculateOrderTotals($cartItems, $vatRate);

            session([
                'checkout' => [
                    'items'       => $cartItems->pluck('id')->toArray(),
                    'subtotal'    => $totals['subtotal'],
                    'discount'    => $totals['discountTotal'],
                    'vat'         => $totals['taxTotal'],
                    'grand_total' => $totals['grandTotal'],
                ],
            ]);

            return view('customer.checkout', [
                'cartItems'     => $cartItems,
                'subtotal'      => $totals['subtotal'],
                'discountTotal' => $totals['discountTotal'],
                'taxAmount'     => $totals['taxTotal'],
                'grandTotal'    => $totals['grandTotal'],
            ]);
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

            return redirect()->route('checkout.confirmation', $result['order']);
        } catch (\Exception $e) {
            if ($e->getMessage() === 'PRICE_CHANGED') {
                return redirect()->back()->with('warning', 'Product prices have changed. Please review the updated totals.');
            }

            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }
    }

    public function confirmation(Order $order)
    {
        // Allow the order owner or the guest session that placed it
        if ($order->user_id && $order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$order->user_id && $order->guest_session_id !== session()->getId()) {
            abort(403);
        }

        $order->load(['orderItems', 'shippingAddress']);

        return view('customer.order-confirmation', compact('order'));
    }

}
