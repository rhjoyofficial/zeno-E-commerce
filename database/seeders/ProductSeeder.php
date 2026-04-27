<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use App\Models\Role;
use App\Models\Tag;
use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    public function run(): void
    {
        $admin = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->firstOrFail();
        $colors = Color::all();
        $sizes = ProductSize::all();
        $brands = Brand::all();

        // Ensure core tags exist
        $newTag = Tag::firstOrCreate(['name' => 'new'], ['name' => 'new', 'created_by' => $admin->id, 'updated_by' => $admin->id]);
        $popularTag = Tag::firstOrCreate(['name' => 'popular'], ['name' => 'popular', 'created_by' => $admin->id, 'updated_by' => $admin->id]);

        // Define Category Groups
        $mensCategories = Category::where('category_name', 'like', 'Men %')->pluck('id')->toArray();
        $womensCategories = Category::where('category_name', 'like', 'Women %')->pluck('id')->toArray();
        $kidsCategories = Category::where('category_name', 'like', 'Kids %')
            ->orWhere('category_name', 'like', 'Boys %')
            ->orWhere('category_name', 'like', 'Girls %')
            ->orWhere('category_name', 'like', 'Baby %')
            ->pluck('id')->toArray();
        $otherCategories = Category::whereNotIn('id', array_merge($mensCategories, $womensCategories, $kidsCategories))->pluck('id')->toArray();

        $counts = [
            'new_arrivals' => 8,
            'mens_fashion' => 8,
            'womens_fashion' => 8,
            'kids_fashion' => 8,
            'others' => 8,
        ];

        $totalGenerated = 0;

        foreach ($counts as $group => $count) {
            for ($i = 0; $i < $count; $i++) {
                // Determine Category
                if ($group === 'mens_fashion') $catId = $this->faker->randomElement($mensCategories);
                elseif ($group === 'womens_fashion') $catId = $this->faker->randomElement($womensCategories);
                elseif ($group === 'kids_fashion') $catId = $this->faker->randomElement($kidsCategories);
                else $catId = $this->faker->randomElement($otherCategories);

                $title = $this->faker->words(3, true) . ' ' . ($totalGenerated + 1);
                $product = Product::create([
                    'title' => ucwords($title),
                    'short_description' => $this->faker->sentence(10),
                    'price' => $this->faker->randomFloat(2, 500, 5000),
                    'discount' => $this->faker->boolean(40),
                    'discount_price' => null,
                    'stock_quantity' => 0, // Will be sum of variants
                    'stock_alert' => 5,
                    'slug' => Str::slug($title) . '-' . Str::random(5),
                    'sku' => 'SKU-' . strtoupper(Str::random(8)),
                    'status' => 'active',
                    'has_variants' => true,
                    'category_id' => $catId,
                    'brand_id' => $brands->random()->id,
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);

                if ($product->discount) {
                    $product->update(['discount_price' => $product->price * 0.8]);
                }

                // Tagging
                $tags = [$popularTag->id];
                if ($group === 'new_arrivals' || $this->faker->boolean(30)) {
                    $tags[] = $newTag->id;
                }
                $product->tags()->sync(collect($tags)->mapWithKeys(fn($id) => [$id => ['created_by' => $admin->id, 'updated_by' => $admin->id]]));

                // Add Details
                ProductDetail::create([
                    'product_id' => $product->id,
                    'description' => '<p>' . $this->faker->paragraphs(3, true) . '</p>',
                    'specifications' => "Material: Premium Cotton\nFit: Regular\nWash: Machine Wash",
                    'created_by' => $admin->id,
                    'updated_by' => $admin->id,
                ]);

                // Create 3-5 Variants
                $variantCount = rand(3, 5);
                $selectedColors = $colors->random(min($variantCount, $colors->count()));
                $selectedSizes = $sizes->random(min($variantCount, $sizes->count()));

                $totalStock = 0;
                for ($v = 0; $v < $variantCount; $v++) {
                    $vQty = rand(10, 50);
                    $totalStock += $vQty;
                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id' => $selectedColors[$v % $selectedColors->count()]->id,
                        'size_id' => $selectedSizes[$v % $selectedSizes->count()]->id,
                        'price' => $product->price + rand(-50, 100),
                        'stock_quantity' => $vQty,
                        'stock_alert' => 5,
                        'sku' => $product->sku . '-V' . ($v + 1),
                        'status' => 'active',
                        'created_by' => $admin->id,
                        'updated_by' => $admin->id,
                    ]);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'variant_id' => $variant->id,
                        'image_path' => "storage/products/dummy-" . rand(1, 10) . ".jpg",
                        'is_primary' => $v === 0,
                        'created_by' => $admin->id,
                        'updated_by' => $admin->id,
                    ]);
                }

                $product->update(['stock_quantity' => $totalStock]);
                $totalGenerated++;
            }
        }
    }
}