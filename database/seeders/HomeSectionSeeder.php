<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\HomeSection;
use App\Models\HomeSectionItem;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->first();

        if (!$admin) {
            $this->command->warn('Admin user not found. Skipping HomeSectionSeeder.');
            return;
        }

        // Fetch category references (created by CategorySeeder)
        $mensCategory   = Category::where('category_name', "Men's Clothing")->first();
        $womensCategory = Category::where('category_name', "Women's Clothing")->first();
        $kidsCategory   = Category::where('category_name', "Kids Clothing")->first();

        // ── 1. New Arrivals ───────────────────────────────────────────
        HomeSection::updateOrCreate(
            ['type' => 'new_arrivals'],
            [
                'title'            => 'New Arrival',
                'subtitle'         => 'Fresh drops just for you',
                'section_title'    => 'New Collection 2025',
                'section_subtitle' => 'Explore the latest trends in fashion',
                'banner_image'     => 'storage/section/new-arrivals-banner.jpg',
                'category_id'      => null,
                'status'           => 'active',
                'order'            => 1,
                'created_by'       => $admin->id,
                'updated_by'       => $admin->id,
            ]
        );

        // ── 2. Men's Fashion ──────────────────────────────────────────
        $mensSection = HomeSection::updateOrCreate(
            ['type' => 'mens_fashion'],
            [
                'title'            => "Men's Fashion",
                'subtitle'         => 'Smart & Casual',
                'section_title'    => 'Elevate Your Style',
                'section_subtitle' => 'Premium menswear for every occasion',
                'banner_image'     => 'storage/section/mens-fashion-banner.jpg',
                'category_id'      => $mensCategory?->id,
                'status'           => 'active',
                'order'            => 2,
                'created_by'       => $admin->id,
                'updated_by'       => $admin->id,
            ]
        );

        // ── 3. Women's Fashion ────────────────────────────────────────
        $womensSection = HomeSection::updateOrCreate(
            ['type' => 'womens_fashion'],
            [
                'title'            => "Women's Fashion",
                'subtitle'         => 'Elegant & Trendy',
                'section_title'    => 'Discover Elegance',
                'section_subtitle' => 'Curated collection for the modern woman',
                'banner_image'     => 'storage/section/womens-fashion-banner.jpg',
                'category_id'      => $womensCategory?->id,
                'status'           => 'active',
                'order'            => 3,
                'created_by'       => $admin->id,
                'updated_by'       => $admin->id,
            ]
        );

        // ── 4. Kids Fashion ───────────────────────────────────────────
        $kidsSection = HomeSection::updateOrCreate(
            ['type' => 'kids_fashion'],
            [
                'title'            => 'Kids Fashion',
                'subtitle'         => 'Cute & Comfortable',
                'section_title'    => 'Little Style Icons',
                'section_subtitle' => 'Playful and durable outfits for children',
                'banner_image'     => 'storage/section/kids-fashion-banner.jpg',
                'category_id'      => $kidsCategory?->id,
                'status'           => 'active',
                'order'            => 4,
                'created_by'       => $admin->id,
                'updated_by'       => $admin->id,
            ]
        );

        // Cleanup old sections if they exist (from previous seeder versions)
        HomeSection::whereNotIn('type', ['new_arrivals', 'mens_fashion', 'womens_fashion', 'kids_fashion'])->delete();

        // ── Home Section Items for Men's Fashion ──────────────────────
        if ($mensSection && $mensCategory) {
            $items = [
                ['title' => 'Casual Shirts', 'image' => 'storage/section/items/mens-shirts.jpg'],
                ['title' => 'Denim Jeans',   'image' => 'storage/section/items/mens-jeans.jpg'],
                ['title' => 'Outerwear',     'image' => 'storage/section/items/mens-outerwear.jpg'],
            ];
            $this->syncItems($mensSection, $mensCategory, $items, $admin->id);
        }

        // ── Home Section Items for Women's Fashion ────────────────────
        if ($womensSection && $womensCategory) {
            $items = [
                ['title' => 'Ethnic Wear',   'image' => 'storage/section/items/womens-ethnic.jpg'],
                ['title' => 'Western Wear',  'image' => 'storage/section/items/womens-western.jpg'],
                ['title' => 'Accessories',   'image' => 'storage/section/items/womens-acc.jpg'],
            ];
            $this->syncItems($womensSection, $womensCategory, $items, $admin->id);
        }

        // ── Home Section Items for Kids Fashion ───────────────────────
        if ($kidsSection && $kidsCategory) {
            $items = [
                ['title' => 'Boys Wear',     'image' => 'storage/section/items/kids-boys.jpg'],
                ['title' => 'Girls Wear',    'image' => 'storage/section/items/kids-girls.jpg'],
                ['title' => 'Infants',       'image' => 'storage/section/items/kids-infant.jpg'],
            ];
            $this->syncItems($kidsSection, $kidsCategory, $items, $admin->id);
        }
    }

    private function syncItems($section, $category, $items, $adminId)
    {
        // Remove old items for this section to keep it clean
        HomeSectionItem::where('home_section_id', $section->id)->delete();

        foreach ($items as $index => $item) {
            HomeSectionItem::create([
                'home_section_id' => $section->id,
                'category_id'     => $category->id,
                'title'           => $item['title'],
                'subtitle'        => 'Explore Collection',
                'image'           => $item['image'],
                'order'           => $index + 1,
                'status'          => 'active',
                'created_by'      => $adminId,
                'updated_by'      => $adminId,
            ]);
        }
    }
}
