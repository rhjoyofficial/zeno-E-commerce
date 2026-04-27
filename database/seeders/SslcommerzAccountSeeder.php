<?php

namespace Database\Seeders;

use App\Models\SslcommerzAccount;
use Illuminate\Database\Seeder;

class SslcommerzAccountSeeder extends Seeder
{
    public function run(): void
    {
        SslcommerzAccount::updateOrCreate(
            ['store_id' => 'zeno_demo_store'],
            [
                'store_id' => 'zeno_demo_store',
                'store_passwd' => 'demo_password',
                'currency' => 'BDT',
                'success_url' => url('/payment/success'),
                'fail_url' => url('/payment/fail'),
                'cancel_url' => url('/payment/cancel'),
                'ipn_url' => url('/payment/ipn'),
                'init_url' => 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php',
            ]
        );
    }
}
