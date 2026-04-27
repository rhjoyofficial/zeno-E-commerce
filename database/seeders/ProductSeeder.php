<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $tags = Tag::pluck('id', 'name');

        foreach ($this->catalog() as $mainCategory => $products) {
            foreach ($products as $index => $item) {
                $category = Category::where('category_name', $item['category'])->firstOrFail();
                $brand = Brand::where('brand_name', $item['brand'])->firstOrFail();

                $product = Product::updateOrCreate(
                    ['sku' => $item['sku']],
                    [
                        'title' => $item['title'],
                        'short_description' => $item['short_description'],
                        'price' => $item['price'],
                        'discount' => isset($item['discount_price']),
                        'discount_price' => $item['discount_price'] ?? null,
                        'is_new_arrival' => $index < 4,
                        'has_variants' => true,
                        'stock_alert' => 8,
                        'stock_quantity' => 0,
                        'slug' => Str::slug($item['title']) . '-' . strtolower($item['sku']),
                        'status' => 'active',
                        'category_id' => $category->id,
                        'brand_id' => $brand->id,
                    ]
                );

                ProductDetail::updateOrCreate(
                    ['product_id' => $product->id],
                    [
                        'description' => $item['description'],
                        'specifications' => 'Material: ' . $item['material'] . "\nFit: " . $item['fit'] . "\nCare: Machine wash cold",
                        'warranty' => '7 day exchange for unused items with tags attached.',
                    ]
                );

                ProductImage::updateOrCreate(
                    ['primary_scope_key' => $product->id . ':base'],
                    [
                        'product_id' => $product->id,
                        'variant_id' => null,
                        'image_path' => $item['image'],
                        'is_primary' => true,
                    ]
                );

                $product->tags()->sync($item['tags']->map(fn ($tag) => $tags[$tag])->all());
                $stock = $this->seedVariants($product, $item);
                $product->update(['stock_quantity' => $stock]);
            }
        }
    }

    private function seedVariants(Product $product, array $item): int
    {
        $stock = 0;
        $sizes = $item['audience'] === 'kids' ? ['4Y', '6Y', '8Y', '10Y'] : ['S', 'M', 'L', 'XL'];

        foreach ($sizes as $sizeName) {
            foreach ($item['colors'] as $colorName) {
                $size = ProductSize::where('name', $sizeName)->firstOrFail();
                $color = Color::where('name', $colorName)->firstOrFail();
                $qty = $item['audience'] === 'kids' ? 14 : 12;
                $stock += $qty;

                $product->variants()->updateOrCreate(
                    ['color_id' => $color->id, 'size_id' => $size->id],
                    [
                        'price' => $item['price'],
                        'discount_price' => $item['discount_price'] ?? null,
                        'stock_quantity' => $qty,
                        'stock_alert' => 4,
                        'sku' => $item['sku'] . '-' . strtoupper(str_replace(' ', '', $colorName)) . '-' . $sizeName,
                        'status' => 'active',
                    ]
                );
            }
        }

        return $stock;
    }

    private function catalog(): array
    {
        return [
            "Men's Clothing" => [
                $this->product('men', 'ZENO-MEN-001', 'Oxford Cotton Shirt', 'Shirts', 'Uniqlo', 1890, 1590, ['White', 'Sky Blue'], ['office-wear', 'modest-fit', 'new-arrival'], 'Crisp oxford shirt for office, university, and Friday family plans.', 'Breathable cotton oxford', 'Regular western fit'),
                $this->product('men', 'ZENO-MEN-002', 'Slim Stretch Chino', 'Trousers & Chinos', 'H&M', 2490, 2190, ['Beige', 'Navy'], ['office-wear', 'weekend'], 'Smart chino trouser with light stretch for Dhaka commute comfort.', 'Cotton twill with elastane', 'Slim tapered fit'),
                $this->product('men', 'ZENO-MEN-003', 'Classic Denim Jacket', 'Jackets', 'Levis', 4590, null, ['Denim Blue', 'Black'], ['denim', 'travel'], 'A western denim layer that works over tees, polos, and casual shirts.', 'Mid-weight cotton denim', 'Regular fit'),
                $this->product('men', 'ZENO-MEN-004', 'Pique Polo Shirt', 'T-Shirts & Polos', 'Marks & Spencer', 1690, 1390, ['Navy', 'White'], ['weekend', 'summer', 'new-arrival'], 'Clean polo shirt for casual offices, weekend lunches, and travel.', 'Cotton pique', 'Comfort fit'),
                $this->product('men', 'ZENO-MEN-005', 'Premium Crew Neck T-Shirt', 'T-Shirts & Polos', 'Gap', 990, null, ['Black', 'Olive'], ['weekend', 'summer'], 'Soft everyday tee with a western streetwear shape.', 'Combed cotton jersey', 'Regular fit'),
                $this->product('men', 'ZENO-MEN-006', 'Lightweight Bomber Jacket', 'Jackets', 'Zara', 3990, 3490, ['Olive', 'Black'], ['travel', 'premium'], 'Light bomber jacket for winter evenings and air-conditioned hangouts.', 'Poly-cotton shell', 'Relaxed fit'),
                $this->product('men', 'ZENO-MEN-007', 'Straight Fit Jeans', "Men's Denim", 'Levis', 3290, null, ['Denim Blue', 'Charcoal'], ['denim', 'weekend'], 'Classic straight jeans for a clean western casual wardrobe.', 'Cotton denim', 'Straight fit'),
                $this->product('men', 'ZENO-MEN-008', 'Casual Knit Blazer', 'Jackets', 'Mango', 5490, 4990, ['Navy', 'Charcoal'], ['office-wear', 'premium'], 'Soft blazer for semi-formal dinners, office events, and Eid visits.', 'Knit ponte fabric', 'Modern fit'),
                $this->product('men', 'ZENO-MEN-009', 'Soft Fleece Hoodie', 'T-Shirts & Polos', 'Nike', 2890, null, ['Black', 'Maroon'], ['travel', 'weekend'], 'Warm hoodie with a clean athletic western silhouette.', 'Cotton fleece', 'Relaxed fit'),
                $this->product('men', 'ZENO-MEN-010', 'Utility Cargo Trouser', 'Trousers & Chinos', 'Adidas', 2990, 2590, ['Olive', 'Black'], ['travel', 'weekend'], 'Practical cargo trouser for travel, campus, and casual weekends.', 'Cotton ripstop', 'Straight utility fit'),
            ],
            "Women's Clothing" => [
                $this->product('women', 'ZENO-WOMEN-001', 'Floral Midi Dress', 'Dresses', 'Mango', 3490, 2990, ['Dusty Pink', 'Navy'], ['new-arrival', 'eid-ready', 'premium'], 'Elegant midi dress with modest coverage and a western floral shape.', 'Printed viscose blend', 'Flowy fit'),
                $this->product('women', 'ZENO-WOMEN-002', 'Wide Leg Palazzo Trouser', 'Pants & Palazzos', 'Zara', 2290, null, ['Black', 'Beige'], ['office-wear', 'modest-fit'], 'Easy wide-leg trouser that pairs with shirts, kurtis, or blouses.', 'Drape crepe', 'Wide leg fit'),
                $this->product('women', 'ZENO-WOMEN-003', 'Satin Button Blouse', 'Tops & Blouses', 'H&M', 2190, 1890, ['White', 'Maroon'], ['office-wear', 'premium'], 'Soft satin blouse for dinner plans and polished workwear.', 'Satin polyester', 'Relaxed fit'),
                $this->product('women', 'ZENO-WOMEN-004', 'Cropped Denim Jacket', 'Outerwear', 'Levis', 4290, null, ['Denim Blue', 'Black'], ['denim', 'travel'], 'A western denim jacket cut to layer over dresses and tops.', 'Cotton denim', 'Cropped regular fit'),
                $this->product('women', 'ZENO-WOMEN-005', 'Pleated Midi Skirt', 'Pants & Palazzos', 'Next', 2690, 2390, ['Beige', 'Black'], ['office-wear', 'modest-fit'], 'Pleated skirt with easy movement for smart modest styling.', 'Poly chiffon', 'A-line fit'),
                $this->product('women', 'ZENO-WOMEN-006', 'High Rise Wide Jeans', "Women's Denim", 'Levis', 3590, null, ['Denim Blue', 'Charcoal'], ['denim', 'weekend'], 'High-rise western denim with a comfortable wide leg.', 'Stretch denim', 'Wide leg fit'),
                $this->product('women', 'ZENO-WOMEN-007', 'Rib Knit Cardigan', 'Outerwear', 'Uniqlo', 2490, 2190, ['Dusty Pink', 'Navy'], ['travel', 'new-arrival'], 'Light cardigan for layering through mild Bangladesh winter evenings.', 'Rib knit cotton blend', 'Regular fit'),
                $this->product('women', 'ZENO-WOMEN-008', 'Wrap Front Top', 'Tops & Blouses', 'Mango', 1990, null, ['Black', 'Olive'], ['weekend', 'premium'], 'Flattering wrap top with modest neckline and western styling.', 'Soft knit jersey', 'Wrap fit'),
                $this->product('women', 'ZENO-WOMEN-009', 'Tailored Casual Blazer', 'Outerwear', 'Zara', 5790, 5290, ['Navy', 'Beige'], ['office-wear', 'premium'], 'Tailored blazer for office wear, presentations, and dinners.', 'Woven suiting blend', 'Tailored fit'),
                $this->product('women', 'ZENO-WOMEN-010', 'Cotton Shirt Dress', 'Dresses', 'Gap', 3290, null, ['Sky Blue', 'White'], ['summer', 'modest-fit'], 'Breathable shirt dress for brunch, travel, and casual workdays.', 'Cotton poplin', 'Relaxed fit'),
            ],
            'Kids Clothing' => [
                $this->product('kids', 'ZENO-KIDS-001', 'Graphic Cotton T-Shirt', 'Boys Casuals', 'H&M', 790, null, ['White', 'Navy'], ['kids-favorite', 'summer'], 'Playful cotton tee for school breaks and weekend outings.', 'Cotton jersey', 'Kids regular fit'),
                $this->product('kids', 'ZENO-KIDS-002', 'Denim Dungaree Set', 'Toddler Wear', 'Next', 1990, 1690, ['Denim Blue', 'Sky Blue'], ['denim', 'kids-favorite'], 'Comfortable denim dungaree set for toddlers.', 'Soft cotton denim', 'Relaxed toddler fit'),
                $this->product('kids', 'ZENO-KIDS-003', 'Fleece Hoodie Set', 'School Casuals', 'Gap', 2290, null, ['Maroon', 'Charcoal'], ['travel', 'kids-favorite'], 'Warm hoodie and jogger set for cool mornings.', 'Cotton fleece', 'Relaxed fit'),
                $this->product('kids', 'ZENO-KIDS-004', 'Classic Polo Shirt', 'Boys Casuals', 'Marks & Spencer', 1190, 990, ['Navy', 'White'], ['school-casual', 'summer'], 'Smart polo shirt for family events and casual school programs.', 'Cotton pique', 'Regular fit'),
                $this->product('kids', 'ZENO-KIDS-005', 'Chino Shorts', 'Boys Casuals', 'Uniqlo', 1290, null, ['Beige', 'Olive'], ['summer', 'weekend'], 'Easy chino shorts for warm Bangladesh weather.', 'Cotton twill', 'Straight fit'),
                $this->product('kids', 'ZENO-KIDS-006', 'Girls Floral Dress', 'Girls Dresses', 'Zara', 2490, 2190, ['Dusty Pink', 'White'], ['eid-ready', 'kids-favorite'], 'Pretty floral western dress with comfortable lining.', 'Printed cotton voile', 'A-line fit'),
                $this->product('kids', 'ZENO-KIDS-007', 'Stretch Jogger Pants', 'School Casuals', 'Nike', 1590, null, ['Black', 'Charcoal'], ['travel', 'weekend'], 'Soft joggers for active kids and easy daily wear.', 'Cotton fleece', 'Tapered fit'),
                $this->product('kids', 'ZENO-KIDS-008', 'Mini Denim Jacket', 'Party Wear', 'Levis', 2990, 2590, ['Denim Blue', 'Black'], ['denim', 'eid-ready'], 'Mini denim jacket for layering over tees and dresses.', 'Cotton denim', 'Regular kids fit'),
                $this->product('kids', 'ZENO-KIDS-009', 'Checked Party Shirt', 'Party Wear', 'Next', 1490, null, ['Sky Blue', 'Maroon'], ['eid-ready', 'premium'], 'Western checked shirt for parties and family dinners.', 'Cotton poplin', 'Regular fit'),
                $this->product('kids', 'ZENO-KIDS-010', 'Cotton Leggings Pack', 'Girls Dresses', 'H&M', 990, 850, ['Black', 'Dusty Pink'], ['kids-favorite', 'weekend'], 'Soft leggings that pair with dresses, tees, and tunics.', 'Stretch cotton jersey', 'Slim comfort fit'),
            ],
        ];
    }

    private function product(string $audience, string $sku, string $title, string $category, string $brand, int $price, ?int $discountPrice, array $colors, array $tags, string $short, string $material, string $fit): array
    {
        return [
            'audience' => $audience,
            'sku' => $sku,
            'title' => $title,
            'category' => $category,
            'brand' => $brand,
            'price' => $price,
            'discount_price' => $discountPrice,
            'colors' => $colors,
            'tags' => collect($tags),
            'short_description' => $short,
            'description' => $short . ' Designed for Bangladesh weather, easy styling, and everyday movement.',
            'material' => $material,
            'fit' => $fit,
            'image' => 'storage/products/' . Str::slug($title) . '.jpg',
        ];
    }
}
