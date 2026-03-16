<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LocaleController extends Controller
{
    protected array $supportedLocales = ['ar', 'en'];

    public function switch(Request $request, string $locale): \Illuminate\Http\RedirectResponse
    {
        if (! in_array($locale, $this->supportedLocales, true)) {
            return Redirect::to('/');
        }

        session()->put('locale', $locale);
        session()->save();

        $intended = $request->query('intended');
        if ($intended) {
            $path = str_starts_with($intended, '/') ? $intended : parse_url($intended, PHP_URL_PATH);
            if ($path && str_starts_with($path, '/') && ! str_contains($path, '/locale/')) {
                return Redirect::to($path);
            }
        }

        $previous = $request->header('referer');
        if ($previous) {
            $parsed = parse_url($previous);
            $path = $parsed['path'] ?? '/';
            if (str_starts_with($path, '/') && ! str_contains($path, '/locale/')) {
                return Redirect::to($path);
            }
        }

        $user = $request->user('web') ?? $request->user('admin');

        return Redirect::to($user ? '/dashboard' : '/');
    }
}
