@extends('layouts.app')
@section('title', 'Dépense - ' . ($depense->numero_facture ?? $depense->designation))

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Détail de la dépense</h2>
        <p class="text-gray-500">Facture {{ $depense->numero_facture ?? 'N/A' }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('depenses.edit', $depense) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm">Modifier</a>
        <a href="{{ route('depenses.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">Retour</a>
    </div>
</div>

<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="grid grid-cols-2 gap-0">
        <div class="p-6 border-r border-b">
            <p class="text-gray-400 text-xs">Désignation</p>
            <p class="font-semibold">{{ $depense->designation }}</p>
        </div>
        <div class="p-6 border-b">
            <p class="text-gray-400 text-xs">Montant</p>
            <p class="font-semibold text-blue-900">{{ number_format($depense->montant, 2, ',', ' ') }} MAD</p>
        </div>
        <div class="p-6 border-r">
            <p class="text-gray-400 text-xs">Chantier</p>
            <p class="font-semibold">{{ $depense->chantier->nom ?? '-' }}</p>
        </div>
        <div class="p-6">
            <p class="text-gray-400 text-xs">Fournisseur</p>
            <p class="font-semibold">{{ $depense->fournisseur->nom ?? '-' }}</p>
        </div>
        <div class="p-6 border-r border-t">
            <p class="text-gray-400 text-xs">Date</p>
            <p class="font-semibold">{{ $depense->date->format('d/m/Y') }}</p>
        </div>
        <div class="p-6 border-t">
            <p class="text-gray-400 text-xs">Statut paiement</p>
            <p><span class="px-2 py-1 rounded-full text-xs {{ $depense->statutClass() }}">{{ $depense->statutLabel() }}</span></p>
        </div>
        <div class="p-6 border-r border-t">
            <p class="text-gray-400 text-xs">Mode de paiement</p>
            <p class="font-semibold">{{ $depense->mode_paiement ?? 'Non spécifié' }}</p>
        </div>
        <div class="p-6 border-t">
            <p class="text-gray-400 text-xs">Numéro facture</p>
            <p class="font-semibold font-mono text-sm">{{ $depense->numero_facture ?? '-' }}</p>
        </div>
    </div>
</div>

<!-- Pièce jointe -->
@if($depense->piece_jointe)
<div class="mt-6 bg-white rounded-xl shadow p-6">
    <h3 class="font-bold mb-4 text-gray-700">📎 Pièce jointe</h3>
    <a href="{{ asset('storage/' . $depense->piece_jointe) }}" target="_blank" 
       class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 px-4 py-2 rounded-lg hover:bg-blue-100 transition">
        <i class="fas fa-file-pdf"></i> Voir le document
    </a>
</div>
@endif

<!-- Commentaire -->
@if($depense->commentaire)
<div class="mt-6 bg-white rounded-xl shadow p-6">
    <h3 class="font-bold mb-2 text-gray-700">💬 Commentaire</h3>
    <p class="text-gray-600">{{ $depense->commentaire }}</p>
</div>
@endif

<!-- Numéros BC/BL -->
@if($depense->numero_bc || $depense->numero_bl)
<div class="mt-6 bg-white rounded-xl shadow p-6">
    <h3 class="font-bold mb-3 text-gray-700">📄 Références</h3>
    <div class="grid grid-cols-2 gap-4">
        @if($depense->numero_bc)
        <div><p class="text-gray-400 text-xs">Bon de commande (BC)</p><p class="font-semibold">{{ $depense->numero_bc }}</p></div>
        @endif
        @if($depense->numero_bl)
        <div><p class="text-gray-400 text-xs">Bon de livraison (BL)</p><p class="font-semibold">{{ $depense->numero_bl }}</p></div>
        @endif
    </div>
</div>
@endif
@endsection