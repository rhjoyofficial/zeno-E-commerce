<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\MegaMenuContent;
use App\Models\NavigationMenu;
use App\Models\NavigationMenuItem;
use Illuminate\Database\Seeder;

class NavigationMenuSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ["Men's Clothing", 'mens-clothing', 1],
            ["Women's Clothing", 'womens-clothing', 2],
            ['Kids Clothing', 'kids-clothing', 3],
            ['New Arrivals', 'new-arrivals', 4],
        ] as [$name, $slug, $position]) {
            $category = Category::where('category_name', $name)->first();

            $menu = NavigationMenu::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'slug' => $slug,
                    'position' => $position,
                    'status' => 'active',
                    'is_mega_menu' => (bool) $category,
                    'mega_menu_type' => $category ? 'categories' : null,
                    'mega_menu_content' => $category ? ['category_id' => $category->id] : null,
                ]
            );

            if ($category) {
                $category->children()->orderBy('category_name')->get()->each(function (Category $child, int $index) use ($menu) {
                    NavigationMenuItem::updateOrCreate(
                        ['navigation_menu_id' => $menu->id, 'category_id' => $child->id, 'parent_id' => null],
                        [
                            'navigation_menu_id' => $menu->id,
                            'parent_id' => null,
                            'title' => $child->category_name,
                            'url' => '/products?category=' . $child->id,
                            'route' => null,
                            'category_id' => $child->id,
                            'brand_id' => null,
                            'icon' => null,
                            'order' => $index + 1,
                            'is_featured' => $index < 2,
                            'featured_image' => $child->category_image,
                            'description' => 'Shop ' . strtolower($child->category_name),
                            'status' => 'active',
                        ]
                    );
                });

                MegaMenuContent::updateOrCreate(
                    ['navigation_menu_id' => $menu->id, 'type' => 'categories'],
                    [
                        'navigation_menu_id' => $menu->id,
                        'type' => 'categories',
                        'title' => $name . ' Categories',
                        'content' => ['category_id' => $category->id],
                        'columns' => 3,
                        'order' => 1,
                        'is_active' => true,
                    ]
                );
            } else {
                NavigationMenuItem::updateOrCreate(
                    ['navigation_menu_id' => $menu->id, 'title' => 'Latest Products'],
                    [
                        'navigation_menu_id' => $menu->id,
                        'parent_id' => null,
                        'title' => 'Latest Products',
                        'url' => '/products?sort=new',
                        'route' => null,
                        'category_id' => null,
                        'brand_id' => null,
                        'icon' => null,
                        'order' => 1,
                        'is_featured' => true,
                        'featured_image' => null,
                        'description' => 'Fresh arrivals',
                        'status' => 'active',
                    ]
                );
            }
        }
    }
}
