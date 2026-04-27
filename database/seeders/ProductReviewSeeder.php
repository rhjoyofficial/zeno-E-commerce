<?php

namespace Database\Seeders;

use App\Models\CustomerProfile;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Database\Seeder;

class ProductReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = CustomerProfile::orderBy('id')->get();
        $comments = [
            'Fabric feels comfortable and the fit is accurate.',
            'Looks premium for the price and delivery was smooth.',
            'Good choice for Bangladesh weather.',
            'Color and stitching matched the product photos.',
        ];

        Product::orderBy('id')->take(18)->get()->each(function (Product $product, int $index) use ($customers, $comments) {
            $customer = $customers[$index % max(1, $customers->count())] ?? null;
            if (!$customer) {
                return;
            }

            ProductReview::updateOrCreate(
                ['customer_id' => $customer->id, 'product_id' => $product->id],
                [
                    'description' => $comments[$index % count($comments)],
                    'rating' => $index % 5 === 0 ? 4 : 5,
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'status' => 'approved',
                ]
            );
        });
    }
}
