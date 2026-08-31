<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CustomerSystemStatus;
use App\Observers\CustomerSystemObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'customer_id', 'system_id', 'system_plan_id', 'contract_number', 'custom_name',
    'started_at', 'activated_at', 'billing_started_at', 'next_billing_at',
    'billing_interval_days', 'grace_days', 'monthly_amount', 'setup_amount', 'currency',
    'automatic_billing', 'automatic_suspension', 'status', 'manual_suspension',
    'manual_suspension_reason', 'grace_override_until', 'domain', 'installation_id',
    'notes', 'cancelled_at',
])]
#[ObservedBy(CustomerSystemObserver::class)]
class CustomerSystem extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'activated_at' => 'datetime',
            'billing_started_at' => 'datetime',
            'next_billing_at' => 'datetime',
            'grace_override_until' => 'datetime',
            'cancelled_at' => 'datetime',
            'billing_interval_days' => 'integer',
            'grace_days' => 'integer',
            'automatic_billing' => 'boolean',
            'automatic_suspension' => 'boolean',
            'manual_suspension' => 'boolean',
            'status' => CustomerSystemStatus::class,
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function system(): BelongsTo
    {
        return $this->belongsTo(System::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SystemPlan::class, 'system_plan_id');
    }

    public function license(): HasOne
    {
        return $this->hasOne(License::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }
}
