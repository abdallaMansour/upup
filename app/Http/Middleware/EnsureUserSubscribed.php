<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserSubscribed
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user('admin')) {
            return $next($request);
        }

        $user = $request->user('web');
        if (! $user) {
            return $next($request);
        }

        if ($request->routeIs('dashboard.packages.index')) {
            return $next($request);
        }

        if ($user->active_subscription === null) {
            return redirect()->route('dashboard.packages.index')
                ->with('error', __('my_pages.subscription_required'));
        }

        return $next($request);
    }
}
