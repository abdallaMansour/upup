<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    protected array $supportedLocales = ['ar', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('locale');

        if ($locale && in_array($locale, $this->supportedLocales, true)) {
            session()->put('locale', $locale);
            session()->save();
            app()->setLocale($locale);

            return $next($request);
        }

        $segments = $request->segments();
        $firstSegment = $segments[0] ?? null;

        if ($firstSegment && in_array($firstSegment, $this->supportedLocales, true)) {
            session()->put('locale', $firstSegment);
            session()->save();
            app()->setLocale($firstSegment);

            $pathWithoutLocale = implode('/', array_slice($segments, 1)) ?: '/';

            return Redirect::to('/' . $pathWithoutLocale);
        }

        $sessionLocale = session('locale');
        if ($sessionLocale && in_array($sessionLocale, $this->supportedLocales, true)) {
            app()->setLocale($sessionLocale);
        }

        return $next($request);
    }
}
