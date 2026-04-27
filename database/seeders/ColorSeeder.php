<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['Black', '#111111'],
            ['White', '#FFFFFF'],
            ['Navy', '#1F2A44'],
            ['Sky Blue', '#8EC5E8'],
            ['Beige', '#D8C3A5'],
            ['Olive', '#6B7D3A'],
            ['Maroon', '#7A1F2B'],
            ['Denim Blue', '#315C8A'],
            ['Dusty Pink', '#D8A0A6'],
            ['Charcoal', '#3B3B3B'],
        ] as [$name, $hex]) {
            Color::updateOrCreate(['name' => $name], ['name' => $name, 'hex_code' => $hex]);
        }
    }
}
