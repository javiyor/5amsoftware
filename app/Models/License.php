<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\LicenseStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'customer_system_id', 'license_key', 'api_token_hash', 'status',
    'activated_at', 'expires_at', 'last_check_at', 'last_ip', 'domain',
    'installation_id', 'metadata',
])]
class License extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => LicenseStatus::class,
            'activated_at' => 'datetime',
            'expires_at' => 'datetime',
            'last_check_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function customerSystem(): BelongsTo
    {
        return $this->belongsTo(CustomerSystem::class);
    }
}
