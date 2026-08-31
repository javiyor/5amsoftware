<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'notifiable_type', 'notifiable_id', 'notification_type',
    'channel', 'sent_at', 'data',
])]
class NotificationLog extends Model
{
    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'sent_at' => 'datetime',
            'data' => 'array',
        ];
    }
}
