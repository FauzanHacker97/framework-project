<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NotAdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized');
        }

        if (auth()->user()->isAdmin()) {
            abort(403, 'Admins are not allowed to manage collections');
        }

        return $next($request);
    }
}
