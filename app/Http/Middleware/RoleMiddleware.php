<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (Auth::guard($role)->check() && Auth::guard($role)->user()->role === ucfirst($role)) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Unauthorized access.');
    }
}