<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                abort(403, 'Accès non autorisé. Réservé aux administrateurs.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $logs = Log::with('user')->latest()->paginate(30);
        return view('logs.index', compact('logs'));
    }
}