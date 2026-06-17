@extends('layouts.app')
@section('title', 'Modifier ' . $fournisseur->nom)

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6"><h2 class="text-2xl font-bold text-gray-800">Modifier fournisseur</h2></div>
    <div class="bg-white rounded-xl shadow p-8">
        <form method="POST" action="{{ route('fournisseurs.update', $fournisseur) }}">
            @csrf @method('PUT')
            <div class="space-y-4">
                <div><label class="block text-sm font-medium mb-1">Nom *</label><input type="text" name="nom" value="{{ old('nom', $fournisseur->nom) }}" required class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-1">Téléphone</label><input type="text" name="telephone" value="{{ old('telephone', $fournisseur->telephone) }}" class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-1">Email</label><input type="email" name="email" value="{{ old('email', $fournisseur->email) }}" class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-1">Adresse</label><input type="text" name="adresse" value="{{ old('adresse', $fournisseur->adresse) }}" class="w-full border rounded-lg px-4 py-2"></div>
                <div><label class="block text-sm font-medium mb-1">ICE</label><input type="text" name="ice" value="{{ old('ice', $fournisseur->ice) }}" class="w-full border rounded-lg px-4 py-2"></div>
            </div>
            <div class="flex gap-3 mt-6">
                <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-lg">Mettre à jour</button>
                <a href="{{ route('fournisseurs.show', $fournisseur) }}" class="bg-gray-200 px-6 py-2 rounded-lg">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection