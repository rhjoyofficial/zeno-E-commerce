<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // ── Core Auth & Settings ──────────────────────────────────
            RoleSeeder::class,           // roles  (no deps)
            UserSeeder::class,           // users + customer_profiles  (needs roles)
            SettingSeeder::class,        // settings (needs users)

            // ── Lookup / Reference Tables ──────────────────────────────
            CountrySeeder::class,        // countries  (no deps)
            DivisionSeeder::class,       // divisions  (needs countries)
            DistrictSeeder::class,       // districts  (needs divisions)

            // ── Product Catalogue ──────────────────────────────────────
            CategorySeeder::class,       // categories  (needs users)
            BrandSeeder::class,          // brands  (needs users)
            ColorSeeder::class,          // colors  (needs users)
            ProductSizeSeeder::class,    // product_sizes  (needs users)
            TagSeeder::class,            // tags  (needs users)

            // ── Commerce Config ────────────────────────────────────────
            CouponSeeder::class,         // coupons  (needs users)
            TaxRateSeeder::class,        // tax_rates  (needs users)
            SslcommerzAccountSeeder::class, // sslcommerz_accounts (standalone)

            // ── Shipping ───────────────────────────────────────────────
            ShippingAddressSeeder::class, // shipping_addresses  (needs users)

            // ── Products & Related ─────────────────────────────────────
            ProductSeeder::class,         // products, product_details, product_images,
                                          // product_variants, product_tags  (needs categories, brands, colors, sizes, tags, users)
            ProductSliderSeeder::class,   // product_sliders  (needs products)
            ProductReviewSeeder::class,   // product_reviews  (needs products, customer_profiles)

            // ── Content / CMS ──────────────────────────────────────────
            PolicySeeder::class,          // policies  (needs users)
            HomeSectionSeeder::class,     // home_sections + home_section_items  (needs categories, users)
            NavigationMenuSeeder::class,  // navigation_menus, navigation_menu_items, mega_menu_contents

            // ── Orders ─────────────────────────────────────────────────
            OrderSeeder::class,           // orders + order_items  (needs products, shipping_addresses, users)
        ]);
    }
}
