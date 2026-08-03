<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsPublisher
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && in_array(auth()->user()->user_type, ['publisher', 'admin'])) {
            return $next($request);
        }

        abort(403, 'Access denied. Publisher account required.');
    }
}
