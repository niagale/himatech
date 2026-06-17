@extends('layouts.app')
@section('title', 'Nouveau chantier')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Nouveau chantier</h2>
        <p class="text-gray-500">Remplissez les informations du chantier</p>
    </div>

    <div class="bg-white rounded-xl shadow p-8">
        <form method="POST" action="{{ route('chantiers.store') }}" class="space-y-5">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nom du chantier *</label>
                    <input type="text" name="nom" value="{{ old('nom') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Code chantier *</label>
                    <input type="text" name="code" value="{{ old('code') }}" required placeholder="CHT-001" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Responsable *</label>
                    <input type="text" name="responsable" value="{{ old('responsable') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Budget (MAD) *</label>
                    <input type="number" name="budget" value="{{ old('budget') }}" step="0.01" min="0" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date début *</label>
                    <input type="date" name="date_debut" value="{{ old('date_debut') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date fin prévue</label>
                    <input type="date" name="date_fin" value="{{ old('date_fin') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statut *</label>
                    <select name="statut" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="planifie">Planifié</option>
                        <option value="en_cours">En cours</option>
                        <option value="suspendu">Suspendu</option>
                        <option value="termine">Terminé</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Localisation</label>
                    <input type="text" name="localisation" value="{{ old('localisation') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description') }}</textarea>
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-800 transition">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
                <a href="{{ route('chantiers.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
