<?php

declare(strict_types=1);

namespace App\Services\Support;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class AuditService
{
    public function log(
        string $action,
        ?string $entityType = null,
        ?int $entityId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?User $user = null,
    ): void {
        $user ??= Auth::user();

        DB::table('audit_logs')->insert([
            'user_id' => $user?->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues === null ? null : json_encode($oldValues),
            'new_values' => $newValues === null ? null : json_encode($newValues),
            'ip' => Request::ip(),
            'user_agent' => substr((string) Request::userAgent(), 0, 255) ?: null,
            'created_at' => now(),
        ]);
    }

    public function model(string $action, object $model, ?array $oldValues = null, ?array $newValues = null, ?User $user = null): void
    {
        $this->log(
            $action,
            $model::class,
            method_exists($model, 'getKey') ? $model->getKey() : null,
            $oldValues,
            $newValues,
            $user,
        );
    }
}
