<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role_id', Role::where('slug', 'admin')->first()?->id)->first();
        $adminId = $admin ? $admin->id : null;

        $settings = [
            // General Site Settings
            [
                'key' => 'site_name',
                'value' => 'ZENO',
                'group' => 'general'
            ],
            [
                'key' => 'site_title',
                'value' => 'Zeno - Premium Fashion & Lifestyle',
                'group' => 'general'
            ],
            [
                'key' => 'site_email',
                'value' => 'support@zeno.com.bd',
                'group' => 'contact'
            ],
            [
                'key' => 'site_phone',
                'value' => '+880 1700-000000',
                'group' => 'contact'
            ],
            [
                'key' => 'site_address',
                'value' => 'House 12, Road 5, Dhanmondi, Dhaka-1205, Bangladesh',
                'group' => 'contact'
            ],
            [
                'key' => 'site_working_hours',
                'value' => 'Sun – Thu (10AM–6PM)',
                'group' => 'contact'
            ],
            // Social Media
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com/zenoshop',
                'group' => 'social'
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com/zenoshop',
                'group' => 'social'
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com/zenoshop',
                'group' => 'social'
            ],
            // Appearance
            [
                'key' => 'primary_color',
                'value' => '#000000',
                'group' => 'appearance'
            ],
            [
                'key' => 'secondary_color',
                'value' => '#ffffff',
                'group' => 'appearance'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ])
            );
            
            // Clear cache for each setting
            Cache::forget("setting_{$setting['key']}");
        }
    }
}
