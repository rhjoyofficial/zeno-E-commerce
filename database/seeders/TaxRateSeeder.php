<?php

namespace Database\Seeders;

use App\Models\TaxRate;
use Illuminate\Database\Seeder;

class TaxRateSeeder extends Seeder
{
    public function run(): void
    {
        TaxRate::updateOrCreate(
            ['name' => 'Bangladesh VAT', 'region' => 'BD'],
            ['name' => 'Bangladesh VAT', 'rate' => 5.00, 'region' => 'BD', 'is_active' => true]
        );
    }
}
