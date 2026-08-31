<?php

declare(strict_types=1);

namespace App\Enums;

enum DemoRequestStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Scheduled = 'scheduled';
    case Completed = 'completed';
    case Interested = 'interested';
    case Converted = 'converted';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::New => 'Nueva',
            self::Contacted => 'Contactada',
            self::Scheduled => 'Programada',
            self::Completed => 'Completada',
            self::Interested => 'Interesada',
            self::Converted => 'Convertida',
            self::Rejected => 'Rechazada',
        };
    }
}
