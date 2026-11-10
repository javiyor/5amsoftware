<?php

declare(strict_types=1);

namespace App\Services\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CompanySettingsService
{
    private const DEFAULTS = [
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
        'company_instagram' => '',
        'company_facebook' => '',
        'company_linkedin' => '',
        'company_logo' => '',
        'company_logo_dark' => '',
        'company_favicon' => '',
        'company_profile_photo' => '',
        'company_promo_video' => '',
        'company_promo_video_url' => '',
        'company_profession' => '',
        'brand_primary_color' => '#0d6efd',
        'brand_secondary_color' => '#6c757d',
        'company_footer_text' => '',
        'company_bank_data' => '',
        'company_terms' => '',
        'company_privacy_policy' => '',
    ];

    public function get(string $key, mixed $default = null): mixed
    {
        if (! $this->tableExists()) {
            return self::DEFAULTS[$key] ?? $default;
        }

        return Cache::remember("company_settings.{$key}", now()->addMinutes(5), function () use ($key, $default) {
            $row = DB::table('company_settings')->where('key', $key)->first();

            return $row !== null
                ? json_decode($row->value, true)
                : (self::DEFAULTS[$key] ?? $default);
        });
    }

    public function all(): array
    {
        if (! $this->tableExists()) {
            return self::DEFAULTS;
        }

        $rows = DB::table('company_settings')->get();

        return array_merge(self::DEFAULTS, $rows->mapWithKeys(
            fn ($row) => [$row->key => json_decode($row->value, true)],
        )->all());
    }

    public function set(string $key, mixed $value, string $group = 'general'): void
    {
        DB::table('company_settings')->updateOrInsert(
            ['key' => $key],
            ['value' => json_encode($value), 'group' => $group, 'updated_at' => now()],
        );

        Cache::forget("company_settings.{$key}");
    }

    private function tableExists(): bool
    {
        return Schema::hasTable('company_settings');
    }
}
