<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user() || !auth()->user()->isAdmin()) {
                abort(403, 'Accès non autorisé. Réservé aux administrateurs.');
            }
            return $next($request);
        })->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:admin,achat,finance,direction',
        ]);

        try {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => $request->role,
                'actif'    => true,
            ]);

            if ($user) {
                return redirect()->route('users.index')
                    ->with('success', 'Utilisateur "' . $user->name . '" créé avec succès.');
            } else {
                return back()->with('error', 'Erreur lors de la création de l\'utilisateur.');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,achat,finance,direction',
        ]);

        $user->update($request->only(['name', 'email', 'role']));

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur "' . $user->name . '" mis à jour.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        return redirect()->route('users.index')
            ->with('success', 'Utilisateur "' . $user->name . '" supprimé.');
    }
}