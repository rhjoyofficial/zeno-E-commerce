<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            ['key' => 'site_name', 'value' => 'Zeno Fashion', 'group' => 'general'],
            ['key' => 'default_currency', 'value' => 'BDT', 'group' => 'commerce'],
            ['key' => 'free_shipping_minimum', 'value' => '5000', 'group' => 'commerce'],
            ['key' => 'support_phone', 'value' => '+8801710000000', 'group' => 'support'],
            ['key' => 'support_email', 'value' => 'care@zenofashion.test', 'group' => 'support'],
        ] as $setting) {
            Setting::set($setting['key'], $setting['value'], $setting['group']);
        }
    }
}
