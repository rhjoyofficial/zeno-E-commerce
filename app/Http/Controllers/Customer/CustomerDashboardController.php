<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $completedOrders = Order::byUser($user->id)->where('status', 'delivered')->count();
        $pendingOrders   = Order::byUser($user->id)->pending()->count();
        $wishlistCount   = $user->productWishes()->count();

        $recentOrders = Order::byUser($user->id)
            ->with('shippingAddress')
            ->latest()
            ->limit(5)
            ->get();

        return view('customer.dashboard', compact(
            'user',
            'completedOrders',
            'pendingOrders',
            'wishlistCount',
            'recentOrders'
        ));
    }

    public function orders()
    {
        $orders = Order::byUser(Auth::id())
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function orderDetails(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderItems', 'shippingAddress']);

        return view('customer.order-details', compact('order'));
    }

    public function addresses()
    {
        $addresses = ShippingAddress::byUser(Auth::id())
            ->latest()
            ->get();

        return view('customer.addresses', compact('addresses'));
    }

    public function addressDetails(ShippingAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        return view('customer.addresses', ['addresses' => collect([$address])]);
    }
}
