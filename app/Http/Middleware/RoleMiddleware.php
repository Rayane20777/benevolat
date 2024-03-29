<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        // Check if user is authenticated and has the required role
        if ($user && in_array($user->role, $roles)) {
            return $next($request);
        }

        // Unauthorized access
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}
