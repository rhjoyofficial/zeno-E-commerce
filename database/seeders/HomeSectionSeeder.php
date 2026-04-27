<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\HomeSection;
use App\Models\HomeSectionItem;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'type' => 'new_arrivals',
                'title' => 'New Arrivals',
                'subtitle' => 'Fresh western fashion picks in BDT, selected for Bangladesh weather and daily life.',
                'category' => null,
                'order' => 1,
            ],
            [
                'type' => 'mens_fashion',
                'title' => "Men's Clothing",
                'subtitle' => 'Sharp shirts, denim, chinos, and easy layers.',
                'section_title' => 'Smart Western Staples',
                'section_subtitle' => 'For office, campus, travel, and weekends',
                'banner_image' => 'storage/home-sections/mens-clothing.jpg',
                'category' => "Men's Clothing",
                'order' => 2,
            ],
            [
                'type' => 'womens_fashion',
                'title' => "Women's Clothing",
                'subtitle' => 'Dresses, blouses, palazzos, denim, and soft layering.',
                'section_title' => 'Modern Everyday Style',
                'section_subtitle' => 'Western looks with comfortable modest options',
                'banner_image' => 'storage/home-sections/womens-clothing.jpg',
                'category' => "Women's Clothing",
                'order' => 3,
            ],
            [
                'type' => 'kids_fashion',
                'title' => 'Kids Clothing',
                'subtitle' => 'Play-ready western outfits for school, weekends, and parties.',
                'section_title' => 'Comfort First Kidswear',
                'section_subtitle' => 'Easy outfits parents can trust',
                'banner_image' => 'storage/home-sections/kids-clothing.jpg',
                'category' => 'Kids Clothing',
                'order' => 4,
            ],
        ];

        foreach ($sections as $sectionData) {
            $category = $sectionData['category'] ? Category::where('category_name', $sectionData['category'])->first() : null;

            $section = HomeSection::updateOrCreate(
                ['type' => $sectionData['type']],
                [
                    'type' => $sectionData['type'],
                    'title' => $sectionData['title'],
                    'subtitle' => $sectionData['subtitle'],
                    'section_title' => $sectionData['section_title'] ?? null,
                    'section_subtitle' => $sectionData['section_subtitle'] ?? null,
                    'banner_image' => $sectionData['banner_image'] ?? null,
                    'category_id' => $category?->id,
                    'status' => 'active',
                    'order' => $sectionData['order'],
                ]
            );

            if ($category) {
                $this->items($section, $category);
            }
        }
    }

    private function items(HomeSection $section, Category $mainCategory): void
    {
        $mainCategory->children()->orderBy('category_name')->get()->each(function (Category $category, int $index) use ($section) {
            HomeSectionItem::updateOrCreate(
                ['home_section_id' => $section->id, 'category_id' => $category->id],
                [
                    'home_section_id' => $section->id,
                    'category_id' => $category->id,
                    'title' => $category->category_name,
                    'subtitle' => 'Shop curated ' . strtolower($category->category_name),
                    'image' => 'storage/home-sections/items/' . str($category->category_name)->slug() . '.jpg',
                    'order' => $index + 1,
                    'status' => 'active',
                ]
            );
        });
    }
}
