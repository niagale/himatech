@extends('layouts.app')
@section('title', 'Utilisateurs')

@section('content')
<!-- Animation de bienvenue -->
<div x-data="{ showWelcome: true }" x-show="showWelcome" x-init="setTimeout(() => showWelcome = false, 4000)" 
     class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-purple-600 to-pink-600 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3"
     style="animation: slideInRight 0.4s ease-out forwards;">
    <i class="fas fa-users text-xl animate-bounce"></i>
    <div>
        <p class="font-semibold text-sm">Gestion des utilisateurs</p>
        <p class="text-xs text-purple-200">Contrôle d'accès et permissions</p>
    </div>
</div>

<div class="mb-8 animate-slideIn">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Utilisateurs</h2>
            <p class="text-gray-500 mt-1 flex items-center gap-2">
                <i class="fas fa-shield-alt text-purple-500 text-sm"></i>
                Gestion des accès et permissions
            </p>
        </div>
        <a href="{{ route('users.create') }}" 
           class="group bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-5 py-2.5 rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
            <i class="fas fa-plus text-sm group-hover:rotate-90 transition-transform duration-300"></i>
            <span class="text-sm font-medium">Nouvel utilisateur</span>
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 animate-fadeInUp">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Utilisateur</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Rôle</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($users as $u)
                <tr class="hover:bg-gradient-to-r hover:from-purple-50/50 hover:to-transparent transition-all duration-200 group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md" style="background: linear-gradient(135deg, 
                                {{ $u->role == 'admin' ? '#8b5cf6,#6366f1' : 
                                   ($u->role == 'achat' ? '#3b82f6,#06b6d4' : 
                                   ($u->role == 'finance' ? '#10b981,#059669' : '#f59e0b,#d97706')) }});">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $u->name }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">ID: #{{ $u->id }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-envelope text-xs text-gray-400"></i>
                            <span class="text-gray-600">{{ $u->email }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $roleConfig = [
                                'admin' => ['bg-gradient-to-r from-purple-100 to-purple-50', 'text-purple-700', 'fa-crown'],
                                'achat' => ['bg-gradient-to-r from-blue-100 to-blue-50', 'text-blue-700', 'fa-shopping-cart'],
                                'finance' => ['bg-gradient-to-r from-emerald-100 to-emerald-50', 'text-emerald-700', 'fa-chart-line'],
                                'direction' => ['bg-gradient-to-r from-orange-100 to-orange-50', 'text-orange-700', 'fa-building']
                            ];
                            $config = $roleConfig[$u->role] ?? [$roleConfig['achat']];
                        @endphp
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-semibold {{ $config[0] }} {{ $config[1] }}">
                            <i class="fas {{ $config[2] }} text-xs"></i>
                            {{ ucfirst($u->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($u->actif)
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                <i class="fas fa-circle text-[8px]"></i>
                                Actif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                <i class="fas fa-circle text-[8px]"></i>
                                Inactif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('users.edit', $u) }}" 
                               class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all duration-200 flex items-center justify-center group-hover:scale-110">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('users.destroy', $u) }}" class="inline" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all duration-200 flex items-center justify-center group-hover:scale-110">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $users->links() }}
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