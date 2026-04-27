<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['code' => 'WELCOME10', 'type' => 'percentage', 'value' => 10, 'min_order_amount' => 2500, 'usage_limit' => 500],
            ['code' => 'EIDSTYLE', 'type' => 'fixed', 'value' => 500, 'min_order_amount' => 4500, 'usage_limit' => 300],
            ['code' => 'FREESHIP', 'type' => 'fixed', 'value' => 150, 'min_order_amount' => 3000, 'usage_limit' => 1000],
        ] as $coupon) {
            Coupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon + [
                    'valid_from' => now()->subDay(),
                    'valid_to' => now()->addMonths(6),
                    'used_count' => 0,
                    'is_active' => true,
                ]
            );
        }
    }
}
