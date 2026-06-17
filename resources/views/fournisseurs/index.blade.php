@extends('layouts.app')
@section('title', 'Fournisseurs')

@section('content')
<!-- Animation de bienvenue -->
<div x-data="{ showWelcome: true }" x-show="showWelcome" x-init="setTimeout(() => showWelcome = false, 4000)" 
     class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-blue-600 to-cyan-600 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3"
     style="animation: slideInRight 0.4s ease-out forwards;">
    <i class="fas fa-truck text-xl animate-bounce"></i>
    <div>
        <p class="font-semibold text-sm">Gestion des fournisseurs</p>
        <p class="text-xs text-blue-200">Partenaires et prestataires</p>
    </div>
</div>

<div class="mb-8 animate-slideIn">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Fournisseurs</h2>
            <p class="text-gray-500 mt-1 flex items-center gap-2">
                <i class="fas fa-truck text-blue-500 text-sm"></i>
                Gestion des partenaires et prestataires
            </p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('fournisseurs.export.all.pdf') }}" 
               class="bg-red-600 text-white px-4 py-2.5 rounded-xl hover:bg-red-700 transition-all duration-200 flex items-center gap-2 shadow-md">
                <i class="fas fa-file-pdf"></i>
                <span class="text-sm font-medium">PDF</span>
            </a>
            @if(!auth()->user()->isDirection())
            <a href="{{ route('fournisseurs.create') }}" 
               class="group bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-5 py-2.5 rounded-xl hover:from-indigo-700 hover:to-indigo-800 transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg">
                <i class="fas fa-plus text-sm group-hover:rotate-90 transition-transform duration-300"></i>
                <span class="text-sm font-medium">Nouveau fournisseur</span>
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
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fournisseur</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ICE</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Dépenses</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($fournisseurs as $f)
                <tr class="hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-transparent transition-all duration-200 group">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-md">
                                <i class="fas fa-building text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $f->nom }}</p>
                                @if($f->adresse)
                                <p class="text-xs text-gray-400 mt-0.5">
                                    <i class="fas fa-map-marker-alt text-[10px]"></i> {{ Str::limit($f->adresse, 40) }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="space-y-1">
                            @if($f->telephone)
                            <div class="flex items-center gap-2 text-gray-600">
                                <i class="fas fa-phone-alt text-xs text-gray-400"></i>
                                <span>{{ $f->telephone }}</span>
                            </div>
                            @endif
                            @if($f->email)
                            <div class="flex items-center gap-2 text-indigo-600">
                                <i class="fas fa-envelope text-xs"></i>
                                <span class="text-xs">{{ $f->email }}</span>
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @if($f->ice)
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded-full text-gray-600">{{ $f->ice }}</span>
                        @else
                        <span class="text-xs text-gray-400">Non renseigné</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="font-bold text-indigo-600">{{ number_format($f->depenses_sum_montant ?? 0, 2, ',', ' ') }} <span class="text-xs text-gray-400">MAD</span></span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('fournisseurs.show', $f) }}" 
                               class="tooltip w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                <i class="fas fa-eye text-sm"></i>
                                <span class="tooltip-text">Voir</span>
                            </a>
                            @if(!auth()->user()->isDirection())
                            <a href="{{ route('fournisseurs.edit', $f) }}" 
                               class="tooltip w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                <i class="fas fa-edit text-sm"></i>
                                <span class="tooltip-text">Modifier</span>
                            </a>
                            @endif
                            @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('fournisseurs.destroy', $f) }}" class="inline" onsubmit="return confirm('Supprimer ce fournisseur ?')">
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
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-truck text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Aucun fournisseur enregistré</p>
                            <p class="text-xs text-gray-400">Commencez par ajouter votre premier fournisseur</p>
                            @if(!auth()->user()->isDirection())
                            <a href="{{ route('fournisseurs.create') }}" class="mt-2 text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                + Ajouter un fournisseur
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
        {{ $fournisseurs->links() }}
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