<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $supportedLocales = ['ar', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('locale');

        if ($locale && in_array($locale, $this->supportedLocales, true)) {
            session(['locale' => $locale]);
            app()->setLocale($locale);

            return $next($request);
        }

        $sessionLocale = session('locale');
        if ($sessionLocale && in_array($sessionLocale, $this->supportedLocales, true)) {
            app()->setLocale($sessionLocale);
        }

        return $next($request);
    }
}
