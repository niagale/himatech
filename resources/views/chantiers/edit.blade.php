@extends('layouts.app')
@section('title', 'Modifier ' . $chantier->nom)

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Modifier le chantier</h2>
        <p class="text-gray-500">{{ $chantier->code }}</p>
    </div>

    <div class="bg-white rounded-xl shadow p-8">
        <form method="POST" action="{{ route('chantiers.update', $chantier) }}">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div><label class="block text-sm font-medium mb-1">Nom *</label><input type="text" name="nom" value="{{ old('nom', $chantier->nom) }}" required class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-1">Code *</label><input type="text" name="code" value="{{ old('code', $chantier->code) }}" required class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-1">Responsable *</label><input type="text" name="responsable" value="{{ old('responsable', $chantier->responsable) }}" required class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-1">Budget *</label><input type="number" name="budget" value="{{ old('budget', $chantier->budget) }}" step="0.01" required class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-1">Date début *</label><input type="date" name="date_debut" value="{{ old('date_debut', $chantier->date_debut->format('Y-m-d')) }}" required class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-1">Statut</label>
                    <select name="statut" class="w-full border rounded-lg px-4 py-2">
                        <option value="planifie" {{ $chantier->statut == 'planifie' ? 'selected' : '' }}>Planifié</option>
                        <option value="en_cours" {{ $chantier->statut == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="suspendu" {{ $chantier->statut == 'suspendu' ? 'selected' : '' }}>Suspendu</option>
                        <option value="termine" {{ $chantier->statut == 'termine' ? 'selected' : '' }}>Terminé</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-lg">Mettre à jour</button>
                <a href="{{ route('chantiers.show', $chantier) }}" class="bg-gray-200 px-6 py-2 rounded-lg">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection