<?php

namespace Database\Seeders;

use App\Models\Policy;
use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            'about' => 'Zeno Fashion curates western everyday clothing for Bangladeshi customers with comfortable fabrics, modest styling options, and BDT pricing.',
            'refund' => 'Unused products with tags can be exchanged or refunded within 7 days according to our inspection policy.',
            'terms' => 'Orders are accepted subject to stock availability, payment confirmation, and accurate customer information.',
            'how to buy' => 'Select size and color, add to cart, choose delivery details, and confirm payment or cash on delivery.',
            'contact' => 'Contact customer care at care@zenofashion.test or +8801710000000.',
            'complain' => 'For complaints, share your order number and product photos within 48 hours of delivery.',
        ] as $type => $description) {
            Policy::updateOrCreate(['type' => $type], ['type' => $type, 'description' => $description]);
        }
    }
}
