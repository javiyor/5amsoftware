<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'name', 'slug', 'short_description', 'description', 'main_image',
    'price_from', 'installation_price', 'is_featured', 'is_published',
    'sort_order', 'seo_title', 'seo_description',
])]
class System extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'price_from' => 'decimal:2',
            'installation_price' => 'decimal:2',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function features(): HasMany
    {
        return $this->hasMany(SystemFeature::class)->orderBy('sort_order');
    }

    public function plans(): HasMany
    {
        return $this->hasMany(SystemPlan::class)->orderBy('sort_order');
    }

    public function customerSystems(): HasMany
    {
        return $this->hasMany(CustomerSystem::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
