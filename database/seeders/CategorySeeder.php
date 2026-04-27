<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->firstOrFail();

        // ── Parent Categories ──────────────────────────────────────────
        $electronics = $this->createCategory('Electronics', 'storage/categories/electronics.jpg', $admin->id);
        $clothing    = $this->createCategory('Clothing',    'storage/categories/clothing.jpg',    $admin->id);
        $footwear    = $this->createCategory('Footwear',    'storage/categories/footwear.jpg',    $admin->id);
        $accessories = $this->createCategory('Accessories', 'storage/categories/accessories.jpg', $admin->id);

        // ── Men's Clothing Sub-categories (5 categories) ──────────────
        $mensClothing = $this->createCategory("Men's Clothing", 'storage/categories/mens_clothing.jpg', $admin->id, $clothing->id);
        $this->createCategory('Men T-Shirts', 'storage/categories/mens_tshirts.jpg', $admin->id, $mensClothing->id);
        $this->createCategory('Men Shirts',   'storage/categories/mens_shirts.jpg',  $admin->id, $mensClothing->id);
        $this->createCategory('Men Pants',    'storage/categories/mens_pants.jpg',   $admin->id, $mensClothing->id);
        $this->createCategory('Men Hoodies',  'storage/categories/mens_hoodies.jpg', $admin->id, $mensClothing->id);
        $this->createCategory('Men Blazers',  'storage/categories/mens_blazers.jpg', $admin->id, $mensClothing->id);

        // ── Women's Clothing Sub-categories (5 categories) ────────────
        $womensClothing = $this->createCategory("Women's Clothing", 'storage/categories/womens_clothing.jpg', $admin->id, $clothing->id);
        $this->createCategory('Women Tops',   'storage/categories/womens_tops.jpg',   $admin->id, $womensClothing->id);
        $this->createCategory('Women Dresses','storage/categories/womens_dresses.jpg',$admin->id, $womensClothing->id);
        $this->createCategory('Women Sarees', 'storage/categories/womens_sarees.jpg', $admin->id, $womensClothing->id);
        $this->createCategory('Women Jeans',  'storage/categories/womens_jeans.jpg',  $admin->id, $womensClothing->id);
        $this->createCategory('Women Kurtas', 'storage/categories/womens_kurtas.jpg', $admin->id, $womensClothing->id);

        // ── Kids Clothing Sub-categories (5 categories) ───────────────
        $kidsClothing = $this->createCategory('Kids Clothing', 'storage/categories/kids_clothing.jpg', $admin->id, $clothing->id);
        $this->createCategory('Boys Sets',    'storage/categories/kids_boys.jpg',   $admin->id, $kidsClothing->id);
        $this->createCategory('Girls Dresses','storage/categories/kids_girls.jpg',  $admin->id, $kidsClothing->id);
        $this->createCategory('Baby Suits',   'storage/categories/kids_baby.jpg',   $admin->id, $kidsClothing->id);
        $this->createCategory('Kids Pajamas', 'storage/categories/kids_pajamas.jpg',$admin->id, $kidsClothing->id);
        $this->createCategory('Kids School',  'storage/categories/kids_school.jpg', $admin->id, $kidsClothing->id);

        // ── Others ─────────────────────────────────────────────────────
        $this->createCategory('Smartphones',  'storage/categories/smartphones.jpg',  $admin->id, $electronics->id);
        $this->createCategory('Laptops',      'storage/categories/laptops.jpg',      $admin->id, $electronics->id);
        $this->createCategory("Men's Shoes",   'storage/categories/mens_shoes.jpg',   $admin->id, $footwear->id);
        $this->createCategory("Women's Shoes", 'storage/categories/womens_shoes.jpg', $admin->id, $footwear->id);
        $this->createCategory('Bags',    'storage/categories/bags.jpg',    $admin->id, $accessories->id);
        $this->createCategory('Watches', 'storage/categories/watches.jpg', $admin->id, $accessories->id);
    }

    private function createCategory(string $name, string $image, int $userId, ?int $parentId = null): Category
    {
        return Category::firstOrCreate(
            ['category_name' => $name],
            [
                'category_image' => $image,
                'status'         => 'active',
                'parent_id'      => $parentId,
                'created_by'     => $userId,
                'updated_by'     => $userId,
            ]
        );
    }
}
