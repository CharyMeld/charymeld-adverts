<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get available locales as array of keys
        $availableLocales = array_keys(config('app.available_locales', ['en' => []]));

        // Check if language parameter is in URL
        if ($request->has('lang')) {
            $locale = $request->input('lang');

            // Validate locale
            if (in_array($locale, $availableLocales)) {
                Session::put('locale', $locale);
                App::setLocale($locale);
            }
        }
        // Check session for saved locale
        elseif (Session::has('locale')) {
            $locale = Session::get('locale');

            if (in_array($locale, $availableLocales)) {
                App::setLocale($locale);
            }
        }
        // Check browser Accept-Language header
        else {
            $locale = $request->getPreferredLanguage($availableLocales);
            if ($locale && in_array($locale, $availableLocales)) {
                App::setLocale($locale);
            }
        }

        return $next($request);
    }
}
