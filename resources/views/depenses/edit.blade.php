@extends('layouts.app')
@section('title', 'Modifier dépense')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Modifier la dépense</h2>
        <p class="text-gray-500">Complétez les informations de facturation</p>
    </div>
    <div class="bg-white rounded-xl shadow p-8">
        <form method="POST" action="{{ route('depenses.update', $depense) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Chantier</label>
                    <select name="chantier_id" class="w-full border rounded-lg px-4 py-2 bg-gray-100" disabled>
                        <option value="{{ $depense->chantier_id }}">{{ $depense->chantier->nom ?? '-' }}</option>
                    </select>
                    <input type="hidden" name="chantier_id" value="{{ $depense->chantier_id }}">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Fournisseur</label>
                    <select name="fournisseur_id" class="w-full border rounded-lg px-4 py-2 bg-gray-100" disabled>
                        <option value="{{ $depense->fournisseur_id }}">{{ $depense->fournisseur->nom ?? '-' }}</option>
                    </select>
                    <input type="hidden" name="fournisseur_id" value="{{ $depense->fournisseur_id }}">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Désignation</label>
                    <input type="text" name="designation" value="{{ old('designation', $depense->designation) }}" class="w-full border rounded-lg px-4 py-2 bg-gray-100" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Montant (MAD)</label>
                    <input type="number" name="montant" value="{{ old('montant', $depense->montant) }}" step="0.01" class="w-full border rounded-lg px-4 py-2 bg-gray-100" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Date</label>
                    <input type="date" name="date" value="{{ old('date', $depense->date->format('Y-m-d')) }}" class="w-full border rounded-lg px-4 py-2 bg-gray-100" readonly>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">N° Bon de Commande</label>
                    <input type="text" name="numero_bc" value="{{ old('numero_bc', $depense->numero_bc) }}" class="w-full border rounded-lg px-4 py-2 bg-gray-100" readonly>
                </div>
            </div>

            <!-- Section Facturation (modifiable par Finance) -->
            <div class="border-t pt-4 mt-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📄 Informations de facturation</h3>
                
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">N° Bon de Livraison (BL)</label>
                        <input type="text" name="numero_bl" value="{{ old('numero_bl', $depense->numero_bl) }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                        <p class="text-xs text-gray-400 mt-1">Ajoutez le numéro du bon de livraison reçu</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">N° Facture fournisseur</label>
                        <input type="text" name="numero_facture" value="{{ old('numero_facture', $depense->numero_facture) }}" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                        <p class="text-xs text-gray-400 mt-1">Ajoutez le numéro de facture du fournisseur</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mode de paiement</label>
                        <select name="mode_paiement" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                            <option value="">-- Sélectionner --</option>
                            <option value="virement" {{ $depense->mode_paiement == 'virement' ? 'selected' : '' }}>🏦 Virement bancaire</option>
                            <option value="cheque" {{ $depense->mode_paiement == 'cheque' ? 'selected' : '' }}>📝 Chèque</option>
                            <option value="especes" {{ $depense->mode_paiement == 'especes' ? 'selected' : '' }}>💵 Espèces</option>
                            <option value="carte" {{ $depense->mode_paiement == 'carte' ? 'selected' : '' }}>💳 Carte bancaire</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statut facture</label>
                        <select name="statut_facture" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                            <option value="non_payee" {{ ($depense->statut_facture ?? 'non_payee') == 'non_payee' ? 'selected' : '' }}>⏳ Non payée</option>
                            <option value="payee" {{ ($depense->statut_facture ?? '') == 'payee' ? 'selected' : '' }}>✅ Payée</option>
                        </select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire (optionnel)</label>
                    <textarea name="commentaire" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('commentaire', $depense->commentaire) }}</textarea>
                </div>
            </div>

            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                    <i class="fas fa-save mr-2"></i> Mettre à jour la facture
                </button>
                <a href="{{ route('depenses.show', $depense) }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection