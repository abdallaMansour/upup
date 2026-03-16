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
            return Redirect::back();
        }

        session(['locale' => $locale]);
        app()->setLocale($locale);

        return Redirect::back();
    }
}
