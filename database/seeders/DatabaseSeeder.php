<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->resetDemoTables();

        $this->call([
            RoleSeeder::class,
            CountrySeeder::class,
            DivisionSeeder::class,
            DistrictSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            CategorySeeder::class,
            BrandSeeder::class,
            ColorSeeder::class,
            ProductSizeSeeder::class,
            TagSeeder::class,
            CouponSeeder::class,
            TaxRateSeeder::class,
            SslcommerzAccountSeeder::class,
            ShippingAddressSeeder::class,
            ProductSeeder::class,
            ProductSliderSeeder::class,
            ProductReviewSeeder::class,
            PolicySeeder::class,
            HomeSectionSeeder::class,
            NavigationMenuSeeder::class,
            OrderSeeder::class,
        ]);
    }

    private function resetDemoTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ([
            'order_items',
            'orders',
            'product_reviews',
            'product_sliders',
            'home_section_items',
            'home_sections',
            'mega_menu_contents',
            'navigation_menu_items',
            'navigation_menus',
            'product_tags',
            'product_images',
            'product_variants',
            'product_details',
            'product_carts',
            'product_wishes',
            'products',
            'tags',
            'product_sizes',
            'colors',
            'brands',
            'categories',
            'shipping_addresses',
            'customer_profiles',
            'users',
            'roles',
            'districts',
            'divisions',
            'countries',
            'coupons',
            'tax_rates',
            'sslcommerz_accounts',
            'policies',
            'settings',
            'sequences',
        ] as $table) {
            DB::table($table)->truncate();
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
