@extends('layouts.app')
@section('title', 'Nouveau fournisseur')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Nouveau fournisseur</h2>
    </div>
    <div class="bg-white rounded-xl shadow p-8">
        <form method="POST" action="{{ route('fournisseurs.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nom *</label>
                <input type="text" name="nom" value="{{ old('nom') }}" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="text" name="telephone" value="{{ old('telephone') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                <input type="text" name="adresse" value="{{ old('adresse') }}" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ICE</label>
                <input type="text" name="ice" value="{{ old('ice') }}" placeholder="000000000000000" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none">
            </div>
            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-800">
                    <i class="fas fa-save mr-2"></i>Enregistrer
                </button>
                <a href="{{ route('fournisseurs.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
