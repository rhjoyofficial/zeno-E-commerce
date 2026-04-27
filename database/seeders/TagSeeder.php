<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['new-arrival', 'office-wear', 'weekend', 'summer', 'modest-fit', 'premium', 'denim', 'kids-favorite', 'eid-ready', 'travel', 'school-casual'] as $name) {
            Tag::updateOrCreate(['name' => $name], ['name' => $name]);
        }
    }
}
