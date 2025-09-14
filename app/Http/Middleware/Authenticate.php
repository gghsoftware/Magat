<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            // Admin area goes to admin login
            if ($request->routeIs('admin.*') || $request->is('admin/*')) {
                return route('admin.login');
            }
            // Frontend login (this matches your blade path view('frontend.auth.login'))
            return route('frontend.login');
        }
        return null;
    }
}
