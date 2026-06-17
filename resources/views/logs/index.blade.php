@extends('layouts.app')
@section('title', 'Historique des actions')

@section('content')
<!-- Animation de bienvenue -->
<div x-data="{ showWelcome: true }" x-show="showWelcome" x-init="setTimeout(() => showWelcome = false, 4000)" 
     class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-gray-700 to-gray-900 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3"
     style="animation: slideInRight 0.4s ease-out forwards;">
    <i class="fas fa-history text-xl animate-spin-slow"></i>
    <div>
        <p class="font-semibold text-sm">Historique des actions</p>
        <p class="text-xs text-gray-300">Traçabilité complète</p>
    </div>
</div>

<div class="mb-8 animate-slideIn">
    <div>
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Historique des actions</h2>
        <p class="text-gray-500 mt-1 flex items-center gap-2">
            <i class="fas fa-history text-gray-500 text-sm"></i>
            Traçabilité complète des modifications
        </p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 animate-fadeInUp">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date/Heure</th>
                    <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Utilisateur</th>
                    <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                    <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Table</th>
                    <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Détails</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-transparent transition-all duration-200 group">
                    <td class="px-5 py-4">
                        <div class="flex flex-col">
                            <span class="text-gray-700 font-medium">{{ $log->created_at->format('d/m/Y') }}</span>
                            <span class="text-xs text-gray-400">{{ $log->created_at->format('H:i:s') }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center shadow-sm" style="background: linear-gradient(135deg, 
                                {{ $log->user->role == 'admin' ? '#8b5cf6,#6366f1' : 
                                   ($log->user->role == 'achat' ? '#3b82f6,#06b6d4' : 
                                   ($log->user->role == 'finance' ? '#10b981,#059669' : '#f59e0b,#d97706')) }});">
                                <i class="fas fa-user text-white text-[10px]"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $log->user->name ?? 'Inconnu' }}</p>
                                <p class="text-xs text-gray-400 capitalize">{{ $log->user->role ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        @php
                            $actionConfig = [
                                'create' => ['bg-emerald-100', 'text-emerald-700', 'fa-plus-circle', 'Création'],
                                'update' => ['bg-amber-100', 'text-amber-700', 'fa-pen', 'Modification'],
                                'delete' => ['bg-red-100', 'text-red-700', 'fa-trash', 'Suppression'],
                                'login' => ['bg-blue-100', 'text-blue-700', 'fa-sign-in-alt', 'Connexion'],
                                'logout' => ['bg-gray-100', 'text-gray-600', 'fa-sign-out-alt', 'Déconnexion']
                            ];
                            $config = $actionConfig[$log->action] ?? ['bg-gray-100', 'text-gray-600', 'fa-circle', $log->action];
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg text-xs font-semibold {{ $config[0] }} {{ $config[1] }}">
                            <i class="fas {{ $config[2] }} text-xs"></i>
                            {{ $config[3] }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-mono bg-gray-100 text-gray-600">
                            <i class="fas fa-database text-[10px]"></i>
                            {{ $log->table_name }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        @if($log->action == 'create' && $log->new_values)
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-emerald-100 text-emerald-700">
                                    <i class="fas fa-plus-circle text-[10px]"></i>
                                    ID: {{ $log->record_id }}
                                </span>
                            </div>
                        @elseif($log->action == 'update' && $log->new_values)
                            <details class="group">
                                <summary class="cursor-pointer inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-amber-100 text-amber-700 hover:bg-amber-200 transition">
                                    <i class="fas fa-edit text-[10px]"></i>
                                    {{ count($log->new_values) }} modification(s)
                                    <i class="fas fa-chevron-down text-[8px] group-open:rotate-180 transition"></i>
                                </summary>
                                <div class="mt-2 p-3 bg-gray-50 rounded-lg border border-gray-100 space-y-1">
                                    @foreach($log->new_values as $key => $value)
                                        @if($key != 'updated_at' && $key != 'created_at')
                                        <div class="text-xs flex items-start gap-2">
                                            <span class="font-mono font-bold text-gray-700 min-w-[100px]">{{ $key }}</span>
                                            <span class="text-red-500 line-through">{{ json_encode($log->old_values[$key] ?? 'null') }}</span>
                                            <i class="fas fa-arrow-right text-gray-400 text-[10px] mt-0.5"></i>
                                            <span class="text-green-600">{{ json_encode($value) }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </details>
                        @elseif($log->action == 'delete')
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">
                                    <i class="fas fa-trash text-[10px]"></i>
                                    ID: {{ $log->record_id }}
                                </span>
                            </div>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-history text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Aucune action enregistrée</p>
                            <p class="text-xs text-gray-400">Les actions des utilisateurs apparaîtront ici</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $logs->links() }}
    </div>
</div>

<style>
    @keyframes slideInRight {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }
    @keyframes spin-slow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    .animate-spin-slow {
        animation: spin-slow 2s linear infinite;
    }
    .animate-slideIn {
        animation: slideInRight 0.4s ease-out forwards;
    }
</style>
@endsection