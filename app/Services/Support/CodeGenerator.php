<?php

declare(strict_types=1);

namespace App\Services\Support;

final class CodeGenerator
{
    public static function next(string $prefix, ?int $maxId, int $padding = 6): string
    {
        $next = (int) $maxId + 1;

        return $prefix.str_pad((string) $next, $padding, '0', STR_PAD_LEFT);
    }
}
