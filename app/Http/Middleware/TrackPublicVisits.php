<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TrackPublicVisits
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $request->isMethod('GET') || $response->getStatusCode() >= 400) {
            return $response;
        }

        $routeName = $request->route()?->getName();
        $counterKeys = match ($routeName) {
            'home' => ['total', 'home'],
            'systems.show' => ['total', 'systems'],
            default => [],
        };

        if ($routeName === 'systems.show') {
            $system = $request->route('system');
            $systemId = is_object($system) && method_exists($system, 'getKey')
                ? $system->getKey()
                : $system;

            if (is_numeric($systemId)) {
                $counterKeys[] = 'system:'.$systemId;
            }
        }

        foreach ($counterKeys as $key) {
            DB::table('site_visit_counters')->insertOrIgnore([
                'key' => $key,
                'visits' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('site_visit_counters')->where('key', $key)->increment('visits', 1, ['updated_at' => now()]);
        }

        return $response;
    }
}
