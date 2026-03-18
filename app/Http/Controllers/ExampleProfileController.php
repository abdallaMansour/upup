<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleProfileController extends Controller
{
    private const EXAMPLE_PIN = '123456';

    private function handleExampleStage(Request $request, string $stage)
    {
        $stage = strtolower($stage);
        if (! in_array($stage, ['child', 'teenager', 'adults'])) {
            abort(404);
        }

        if ($request->isMethod('post')) {
            $request->validate(['pin' => ['required', 'string', 'size:6']]);

            if ($request->pin !== self::EXAMPLE_PIN) {
                return redirect()->route("profile.example.{$stage}")
                    ->with('error', __('profile.pin_error'));
            }

            return match ($stage) {
                'child' => view('profile_pages.example.child'),
                'teenager' => view('profile_pages.example.teenager'),
                'adults' => view('profile_pages.example.adults'),
                default => abort(404),
            };
        }

        return view('profile_pages.example.pin-login', compact('stage'));
    }

    public function child(Request $request)
    {
        return $this->handleExampleStage($request, 'child');
    }

    public function teenager(Request $request)
    {
        return $this->handleExampleStage($request, 'teenager');
    }

    public function adults(Request $request)
    {
        return $this->handleExampleStage($request, 'adults');
    }
}
