<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, $role)
    {
        // Ensure the user is logged in
        if (! Auth::check()) {
            return redirect()->route('401.page');
        }

        // Check role (assuming you have a `role` column on users table)
        if (Auth::user()->role !== $role) {
            return redirect()->route('403.page');
        }

        return $next($request);
    }
}
