<?php

namespace App\Http\Middleware;

use App\Http\Controllers\BaseApi;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware extends BaseApi
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
        if (! $request->user()) {
            return redirect()->route('401.page');
        }

        // Check role (assuming you have a `role` column on users table)
        if ($request->user()->role !== $role) {
            return redirect()->route('403.page');
        }

        return $next($request);
    }
}
