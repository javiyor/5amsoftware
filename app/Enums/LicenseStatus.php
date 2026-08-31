<?php

declare(strict_types=1);

namespace App\Enums;

enum LicenseStatus: string
{
    case Pending = 'pending';
    case Active = 'active';
    case Suspended = 'suspended';
    case Revoked = 'revoked';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pendiente',
            self::Active => 'Activa',
            self::Suspended => 'Suspendida',
            self::Revoked => 'Revocada',
            self::Expired => 'Expirada',
        };
    }
}
