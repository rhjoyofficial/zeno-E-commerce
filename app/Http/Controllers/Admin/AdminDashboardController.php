<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AdminDashboardController extends Controller
{
    private const STATS_TTL = 900; // 15 minutes

    public function dashboard()
    {
        $stats = Cache::remember('admin_dashboard_stats', self::STATS_TTL, function () {
            return [
                'totalRevenue'   => Order::whereNotIn('status', ['cancelled', 'refunded'])->sum('total'),
                'totalOrders'    => Order::count(),
                'totalCustomers' => User::whereHas('role', fn($q) => $q->where('slug', 'customer'))->count(),
                'totalProducts'  => Product::count(),
                'topProducts'    => OrderItem::select(
                        'product_id',
                        DB::raw('SUM(quantity) as total_sold'),
                        DB::raw('SUM(row_total) as total_revenue')
                    )
                    ->with('product')
                    ->groupBy('product_id')
                    ->orderByDesc('total_sold')
                    ->limit(5)
                    ->get(),
            ];
        });

        // Recent orders are intentionally live — always shows latest 5
        $recentOrders = Order::with('user')->latest()->limit(5)->get();

        return view('admin.dashboard', [
            'totalRevenue'   => $stats['totalRevenue'],
            'totalOrders'    => $stats['totalOrders'],
            'totalCustomers' => $stats['totalCustomers'],
            'totalProducts'  => $stats['totalProducts'],
            'recentOrders'   => $recentOrders,
            'topProducts'    => $stats['topProducts'],
        ]);
    }
}
