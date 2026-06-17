@extends('layouts.app')
@section('title', 'Bons de commande')

@section('content')
<!-- Animation de bienvenue -->
<div x-data="{ showWelcome: true }" x-show="showWelcome" x-init="setTimeout(() => showWelcome = false, 4000)" 
     class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3"
     style="animation: slideInRight 0.4s ease-out forwards;">
    <i class="fas fa-file-signature text-xl animate-bounce"></i>
    <div>
        <p class="font-semibold text-sm">Bons de commande</p>
        <p class="text-xs text-blue-200">Gestion des achats</p>
    </div>
</div>

<div class="mb-8 animate-slideIn">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Bons de commande</h2>
            <p class="text-gray-500 mt-1 flex items-center gap-2">
                <i class="fas fa-file-signature text-blue-500 text-sm"></i>
                Gestion des commandes fournisseurs
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('bon-commandes.export.all.pdf') }}" 
               class="bg-red-600 text-white px-4 py-2.5 rounded-xl hover:bg-red-700 transition-all duration-200 flex items-center gap-2 shadow-md">
                <i class="fas fa-file-pdf"></i>
                <span class="text-sm font-medium">PDF</span>
            </a>
            @if(in_array(auth()->user()->role, ['admin', 'achat']))
            <a href="{{ route('bon-commandes.create') }}" 
               class="group bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-5 py-2.5 rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                <i class="fas fa-plus text-sm group-hover:rotate-90 transition-transform duration-300"></i>
                <span class="text-sm font-medium">Nouveau BC</span>
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
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">N° BC</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Chantier</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fournisseur</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total TTC</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bonCommandes as $bc)
                <tr class="hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-transparent transition-all duration-200 group">
                    <td class="px-6 py-4">
                        <span class="font-mono font-semibold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg text-xs">{{ $bc->numero_bc }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-700">{{ $bc->chantier->nom ?? '-' }}</td>
                    <td class="px-6 py-4 text-gray-700">{{ $bc->fournisseur->nom ?? '-' }}</td>
                    <td class="px-6 py-4 text-center text-gray-500">{{ $bc->date_commande->format('d/m/Y') }}</td>
                    <td class="px-6 py-4 text-right font-bold text-indigo-600">{{ number_format($bc->total_ttc, 2, ',', ' ') }} MAD</td>
                    <td class="px-6 py-4 text-center">
                        @php
                            $statusConfig = [
                                'en_attente' => ['bg-yellow-100', 'text-yellow-700', 'fa-clock', 'En attente'],
                                'accepte' => ['bg-blue-100', 'text-blue-700', 'fa-check-circle', 'Accepté'],
                                'partiel' => ['bg-orange-100', 'text-orange-700', 'fa-hourglass-half', 'Partiel'],
                                'recu' => ['bg-green-100', 'text-green-700', 'fa-box-check', 'Reçu']
                            ];
                            $config = $statusConfig[$bc->statut] ?? ['bg-gray-100', 'text-gray-600', 'fa-circle', $bc->statut];
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-semibold {{ $config[0] }} {{ $config[1] }}">
                            <i class="fas {{ $config[2] }} text-xs"></i>
                            {{ $config[3] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('bon-commandes.show', $bc) }}" 
                               class="tooltip w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                <i class="fas fa-eye text-sm"></i>
                                <span class="tooltip-text">Voir</span>
                            </a>
                            <a href="{{ route('bon-commandes.export.pdf', $bc) }}" 
                               class="tooltip w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                <i class="fas fa-file-pdf text-sm"></i>
                                <span class="tooltip-text">PDF</span>
                            </a>
                            @if(in_array(auth()->user()->role, ['admin', 'achat']))
                            <a href="{{ route('bon-commandes.edit', $bc) }}" 
                               class="tooltip w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                <i class="fas fa-edit text-sm"></i>
                                <span class="tooltip-text">Modifier</span>
                            </a>
                            <form action="{{ route('bon-commandes.destroy', $bc) }}" method="POST" class="inline" onsubmit="return confirm('Supprimer ce bon de commande ?')">
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
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-file-signature text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Aucun bon de commande</p>
                            <p class="text-xs text-gray-400">Commencez par créer votre premier bon de commande</p>
                            @if(in_array(auth()->user()->role, ['admin', 'achat']))
                            <a href="{{ route('bon-commandes.create') }}" class="mt-2 text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                + Créer un BC
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
        {{ $bonCommandes->links() }}
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