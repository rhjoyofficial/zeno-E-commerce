<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function bkash(Request $request, Order $order)
    {
        $this->authorizeOrderAccess($order);

        try {
            // TODO: replace with real BkashService::initiatePayment($order)
            Log::info('Initiating bKash payment', ['order_id' => $order->id]);

            return redirect()->route('checkout.confirmation', $order)
                ->with('error', 'bKash gateway not configured.');
        } catch (\Exception $e) {
            Log::error('bKash payment initiation failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return redirect()->route('checkout.confirmation', $order)
                ->with('error', 'Failed to initiate bKash payment. Please try again.');
        }
    }

    public function mobileBanking(Request $request, Order $order)
    {
        $this->authorizeOrderAccess($order);

        $request->validate([
            'transaction_id' => 'required|string|max:100|regex:/^[A-Za-z0-9\-]+$/',
        ]);

        $order->update([
            'payment_status' => 'pending',
            'transaction_id'  => $request->transaction_id,
        ]);

        Log::info('Mobile banking transaction submitted', [
            'order_id'       => $order->id,
            'transaction_ref' => substr($request->transaction_id, 0, 6) . '***',
        ]);

        return redirect()->route('checkout.confirmation', $order)
            ->with('info', 'Your transaction ID has been submitted. Order will be confirmed after verification.');
    }

    public function card(Request $request, Order $order)
    {
        $this->authorizeOrderAccess($order);

        try {
            // TODO: replace with real SslCommerzService::makePayment($order) or StripeService
            Log::info('Initiating card payment', ['order_id' => $order->id]);

            return redirect()->route('checkout.confirmation', $order)
                ->with('error', 'Card payment gateway not configured.');
        } catch (\Exception $e) {
            Log::error('Card payment initiation failed', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return redirect()->route('checkout.confirmation', $order)
                ->with('error', 'Failed to initiate card payment. Please try again.');
        }
    }

    // Gateway POST-back endpoint — must verify gateway signature before updating any order.
    // Until real signature verification is wired up this rejects all callbacks (fail-safe).
    public function callback(Request $request)
    {
        Log::warning('Payment callback received — no verification logic implemented.', [
            'ip'      => $request->ip(),
            'gateway' => $request->query('gateway', 'unknown'),
        ]);

        abort(501, 'Payment callback verification not implemented.');
    }

    private function authorizeOrderAccess(Order $order): void
    {
        $user = Auth::user();

        if ($order->user_id) {
            if (!$user || $order->user_id !== $user->id) {
                abort(403);
            }
        } else {
            if ($order->guest_session_id !== session()->getId()) {
                abort(403);
            }
        }
    }
}
