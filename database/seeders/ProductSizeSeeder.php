<?php

namespace Database\Seeders;

use App\Models\ProductSize;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ProductSizeSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->firstOrFail();

        $sizes = [
            // Clothing sizes
            ['name' => 'XS',   'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'S',    'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'M',    'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'L',    'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'XL',   'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'XXL',  'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'XXXL', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            // Shoe sizes (EU)
            ['name' => '38',   'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => '39',   'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => '40',   'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => '41',   'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => '42',   'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => '43',   'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => '44',   'created_by' => $admin->id, 'updated_by' => $admin->id],
            // Free size
            ['name' => 'Free Size', 'created_by' => $admin->id, 'updated_by' => $admin->id],
        ];

        ProductSize::upsert($sizes, ['name'], ['created_by', 'updated_by']);
    }
}