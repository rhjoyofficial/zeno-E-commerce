<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Zara', 'H&M', 'Uniqlo', 'Levis', 'Mango', 'Gap', 'Marks & Spencer', 'Next', 'Nike', 'Adidas'] as $name) {
            Brand::updateOrCreate(
                ['brand_name' => $name],
                [
                    'brand_name' => $name,
                    'brand_image' => 'storage/brands/' . str($name)->slug() . '.jpg',
                    'status' => 'active',
                ]
            );
        }
    }
}
