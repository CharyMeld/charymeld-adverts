<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force HTTPS if enabled in config and not already secure
        if (!$request->secure() && config('app.force_https', false)) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
