<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'system_id', 'name', 'slug', 'description', 'price', 'setup_price',
    'billing_interval_days', 'grace_days', 'max_users', 'is_active', 'sort_order',
])]
class SystemPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'setup_price' => 'decimal:2',
            'billing_interval_days' => 'integer',
            'grace_days' => 'integer',
            'max_users' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function customerSystems(): HasMany
    {
        return $this->hasMany(CustomerSystem::class, 'system_plan_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
