<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->firstOrFail();

        $brands = [
            ['brand_name' => 'Apple',    'brand_image' => 'storage/brands/apple.jpg',    'status' => 'active', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['brand_name' => 'Samsung',  'brand_image' => 'storage/brands/samsung.jpg',  'status' => 'active', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['brand_name' => 'Nike',     'brand_image' => 'storage/brands/nike.jpg',     'status' => 'active', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['brand_name' => 'Sony',     'brand_image' => 'storage/brands/sony.jpg',     'status' => 'active', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['brand_name' => 'Adidas',   'brand_image' => 'storage/brands/adidas.jpg',   'status' => 'active', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['brand_name' => 'H&M',      'brand_image' => 'storage/brands/hm.jpg',       'status' => 'active', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['brand_name' => 'Zara',     'brand_image' => 'storage/brands/zara.jpg',     'status' => 'active', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['brand_name' => 'Xiaomi',   'brand_image' => 'storage/brands/xiaomi.jpg',   'status' => 'active', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['brand_name' => 'Reebok',   'brand_image' => 'storage/brands/reebok.jpg',   'status' => 'active', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['brand_name' => 'Uniqlo',   'brand_image' => 'storage/brands/uniqlo.jpg',   'status' => 'active', 'created_by' => $admin->id, 'updated_by' => $admin->id],
        ];

        Brand::upsert($brands, ['brand_name'], ['brand_image', 'status', 'created_by', 'updated_by']);
    }
}