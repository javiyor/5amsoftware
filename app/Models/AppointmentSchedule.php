<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['open_days', 'open_time', 'close_time', 'duration_minutes'])]
class AppointmentSchedule extends Model
{
    protected function casts(): array
    {
        return [
            'open_days' => 'array',
            'duration_minutes' => 'integer',
        ];
    }
}
