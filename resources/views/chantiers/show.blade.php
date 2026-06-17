@extends('layouts.app')
@section('title', $chantier->nom)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">{{ $chantier->nom }}</h2>
        <p class="text-gray-500">Code: {{ $chantier->code }} | Responsable: {{ $chantier->responsable }}</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('chantiers.export.pdf', $chantier) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700 transition flex items-center gap-2">
            <i class="fas fa-file-pdf"></i> Exporter PDF
        </a>
        <a href="{{ route('chantiers.edit', $chantier) }}" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm">Modifier</a>
        <a href="{{ route('chantiers.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg text-sm">Retour</a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-gray-500 text-sm">Budget</p>
        <p class="text-xl font-bold">{{ number_format($chantier->budget, 2, ',', ' ') }} MAD</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-gray-500 text-sm">Dépenses</p>
        <p class="text-xl font-bold text-blue-900">{{ number_format($chantier->totalDepenses(), 2, ',', ' ') }} MAD</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-gray-500 text-sm">Restant</p>
        <p class="text-xl font-bold {{ $chantier->budgetRestant() < 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($chantier->budgetRestant(), 2, ',', ' ') }} MAD</p>
    </div>
    <div class="bg-white rounded-xl shadow p-4">
        <p class="text-gray-500 text-sm">Consommation</p>
        <p class="text-xl font-bold">{{ $chantier->pourcentageConsomme() }}%</p>
    </div>
</div>

<div class="bg-white rounded-xl shadow p-6">
    <h3 class="font-bold mb-4">Dépenses du chantier</h3>
    <table class="w-full text-sm">
        <thead class="bg-gray-50">
            <tr><th class="p-3 text-left">Facture</th><th class="p-3 text-left">Désignation</th><th class="p-3 text-right">Montant</th><th class="p-3 text-center">Statut</th></tr>
        </thead>
        <tbody>
            @forelse($depenses as $d)
            <tr class="border-b">
                <td class="p-3">{{ $d->numero_facture ?? '-' }}</td>
                <td class="p-3">{{ $d->designation }}</td>
                <td class="p-3 text-right">{{ number_format($d->montant, 2, ',', ' ') }}</td>
                <td class="p-3 text-center"><span class="px-2 py-1 rounded-full text-xs {{ $d->statutClass() }}">{{ $d->statutLabel() }}</span></td>
            </tr>
            @empty
            <tr><td colspan="4" class="p-6 text-center text-gray-400">Aucune dépense</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $depenses->links() }}
</div>
@endsection