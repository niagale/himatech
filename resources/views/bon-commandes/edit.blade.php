@extends('layouts.app')
@section('title', 'Modifier BC ' . $bonCommande->numero_bc)

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Modifier le bon de commande</h2>
            <p class="text-gray-500">N° {{ $bonCommande->numero_bc }}</p>
        </div>
        <a href="{{ route('bon-commandes.show', $bonCommande) }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">← Retour</a>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <form method="POST" action="{{ route('bon-commandes.update', $bonCommande) }}">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date livraison prévue</label>
                <input type="date" name="date_livraison_prevue" 
                       value="{{ old('date_livraison_prevue', $bonCommande->date_livraison_prevue?->format('Y-m-d')) }}" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="statut" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                    <option value="en_attente" {{ $bonCommande->statut == 'en_attente' ? 'selected' : '' }}>⏳ En attente</option>
                    <option value="accepte" {{ $bonCommande->statut == 'accepte' ? 'selected' : '' }}>✅ Accepté</option>
                    <option value="partiel" {{ $bonCommande->statut == 'partiel' ? 'selected' : '' }}>📦 Livraison partielle</option>
                    <option value="recu" {{ $bonCommande->statut == 'recu' ? 'selected' : '' }}>🎯 Reçu complet</option>
                </select>
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
            <textarea name="commentaire" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">{{ old('commentaire', $bonCommande->commentaire) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">
                💾 Mettre à jour
            </button>
            <a href="{{ route('bon-commandes.show', $bonCommande) }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection