<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveUser
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user() && (int) $request->user()->status === 1 && $request->user()->deleted_at === null, 403);

        return $next($request);
    }
}
