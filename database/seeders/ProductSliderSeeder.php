<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSlider;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ProductSliderSeeder extends Seeder
{
    public function run(): void
    {
        // Get 3 active products to feature on sliders
        $products = Product::where('status', 'active')->take(3)->get();

        if ($products->isEmpty()) {
            $this->command->warn('No active products found. Skipping ProductSliderSeeder.');
            return;
        }

        $sliderData = [
            [
                'title'     => 'New Season Collection',
                'short_des' => 'Discover the latest arrivals crafted for style and comfort.',
                'price'     => null,
                'image'     => 'storage/sliders/slider-1.jpg',
                'status'    => 'active',
            ],
            [
                'title'     => 'Premium Quality Picks',
                'short_des' => 'Explore our handpicked premium products at unbeatable prices.',
                'price'     => null,
                'image'     => 'storage/sliders/slider-2.jpg',
                'status'    => 'active',
            ],
            [
                'title'     => 'Exclusive Sale — Up to 50% Off',
                'short_des' => 'Limited time offer on selected items. Shop now before it ends.',
                'price'     => null,
                'image'     => 'storage/sliders/slider-3.jpg',
                'status'    => 'active',
            ],
        ];

        foreach ($products as $index => $product) {
            $data = $sliderData[$index] ?? $sliderData[0];

            // Use the product's actual price for the slider price label
            $data['price']      = $product->discount && $product->discount_price
                ? 'BDT ' . number_format($product->discount_price, 2)
                : 'BDT ' . number_format($product->price, 2);
            $data['product_id'] = $product->id;

            ProductSlider::firstOrCreate(
                ['product_id' => $product->id],
                $data
            );
        }
    }
}
