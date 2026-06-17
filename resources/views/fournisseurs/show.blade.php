@extends('layouts.app')
@section('title', $fournisseur->nom)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">{{ $fournisseur->nom }}</h2>
        <p class="text-gray-500">ICE: {{ $fournisseur->ice ?? 'Non renseigné' }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('fournisseurs.edit', $fournisseur) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm">Modifier</a>
        <a href="{{ route('fournisseurs.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">Retour</a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-4"><p class="text-gray-400 text-xs">Téléphone</p><p class="font-semibold">{{ $fournisseur->telephone ?? '-' }}</p></div>
    <div class="bg-white rounded-xl shadow p-4"><p class="text-gray-400 text-xs">Email</p><p class="font-semibold">{{ $fournisseur->email ?? '-' }}</p></div>
    <div class="bg-white rounded-xl shadow p-4"><p class="text-gray-400 text-xs">Total dépenses</p><p class="font-semibold text-blue-900">{{ number_format($fournisseur->totalDepenses(), 2, ',', ' ') }} MAD</p></div>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-bold mb-4">Historique des dépenses</h3>
    <table class="w-full text-sm">
        <thead class="bg-gray-50"> <tr><th class="p-3 text-left">Date</th><th class="p-3 text-left">Chantier</th><th class="p-3 text-left">Désignation</th><th class="p-3 text-right">Montant</th></tr> </thead>
        <tbody>
            @forelse($depenses as $d)
            <tr class="border-b"><td class="p-3">{{ $d->date->format('d/m/Y') }}</td><td class="p-3">{{ $d->chantier->nom ?? '-' }}</td><td class="p-3">{{ $d->designation }}</td><td class="p-3 text-right">{{ number_format($d->montant, 2, ',', ' ') }}</td></tr>
            @empty <tr><td colspan="4" class="p-6 text-center text-gray-400">Aucune dépense</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $depenses->links() }}
</div>
@endsection