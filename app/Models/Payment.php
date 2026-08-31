<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PaymentStatus;
use App\Observers\PaymentObserver;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'customer_id', 'payment_number', 'payment_method', 'amount', 'currency',
    'status', 'paid_at', 'provider', 'provider_payment_id', 'provider_preference_id',
    'provider_external_reference', 'raw_response', 'notes', 'created_by',
])]
#[ObservedBy(PaymentObserver::class)]
class Payment extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'status' => PaymentStatus::class,
            'paid_at' => 'datetime',
            'raw_response' => 'array',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function installments(): BelongsToMany
    {
        return $this->belongsToMany(Installment::class, 'payment_installments')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function paymentInstallments(): HasMany
    {
        return $this->hasMany(PaymentInstallment::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function isApproved(): bool
    {
        return $this->status === PaymentStatus::Approved;
    }
}
