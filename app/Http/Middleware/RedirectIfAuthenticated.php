<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        // Example: if already logged in, redirect away from login/register
        if ($request->session()->has('user_id')) {
            return redirect()->route('dashboard'); // adjust to your dashboard
        }

        return $next($request);
    }
}
