<?php

declare(strict_types=1);

namespace App\Enums;

enum CustomerStatus: string
{
    case Lead = 'lead';
    case Active = 'active';
    case Suspended = 'suspended';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match ($this) {
            self::Lead => 'Prospecto',
            self::Active => 'Activo',
            self::Suspended => 'Suspendido',
            self::Inactive => 'Inactivo',
        };
    }
}
