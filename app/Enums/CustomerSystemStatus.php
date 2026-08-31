<?php

declare(strict_types=1);

namespace App\Enums;

enum CustomerSystemStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Overdue = 'overdue';
    case Suspended = 'suspended';
    case Cancelled = 'cancelled';
    case Demo = 'demo';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Active => 'Activo',
            self::Overdue => 'Moroso',
            self::Suspended => 'Suspendido',
            self::Cancelled => 'Cancelado',
            self::Demo => 'Demo',
        };
    }
}
