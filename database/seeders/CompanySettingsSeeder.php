<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySettingsSeeder extends Seeder
{
    private const VALUES = [
        'identity' => [
            'company_name' => '5AM Software',
            'company_tax_name' => '5AM Software S.R.L.',
            'company_cuit' => '',
            'company_address' => '',
            'company_city' => '',
            'company_state' => '',
            'company_country' => 'Argentina',
            'company_postal_code' => '',
            'company_phone' => '',
            'company_whatsapp' => '',
            'company_email' => '',
            'company_website' => '',
        ],
        'branding' => [
            'company_logo' => 'company/logo.jpeg',
            'company_logo_dark' => 'company/logo.jpeg',
            'company_favicon' => 'company/logo.jpeg',
            'brand_primary_color' => '#0f172a',
            'brand_secondary_color' => '#f59e0b',
            'company_footer_text' => 'Software que simplifica la gestión de tu empresa.',
        ],
        'social' => [
            'company_instagram' => '',
            'company_facebook' => '',
            'company_linkedin' => '',
        ],
        'billing' => [
            'company_bank_data' => '',
        ],
        'legal' => [
            'company_terms' => '',
            'company_privacy_policy' => '',
        ],
    ];

    public function run(): void
    {
        foreach (self::VALUES as $group => $settings) {
            foreach ($settings as $key => $value) {
                DB::table('company_settings')->updateOrInsert(
                    ['key' => $key],
                    ['value' => json_encode($value), 'group' => $group, 'updated_at' => now()],
                );
            }
        }
    }
}
