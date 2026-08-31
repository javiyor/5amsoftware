<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\InstallmentStatus;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'customer_system_id', 'customer_id', 'number', 'period_start', 'period_end',
    'issued_at', 'due_at', 'subtotal', 'discount_amount', 'interest_amount',
    'total', 'status', 'paid_amount', 'paid_at', 'notes',
])]
class Installment extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'number' => 'integer',
            'period_start' => 'date',
            'period_end' => 'date',
            'issued_at' => 'datetime',
            'due_at' => 'datetime',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'interest_amount' => 'decimal:2',
            'total' => 'decimal:2',
            'status' => InstallmentStatus::class,
            'paid_amount' => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function customerSystem(): BelongsTo
    {
        return $this->belongsTo(CustomerSystem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments(): BelongsToMany
    {
        return $this->belongsToMany(Payment::class, 'payment_installments')
            ->withPivot('amount')
            ->withTimestamps();
    }

    public function remaining(): string
    {
        return bcsub((string) $this->total, (string) $this->paid_amount, 2);
    }

    public function isSettled(): bool
    {
        return $this->status->isSettled();
    }

    public function isFullyPaid(): bool
    {
        return $this->isSettled()
            && bccomp((string) $this->paid_amount, (string) $this->total, 2) >= 0;
    }

    public function isOverdue(?CarbonInterface $now = null): bool
    {
        return $this->daysOverdue($now) > 0;
    }

    /**
     * Días de atraso según la regla centralizada de facturación:
     * el primer día completo posterior a la fecha de vencimiento suma 1.
     */
    public function daysOverdue(?CarbonInterface $now = null): int
    {
        if ($this->due_at === null) {
            return 0;
        }

        $now = ($now ?? now())->copy()->startOfDay();

        return max(0, -(int) $now->diffInDays($this->due_at->copy()->startOfDay(), false));
    }
}
