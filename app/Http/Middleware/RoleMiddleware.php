<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (empty($roles)) {
            return $next($request);
        }

        $userRole = auth()->user()->role;

        if (!in_array($userRole, $roles)) {
            abort(403, 'Accès non autorisé. Rôle requis : ' . implode(', ', $roles));
        }

        return $next($request);
    }
}