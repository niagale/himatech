@extends('layouts.app')
@section('title', 'Dépenses')

@section('content')
<div x-data="{ showWelcome: true }" x-show="showWelcome" x-init="setTimeout(() => showWelcome = false, 4000)" 
     class="fixed bottom-6 right-6 z-50 bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3"
     style="animation: slideInRight 0.4s ease-out forwards;">
    <i class="fas fa-file-invoice-dollar text-xl animate-bounce"></i>
    <div>
        <p class="font-semibold text-sm">Gestion des dépenses</p>
        <p class="text-xs text-emerald-200">Suivez vos factures en temps réel</p>
    </div>
</div>

<div class="mb-8 animate-slideIn">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Dépenses</h2>
            <p class="text-gray-500 mt-1 flex items-center gap-2">
                <i class="fas fa-file-invoice-dollar text-emerald-500 text-sm"></i>
                {{ $depenses->total() }} dépense(s) enregistrée(s)
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <form method="GET" action="{{ route('depenses.export.mensuel') }}" class="inline-flex items-center gap-2 bg-white rounded-xl shadow-sm p-1">
                <select name="mois" class="border-0 bg-transparent px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 rounded-lg">
                    @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 1)) }}</option>
                    @endfor
                </select>
                <select name="annee" class="border-0 bg-transparent px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 rounded-lg">
                    @for($i = date('Y'); $i >= date('Y')-2; $i--)
                    <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 py-2 rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 flex items-center gap-2 shadow-md">
                    <i class="fas fa-calendar-alt"></i>
                    <span class="hidden sm:inline">Rapport mensuel</span>
                </button>
            </form>
            
            <a href="{{ route('depenses.export.pdf') }}" class="bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-2 rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 flex items-center gap-2 shadow-md">
                <i class="fas fa-file-pdf"></i>
                <span class="hidden sm:inline">PDF</span>
            </a>
            
            <a href="{{ route('depenses.export') }}" class="bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-green-800 transition-all duration-200 flex items-center gap-2 shadow-md">
                <i class="fas fa-file-excel"></i>
                <span class="hidden sm:inline">CSV</span>
            </a>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-xl p-6 mb-8 border border-white/50 animate-fadeInUp">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
            <div class="w-1 h-5 bg-gradient-to-b from-indigo-500 to-purple-600 rounded-full"></div>
            <i class="fas fa-sliders-h text-indigo-500"></i> Filtrer les dépenses
        </h3>
    </div>
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1"><i class="fas fa-search text-gray-400 mr-1"></i> Recherche</label>
            <input type="text" name="search" placeholder="Facture ou désignation..." value="{{ request('search') }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1"><i class="fas fa-hard-hat text-gray-400 mr-1"></i> Chantier</label>
            <select name="chantier_id" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                <option value="">Tous les chantiers</option>
                @foreach($chantiers as $c)
                <option value="{{ $c->id }}" {{ request('chantier_id') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1"><i class="fas fa-file-invoice text-gray-400 mr-1"></i> État facture</label>
            <select name="statut_facture" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                <option value="">Tous</option>
                <option value="non_payee" {{ request('statut_facture') == 'non_payee' ? 'selected' : '' }}>Non payée</option>
                <option value="payee" {{ request('statut_facture') == 'payee' ? 'selected' : '' }}>Payée</option>
            </select>
        </div>
        <div class="flex gap-2 items-end">
            <button type="submit" class="flex-1 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-xl py-2.5 text-sm font-medium hover:from-indigo-700 transition flex items-center justify-center gap-2 shadow-md">
                <i class="fas fa-search text-xs"></i> Appliquer
            </button>
            <a href="{{ route('depenses.index') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition flex items-center justify-center gap-1">
                <i class="fas fa-undo-alt text-xs"></i>
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100 animate-fadeInUp">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                    <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600">Chantier</th>
                    <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600">Fournisseur</th>
                    <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600">Date</th>
                    <th class="px-5 py-4 text-left text-xs font-semibold text-gray-600">Désignation</th>
                    <th class="px-5 py-4 text-center text-xs font-semibold text-gray-600">N° BC</th>
                    <th class="px-5 py-4 text-center text-xs font-semibold text-gray-600">N° BL</th>
                    <th class="px-5 py-4 text-center text-xs font-semibold text-gray-600">N° Facture</th>
                    <th class="px-5 py-4 text-center text-xs font-semibold text-gray-600">État facture</th>
                    <th class="px-5 py-4 text-center text-xs font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($depenses as $d)
                <tr class="hover:bg-gradient-to-r hover:from-indigo-50/50 hover:to-transparent transition-all duration-200 group">
                    <td class="px-5 py-4">
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-medium">
                            <i class="fas fa-hard-hat text-[10px]"></i>
                            {{ $d->chantier->nom ?? '–' }}
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-truck text-gray-400 text-xs"></i>
                            <span class="text-gray-700">{{ $d->fournisseur->nom ?? '–' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-600">{{ $d->date->format('d/m/Y') }}</td>
                    <td class="px-5 py-4">
                        <div>
                            <p class="font-medium text-gray-800">{{ $d->designation }}</p>
                            @if($d->montant)
                            <p class="text-xs text-gray-400">{{ number_format($d->montant, 2, ',', ' ') }} MAD</p>
                            @endif
                        </div>
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($d->numero_bc)
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded-full">{{ $d->numero_bc }}</span>
                        @else
                        <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($d->numero_bl)
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded-full">{{ $d->numero_bl }}</span>
                        @else
                        <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($d->numero_facture)
                        <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded-full">{{ $d->numero_facture }}</span>
                        @else
                        <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        @php
                            $factureConfig = [
                                'payee' => ['bg-green-100', 'text-green-700', 'fa-check-circle', 'Payée'],
                                'non_payee' => ['bg-yellow-100', 'text-yellow-700', 'fa-clock', 'Non payée']
                            ];
                            $statutFacture = $d->statut_facture ?? 'non_payee';
                            $config = $factureConfig[$statutFacture] ?? $factureConfig['non_payee'];
                        @endphp
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $config[0] }} {{ $config[1] }}">
                            <i class="fas {{ $config[2] }} text-xs"></i>
                            {{ $config[3] }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('depenses.show', $d) }}" 
                               class="tooltip w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                <i class="fas fa-eye text-sm"></i>
                                <span class="tooltip-text">Voir</span>
                            </a>
                            <a href="{{ route('depenses.export.single.pdf', $d) }}" 
                               class="tooltip w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                <i class="fas fa-file-pdf text-sm"></i>
                                <span class="tooltip-text">PDF</span>
                            </a>
                            <a href="{{ route('depenses.export.single.csv', $d) }}" 
                               class="tooltip w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                <i class="fas fa-file-excel text-sm"></i>
                                <span class="tooltip-text">CSV</span>
                            </a>
                            @if(in_array(auth()->user()->role, ['admin', 'finance', 'achat']))
                            <a href="{{ route('depenses.edit', $d) }}" 
                               class="tooltip w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 transition-all duration-200 flex items-center justify-center hover:scale-110 hover:shadow-md">
                                <i class="fas fa-edit text-sm"></i>
                                <span class="tooltip-text">Modifier</span>
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-file-invoice-dollar text-gray-400 text-2xl"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Aucune dépense trouvée</p>
                            <p class="text-xs text-gray-400">Ajustez vos filtres ou créez un bon de commande</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $depenses->links() }}
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