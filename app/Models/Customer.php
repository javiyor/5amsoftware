<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CustomerStatus;
use App\Observers\CustomerObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id', 'code', 'first_name', 'last_name', 'company_name', 'business_name',
    'tax_id', 'email', 'phone', 'whatsapp', 'address', 'city', 'state', 'country',
    'postal_code', 'status', 'notes', 'registered_at',
])]
#[ObservedBy(CustomerObserver::class)]
class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'registered_at' => 'datetime',
            'status' => CustomerStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customerSystems(): HasMany
    {
        return $this->hasMany(CustomerSystem::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function fullName(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }
}
