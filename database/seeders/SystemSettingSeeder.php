<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['setting_key' => 'company_name', 'setting_value' => 'Enterprise HR Solutions'],
            ['setting_key' => 'currency', 'setting_value' => 'USD'],
            ['setting_key' => 'grace_period_default', 'setting_value' => '15'],
            ['setting_key' => 'overtime_rate', 'setting_value' => '1.5'],
        ];

        foreach ($settings as $setting) {
            SystemSetting::firstOrCreate(
                ['setting_key' => $setting['setting_key']],
                $setting
            );
        }
    }
}