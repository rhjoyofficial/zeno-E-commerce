<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            "Men's Clothing" => ['Shirts', 'T-Shirts & Polos', 'Trousers & Chinos', "Men's Denim", 'Jackets'],
            "Women's Clothing" => ['Dresses', 'Tops & Blouses', 'Pants & Palazzos', "Women's Denim", 'Outerwear'],
            'Kids Clothing' => ['Boys Casuals', 'Girls Dresses', 'Toddler Wear', 'School Casuals', 'Party Wear'],
        ];

        foreach ($tree as $mainName => $children) {
            $main = Category::updateOrCreate(
                ['category_name' => $mainName],
                [
                    'category_name' => $mainName,
                    'category_image' => 'storage/categories/' . str($mainName)->slug() . '.jpg',
                    'parent_id' => null,
                    'status' => 'active',
                ]
            );

            foreach ($children as $childName) {
                Category::updateOrCreate(
                    ['category_name' => $childName],
                    [
                        'category_name' => $childName,
                        'category_image' => 'storage/categories/' . str($childName)->slug() . '.jpg',
                        'parent_id' => $main->id,
                        'status' => 'active',
                    ]
                );
            }
        }
    }
}
