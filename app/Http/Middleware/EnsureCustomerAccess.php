<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class EnsureCustomerAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        Gate::authorize('access-customer');

        return $next($request);
    }
}
