@extends('layouts.app')
@section('title', 'BC ' . $bonCommande->numero_bc)

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Bon de commande</h2>
            <p class="text-gray-500">N° {{ $bonCommande->numero_bc }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('bon-commandes.export.pdf', $bonCommande) }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">📄 PDF</a>
            @if(in_array(auth()->user()->role, ['admin', 'achat']))
            <a href="{{ route('bon-commandes.edit', $bonCommande) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">✏️ Modifier</a>
            @endif
            <a href="{{ route('bon-commandes.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">← Retour</a>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 pb-6 border-b">
        <div>
            <p class="text-gray-500 text-xs">Chantier</p>
            <p class="font-semibold">{{ $bonCommande->chantier->nom ?? '-' }}</p>
        </div>
        <div>
            <p class="text-gray-500 text-xs">Fournisseur</p>
            <p class="font-semibold">{{ $bonCommande->fournisseur->nom ?? '-' }}</p>
        </div>
        <div>
            <p class="text-gray-500 text-xs">Date commande</p>
            <p class="font-semibold">{{ $bonCommande->date_commande->format('d/m/Y') }}</p>
        </div>
        <div>
            <p class="text-gray-500 text-xs">Statut</p>
            <p class="font-semibold">
                @php
                    $statusColors = [
                        'en_attente' => 'bg-yellow-100 text-yellow-800',
                        'accepte' => 'bg-blue-100 text-blue-800',
                        'partiel' => 'bg-orange-100 text-orange-800',
                        'recu' => 'bg-green-100 text-green-800'
                    ];
                @endphp
                <span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$bonCommande->statut] ?? 'bg-gray-100' }}">
                    {{ ucfirst($bonCommande->statut) }}
                </span>
            </p>
        </div>
    </div>

    <h3 class="font-bold mb-3 text-lg">📦 Articles commandés</h3>
    <div class="overflow-x-auto">
        <table class="w-full text-sm border">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left">Désignation</th>
                    <th class="px-3 py-2 text-center">Code</th>
                    <th class="px-3 py-2 text-center">Qté</th>
                    <th class="px-3 py-2 text-center">Unité</th>
                    <th class="px-3 py-2 text-right">Prix HT</th>
                    <th class="px-3 py-2 text-right">Montant HT</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bonCommande->articles as $article)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-3 py-2 font-medium">{{ $article->designation }}</td>
                    <td class="px-3 py-2 text-center text-gray-500">{{ $article->code_article ?? '-' }}</td>
                    <td class="px-3 py-2 text-center">{{ $article->quantite }}</td>
                    <td class="px-3 py-2 text-center">{{ $article->unite }}</td>
                    <td class="px-3 py-2 text-right">{{ number_format($article->prix_unitaire_ht, 2, ',', ' ') }} MAD</td>
                    <td class="px-3 py-2 text-right font-semibold">{{ number_format($article->montant_ht, 2, ',', ' ') }} MAD</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="5" class="px-3 py-2 text-right font-semibold">Total HT :</td>
                    <td class="px-3 py-2 text-right font-bold">{{ number_format($bonCommande->total_ht, 2, ',', ' ') }} MAD</td>
                </tr>
                <tr>
                    <td colspan="5" class="px-3 py-2 text-right">TVA ({{ $bonCommande->tva }}%) :</td>
                    <td class="px-3 py-2 text-right">{{ number_format($bonCommande->total_tva, 2, ',', ' ') }} MAD</td>
                </tr>
                <tr class="bg-indigo-50">
                    <td colspan="5" class="px-3 py-2 text-right font-bold text-lg">Total TTC :</td>
                    <td class="px-3 py-2 text-right font-bold text-indigo-700 text-lg">{{ number_format($bonCommande->total_ttc, 2, ',', ' ') }} MAD</td>
                </tr>
            </tfoot>
        </table>
    </div>

    @if($bonCommande->commentaire)
    <div class="mt-6 p-4 bg-gray-50 rounded-lg">
        <p class="text-gray-500 text-xs mb-1">📝 Commentaire</p>
        <p class="text-gray-700">{{ $bonCommande->commentaire }}</p>
    </div>
    @endif

    @if($bonCommande->mode_paiement)
    <div class="mt-4">
        <p class="text-gray-500 text-xs">💳 Mode de paiement</p>
        <p class="font-medium">{{ $bonCommande->mode_paiement }}</p>
    </div>
    @endif
</div>
@endsection