<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        $roleIdMap = [
            'admin' => 1,
            'user' => 2,
            'manager' => 3,
        ];

        foreach ($roles as $role) {
            if ($user->role_id == ($roleIdMap[$role] ?? null)) {
                return $next($request);
            }
        }

        abort(403, 'You do not have permission to access this page.');
    }
}
