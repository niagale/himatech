<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AchatMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $role = auth()->user()->role;
        
        // Admin a tous les droits, Achat aussi
        if (!in_array($role, ['admin', 'achat'])) {
            abort(403, 'Accès non autorisé. Cette action est réservée au service Achats.');
        }

        return $next($request);
    }
}