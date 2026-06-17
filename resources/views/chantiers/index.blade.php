@extends('layouts.app')
@section('title', 'Chantiers')

@section('content')
<!-- Animation de bienvenue spécifique -->
<div x-data="{ showWelcome: true }" x-show="showWelcome" x-init="setTimeout(() => showWelcome = false, 4000)" 
     class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3"
     style="animation: slideInRight 0.4s ease-out forwards;">
    <i class="fas fa-hard-hat text-xl animate-bounce"></i>
    <div>
        <p class="font-semibold text-sm">Gestion des chantiers</p>
        <p class="text-xs text-indigo-200">Suivez l'avancement de vos projets</p>
    </div>
</div>

<div class="mb-8 animate-slideIn">
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Chantiers</h2>
            <p class="text-gray-500 mt-1 flex items-center gap-2">
                <i class="fas fa-hard-hat text-indigo-500 text-sm"></i>
                Gestion et suivi des chantiers
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('chantiers.export.all.pdf') }}" 
               class="bg-red-600 text-white px-4 py-2.5 rounded-xl hover:bg-red-700 transition-all duration-200 flex items-center gap-2 shadow-md">
                <i class="fas fa-file-pdf"></i>
                <span class="text-sm font-medium">PDF</span>
            </a>
            @if(!auth()->user()->isDirection())
            <a href="{{ route('chantiers.create') }}" 
               class="group bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-5 py-2.5 rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                <i class="fas fa-plus text-sm group-hover:rotate-90 transition-transform duration-300"></i>
                <span class="text-sm font-medium">Nouveau chantier</span>
            </a>
            @endif
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 animate-fadeInUp">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Chantier</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Responsable</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Budget</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Dépenses</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Avancement</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($chantiers as $c)
                @php $pct = $c->budget > 0 ? min(round(($c->depenses_sum_montant ?? 0) / $c->budget * 100), 100) : 0; @endphp
                <tr class="hover:bg-gradient-to-r hover:from-indigo-50/50 hover:to-transparent transition-all duration-200 group">
                    <td class="px-6 py-4">
                        <span class="font-mono text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-full">{{ $c->code }}</span>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $c->nom }}</td>
                    <td class="px-6 py-4 text-gray-600">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-[10px]"></i>
                            </div>
                            {{ $c->responsable }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-right text-gray-700 font-medium">{{ number_format($c->budget, 2, ',', ' ') }} MAD</td>
                    <td class="px-6 py-4 text-right">
                        <span class="font-semibold {{ ($c->depenses_sum_montant ?? 0) > $c->budget ? 'text-red-600' : 'text-gray-800' }}">
                            {{ number_format($c->depenses_sum_montant ?? 0, 2, ',', ' ') }} MAD
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col items-center gap-1">
                            <div class="w-32 bg-gray-100 rounded-full h-2 overflow-hidden">
                                <div class="h-2 rounded-full transition-all duration-500" 
                                     style="width: {{ $pct }}%; background: linear-gradient(90deg, {{ $pct >= 90 ? '#ef4444' : ($pct >= 70 ? '#f97316' : '#6366f1') }}, {{ $pct >= 90 ? '#dc2626' : ($pct >= 70 ? '#ea580c' : '#8b5cf6') }});"></div>
                            </div>
                            <p class="text-xs font-medium text-gray-500">{{ $pct }}%</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $statusConfig = [
                                'en_cours' => ['bg-emerald-100', 'text-emerald-700', 'fa-play-circle'],
                                'termine' => ['bg-gray-100', 'text-gray-600', 'fa-check-circle'],
                                'suspendu' => ['bg-red-100', 'text-red-700', 'fa-pause-circle'],
                                'planifie' => ['bg-blue-100', 'text-blue-700', 'fa-clock']
                            ];
                            $config = $statusConfig[$c->statut] ?? ['bg-gray-100', 'text-gray-600', 'fa-circle'];
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $config[0] }} {{ $config[1] }}">
                            <i class="fas {{ $config[2] }} text-xs"></i>
                            {{ $c->statutLabel() }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('chantiers.show', $c) }}" 
                               class="tooltip w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                <i class="fas fa-eye text-sm"></i>
                                <span class="tooltip-text">Voir</span>
                            </a>
                            <a href="{{ route('chantiers.export.pdf', $c) }}" 
                               class="tooltip w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                <i class="fas fa-file-pdf text-sm"></i>
                                <span class="tooltip-text">PDF</span>
                            </a>
                            @if(!auth()->user()->isDirection())
                            <a href="{{ route('chantiers.edit', $c) }}" 
                               class="tooltip w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                <i class="fas fa-edit text-sm"></i>
                                <span class="tooltip-text">Modifier</span>
                            </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('chantiers.destroy', $c) }}" class="inline" onsubmit="return confirm('Supprimer ce chantier ?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="tooltip w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                    <i class="fas fa-trash text-sm"></i>
                                    <span class="tooltip-text">Supprimer</span>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-hard-hat text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Aucun chantier enregistré</p>
                            <p class="text-xs text-gray-400">Commencez par créer votre premier chantier</p>
                            @if(!auth()->user()->isDirection())
                            <a href="{{ route('chantiers.create') }}" class="mt-2 text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                + Créer un chantier
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $chantiers->links() }}
    </div>
</div>

<style>
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    .animate-slideIn {
        animation: slideInRight 0.4s ease-out forwards;
    }
    
    .tooltip {
        position: relative;
        display: inline-flex;
    }
    .tooltip .tooltip-text {
        visibility: hidden;
        opacity: 0;
        width: 70px;
        background: #1f2937;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 3px 6px;
        position: absolute;
        bottom: 110%;
        left: 50%;
        transform: translateX(-50%) scale(0.9);
        transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        font-size: 9px;
        font-weight: 500;
        letter-spacing: 0.3px;
        pointer-events: none;
        white-space: nowrap;
    }
    .tooltip .tooltip-text::after {
        content: '';
        position: absolute;
        top: 100%;
        left: 50%;
        transform: translateX(-50%);
        border: 4px solid transparent;
        border-top-color: #1f2937;
    }
    .tooltip:hover .tooltip-text {
        visibility: visible;
        opacity: 1;
        transform: translateX(-50%) scale(1);
    }
</style>
@endsection