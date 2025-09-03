<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(Request $request, Closure $next): Response
    {
        // Example: check if user is logged in
        if (!$request->session()->has('user_id')) {
            return redirect()->route('admin_login'); // adjust to your login route
        }

        return $next($request);
    }
}
