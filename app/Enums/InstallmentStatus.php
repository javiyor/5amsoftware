<?php

declare(strict_types=1);

namespace App\Enums;

enum InstallmentStatus: string
{
    case Pending = 'pending';
    case Overdue = 'overdue';
    case Paid = 'paid';
    case Cancelled = 'cancelled';
    case Waived = 'waived';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Overdue => 'Vencida',
            self::Paid => 'Pagada',
            self::Cancelled => 'Anulada',
            self::Waived => 'Condonada',
        };
    }

    public function isSettled(): bool
    {
        return in_array($this, [self::Paid, self::Cancelled, self::Waived], true);
    }
}
