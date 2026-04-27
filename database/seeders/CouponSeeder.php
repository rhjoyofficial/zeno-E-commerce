<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->firstOrFail();

        $coupons = [
            [
                'code'             => 'SAVE10',
                'type'             => 'fixed',
                'value'            => 100.00,      // BDT 100 off
                'min_order_amount' => 500.00,
                'valid_from'       => now(),
                'valid_to'         => now()->addMonths(3),
                'usage_limit'      => 200,
                'used_count'       => 0,
                'is_active'        => true,
                'created_by'       => $admin->id,
                'updated_by'       => $admin->id,
            ],
            [
                'code'             => 'PERCENT20',
                'type'             => 'percentage',
                'value'            => 20.00,        // 20% off
                'min_order_amount' => 1000.00,
                'valid_from'       => now(),
                'valid_to'         => now()->addMonths(3),
                'usage_limit'      => 100,
                'used_count'       => 0,
                'is_active'        => true,
                'created_by'       => $admin->id,
                'updated_by'       => $admin->id,
            ],
            [
                'code'             => 'WELCOME50',
                'type'             => 'fixed',
                'value'            => 50.00,        // BDT 50 off for new customers
                'min_order_amount' => 300.00,
                'valid_from'       => now(),
                'valid_to'         => now()->addYear(),
                'usage_limit'      => null,          // unlimited
                'used_count'       => 0,
                'is_active'        => true,
                'created_by'       => $admin->id,
                'updated_by'       => $admin->id,
            ],
            [
                'code'             => 'FLASH15',
                'type'             => 'percentage',
                'value'            => 15.00,        // 15% flash sale
                'min_order_amount' => null,
                'valid_from'       => now(),
                'valid_to'         => now()->addWeek(),
                'usage_limit'      => 50,
                'used_count'       => 0,
                'is_active'        => true,
                'created_by'       => $admin->id,
                'updated_by'       => $admin->id,
            ],
            [
                'code'             => 'EXPIRED',
                'type'             => 'fixed',
                'value'            => 200.00,
                'min_order_amount' => 1000.00,
                'valid_from'       => now()->subMonths(2),
                'valid_to'         => now()->subMonth(),  // Already expired
                'usage_limit'      => 50,
                'used_count'       => 50,
                'is_active'        => false,
                'created_by'       => $admin->id,
                'updated_by'       => $admin->id,
            ],
        ];

        Coupon::upsert(
            $coupons,
            ['code'],
            ['type', 'value', 'min_order_amount', 'valid_from', 'valid_to', 'usage_limit', 'used_count', 'is_active', 'created_by', 'updated_by']
        );
    }
}
