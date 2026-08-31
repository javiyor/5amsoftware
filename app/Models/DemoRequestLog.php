<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['demo_request_id', 'user_id', 'old_status', 'new_status', 'comment'])]
class DemoRequestLog extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    public function demoRequest(): BelongsTo
    {
        return $this->belongsTo(DemoRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
