@extends('layouts.app')
@section('title', 'Nouvelle dépense')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Nouvelle dépense</h2>
        <p class="text-gray-500">Enregistrez une nouvelle dépense chantier</p>
    </div>

    <div class="bg-white rounded-xl shadow p-8">
        <form method="POST" action="{{ route('depenses.store') }}" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Chantier *</label>
                    <select name="chantier_id" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Sélectionner --</option>
                        @foreach($chantiers as $c)
                        <option value="{{ $c->id }}" {{ old('chantier_id') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fournisseur</label>
                    <select name="fournisseur_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Aucun --</option>
                        @foreach($fournisseurs as $f)
                        <option value="{{ $f->id }}" {{ old('fournisseur_id') == $f->id ? 'selected' : '' }}>{{ $f->nom }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Désignation *</label>
                <input type="text" name="designation" value="{{ old('designation') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Montant (MAD) *</label>
                    <input type="number" name="montant" value="{{ old('montant') }}" step="0.01" min="0" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date *</label>
                    <input type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">N° Facture</label>
                    <input type="text" name="numero_facture" value="{{ old('numero_facture') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">N° BC</label>
                    <input type="text" name="numero_bc" value="{{ old('numero_bc') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">N° BL</label>
                    <input type="text" name="numero_bl" value="{{ old('numero_bl') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mode paiement</label>
                    <select name="mode_paiement" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none">
                        <option value="">-- Choisir --</option>
                        <option value="virement">Virement</option>
                        <option value="cheque">Chèque</option>
                        <option value="especes">Espèces</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut paiement *</label>
                    <select name="statut_paiement" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none">
                        <option value="en_attente">En attente</option>
                        <option value="paye">Payé</option>
                        <option value="partiellement_paye">Partiellement payé</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                <textarea name="commentaire" rows="2" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none">{{ old('commentaire') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Pièce jointe (PDF, image, Excel – max 5Mo)</label>
                <input type="file" name="piece_jointe" accept=".pdf,.jpg,.jpeg,.png,.xlsx"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none text-sm">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-800 transition">
                    <i class="fas fa-save mr-2"></i>Enregistrer la dépense
                </button>
                <a href="{{ route('depenses.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
