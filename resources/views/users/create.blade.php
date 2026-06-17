@extends('layouts.app')
@section('title', 'Nouvel utilisateur')

@section('content')
<div class="mb-8 animate-slideIn">
    <div>
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Nouvel utilisateur</h2>
        <p class="text-gray-500 mt-1 flex items-center gap-2">
            <i class="fas fa-user-plus text-indigo-500 text-sm"></i>
            Créer un compte utilisateur
        </p>
    </div>
</div>

<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
        <form method="POST" action="{{ route('users.store') }}" class="p-8 space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user text-indigo-500 mr-1"></i> Nom complet *
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-envelope text-indigo-500 mr-1"></i> Email *
                </label>
                <input type="email" name="email" value="{{ old('email') }}" required 
                    class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-shield-alt text-indigo-500 mr-1"></i> Rôle *
                </label>
                <select name="role" required class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    <option value="achat" {{ old('role') == 'achat' ? 'selected' : '' }}>🛒 Service Achat</option>
                    <option value="finance" {{ old('role') == 'finance' ? 'selected' : '' }}>💰 Service Finance</option>
                    <option value="direction" {{ old('role') == 'direction' ? 'selected' : '' }}>🏢 Direction</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>👑 Administrateur</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock text-indigo-500 mr-1"></i> Mot de passe *
                    </label>
                    <input type="password" name="password" required 
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                    @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-check-circle text-indigo-500 mr-1"></i> Confirmation *
                    </label>
                    <input type="password" name="password_confirmation" required 
                        class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200">
                </div>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-3 rounded-xl font-medium hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 shadow-md flex items-center gap-2">
                    <i class="fas fa-save"></i> Créer l'utilisateur
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