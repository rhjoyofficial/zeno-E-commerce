<?php

namespace Database\Seeders;

use App\Models\TaxRate;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class TaxRateSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->firstOrFail();

        $taxRates = [
            [
                'name'       => 'Standard VAT (Bangladesh)',
                'rate'       => 15.00,   // Bangladesh standard VAT
                'region'     => 'BD',
                'is_active'  => true,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ],
            [
                'name'       => 'Reduced VAT',
                'rate'       => 5.00,
                'region'     => 'BD',
                'is_active'  => true,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ],
            [
                'name'       => 'Zero Rated',
                'rate'       => 0.00,
                'region'     => 'BD',
                'is_active'  => true,
                'created_by' => $admin->id,
                'updated_by' => $admin->id,
            ],
        ];

        TaxRate::upsert($taxRates, ['name', 'region'], ['rate', 'is_active', 'created_by', 'updated_by']);
    }
}