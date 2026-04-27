<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->firstOrFail();

        $colors = [
            ['name' => 'Black',       'hex_code' => '#000000', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'White',       'hex_code' => '#FFFFFF', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Navy Blue',   'hex_code' => '#1E3A8A', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Red',         'hex_code' => '#EF4444', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Green',       'hex_code' => '#10B981', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Grey',        'hex_code' => '#6B7280', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Maroon',      'hex_code' => '#800000', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Olive',       'hex_code' => '#808000', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Sky Blue',    'hex_code' => '#87CEEB', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Pink',        'hex_code' => '#EC4899', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Yellow',      'hex_code' => '#F59E0B', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Purple',      'hex_code' => '#8B5CF6', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Orange',      'hex_code' => '#F97316', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Beige',       'hex_code' => '#F5F5DC', 'created_by' => $admin->id, 'updated_by' => $admin->id],
            ['name' => 'Teal',        'hex_code' => '#0D9488', 'created_by' => $admin->id, 'updated_by' => $admin->id],
        ];

        Color::upsert($colors, ['name'], ['hex_code', 'created_by', 'updated_by']);
    }
}