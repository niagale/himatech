<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class FinanceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $role = auth()->user()->role;
        
        // Admin a tous les droits, Finance aussi
        if (!in_array($role, ['admin', 'finance'])) {
            abort(403, 'Accès non autorisé. Cette action est réservée au service Finance.');
        }

        return $next($request);
    }
}