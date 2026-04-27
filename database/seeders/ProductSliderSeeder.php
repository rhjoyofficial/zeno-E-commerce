<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSlider;
use Illuminate\Database\Seeder;

class ProductSliderSeeder extends Seeder
{
    public function run(): void
    {
        Product::where('is_new_arrival', true)
            ->orderBy('id')
            ->take(5)
            ->get()
            ->each(function (Product $product) {
                ProductSlider::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'title' => $product->title,
                        'short_des' => $product->short_description,
                        'price' => 'BDT ' . number_format((float) $product->final_price),
                        'image' => $product->image_path,
                        'product_id' => $product->id,
                        'status' => 'active',
                    ]
                );
            });
    }
}
