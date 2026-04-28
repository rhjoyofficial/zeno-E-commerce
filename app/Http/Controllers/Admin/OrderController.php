<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    private const VALID_STATUSES = [
        'pending', 'confirmed', 'processing',
        'shipped', 'delivered', 'cancelled',
        'refunded', 'partially_refunded', 'on_hold',
    ];

    public function index(Request $request)
    {
        $query = Order::with(['user', 'shippingAddress'])
            ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'shippingAddress', 'orderItems']);

        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        // Edit view not yet built — reuse the show page which has inline status update
        return redirect()->route('admin.orders.show', $order);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status'         => 'sometimes|in:' . implode(',', self::VALID_STATUSES),
            'payment_status' => 'sometimes|in:pending,paid,partially_paid,refunded,failed',
            'admin_notes'    => 'nullable|string|max:1000',
            'tracking_number' => 'nullable|string|max:255',
            'tracking_url'   => 'nullable|url|max:500',
        ]);

        if (isset($validated['status']) && !$order->canTransitionTo($validated['status'])) {
            return redirect()->back()
                ->with('error', "Cannot change order status from '{$order->status}' to '{$validated['status']}'.");
        }

        $order->update($validated);

        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order removed.');
    }

    /**
     * Update only the status field — called via AJAX from the order list.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', self::VALID_STATUSES),
        ]);

        if (!$order->canTransitionTo($validated['status'])) {
            $error = "Cannot change order status from '{$order->status}' to '{$validated['status']}'.";
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $error], 422);
            }
            return redirect()->back()->with('error', $error);
        }

        try {
            $order->update(['status' => $validated['status']]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order status updated to ' . str_replace('_', ' ', $validated['status']),
                    'status'  => $order->status,
                ]);
            }

            return redirect()->route('admin.orders.show', $order)
                ->with('success', 'Order status updated.');
        } catch (\Exception $e) {
            Log::error('Order status update failed: ' . $e->getMessage(), ['order_id' => $order->id]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update status. Please try again.',
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to update status. Please try again.');
        }
    }

    /**
     * Return JSON + rendered HTML for the quick-view modal.
     */
    public function quickView(Order $order)
    {
        $order->load(['shippingAddress', 'orderItems']);

        $html = view('admin.orders.partials.quick-view', compact('order'))->render();

        return response()->json([
            'success' => true,
            'order'   => [
                'order_number' => $order->order_number,
                'status'       => $order->status,
                'total'        => $order->total,
            ],
            'html' => $html,
        ]);
    }
}
