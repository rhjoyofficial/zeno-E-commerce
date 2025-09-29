<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Handle bKash Payment
     */
    public function bkash(Request $request, Order $order)
    {
        try {
            // Step 1: Create Payment Request (bKash API)
            // Example: call your bKash service here
            // $paymentUrl = BkashService::initiatePayment($order);

            Log::info("Initiating bKash payment", ['order_id' => $order->id]);

            // Step 2: Redirect user to bKash payment page
            return redirect()->away('https://sandbox.bkash.com/payment-url'); // replace with real $paymentUrl orders.show
        } catch (\Exception $e) {
            Log::error("bKash Payment Failed: " . $e->getMessage(), ['order_id' => $order->id]);
            return redirect()->route('home', $order->id)
                ->with('error', 'Failed to initiate bKash payment. Please try again.');
        }
    }

    /**
     * Handle Mobile Banking Payment (Manual Verification)
     */
    public function mobileBanking(Request $request, Order $order)
    {
        $request->validate([
            'transaction_id' => 'required|string|max:100',
        ]);

        // Save transaction reference for admin verification orders.show
        $order->update([
            'status' => 'payment_pending',
            'transaction_id' => $request->transaction_id,
        ]);

        Log::info("Mobile banking transaction submitted", [
            'order_id' => $order->id,
            'transaction_id' => $request->transaction_id,
        ]);

        return redirect()->route('home', $order->id)
            ->with('info', 'Your transaction ID has been submitted. Order will be confirmed after verification.');
    }

    /**
     * Handle Card Payment (SSLCommerz / Stripe etc.)
     */
    public function card(Request $request, Order $order)
    {
        try {
            // Step 1: Initiate payment with SSLCommerz/Stripe
            // Example: $paymentUrl = SslCommerzService::makePayment($order);

            Log::info("Initiating Card Payment", ['order_id' => $order->id]);

            // Step 2: Redirect to gateway checkout page
            return redirect()->away('https://sandbox.sslcommerz.com/payment-url'); // replace with real $paymentUrl orders.show
        } catch (\Exception $e) {
            Log::error("Card Payment Failed: " . $e->getMessage(), ['order_id' => $order->id]);
            return redirect()->route('home', $order->id)
                ->with('error', 'Failed to initiate card payment. Please try again.');
        }
    }

    /**
     * Payment Callback (For Gateways like bKash, SSLCommerz)
     */
    public function callback(Request $request)
    {
        // Example: process gateway response here
        // Validate payment response and update order status
        Log::info("Payment Callback Received", $request->all());

        // Implement gateway-specific verification here
        return redirect()->route('cart.index')->with('success', 'Payment verified and order confirmed.');
    }
}
