@extends('layouts.app')
@section('title', 'Modifier utilisateur')

@section('content')
<div class="mb-8 animate-slideIn">
    <div>
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Modifier utilisateur</h2>
        <p class="text-gray-500 mt-1 flex items-center gap-2">
            <i class="fas fa-user-edit text-indigo-500 text-sm"></i>
            Modifier les informations de {{ $user->name }}
        </p>
    </div>
</div>

<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <form method="POST" action="{{ route('users.update', $user) }}" class="p-8 space-y-5">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user text-indigo-500 mr-1"></i> Nom complet *
                </label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope text-indigo-500 mr-1"></i> Email *
                </label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-shield-alt text-indigo-500 mr-1"></i> Rôle *
                </label>
                <select name="role" required class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    <option value="achat" {{ $user->role == 'achat' ? 'selected' : '' }}>🛒 Service Achat</option>
                    <option value="finance" {{ $user->role == 'finance' ? 'selected' : '' }}>💰 Service Finance</option>
                    <option value="direction" {{ $user->role == 'direction' ? 'selected' : '' }}>🏢 Direction</option>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>👑 Administrateur</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-toggle-on text-indigo-500 mr-1"></i> Statut
                </label>
                <select name="actif" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    <option value="1" {{ $user->actif ? 'selected' : '' }}>✅ Actif</option>
                    <option value="0" {{ !$user->actif ? 'selected' : '' }}>⛔ Inactif</option>
                </select>
            </div>
            
            <div class="border-t border-gray-100 pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-key text-indigo-500 mr-1"></i> Nouveau mot de passe
                </label>
                <input type="password" name="password" 
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200"
                    placeholder="Laisser vide pour ne pas changer">
                <p class="text-xs text-gray-400 mt-1">Minimum 8 caractères</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-check-circle text-indigo-500 mr-1"></i> Confirmation
                </label>
                <input type="password" name="password_confirmation" 
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-3 rounded-xl font-medium hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 shadow-md flex items-center gap-2">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="{{ route('users.index') }}" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-xl font-medium hover:bg-gray-200 transition-all duration-200 flex items-center gap-2">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    .animate-slideIn {
        animation: slideInRight 0.4s ease-out forwards;
    }
</style>
@endsection