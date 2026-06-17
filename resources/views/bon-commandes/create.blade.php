@extends('layouts.app')
@section('title', 'Nouveau bon de commande')

@section('content')
<div class="mb-8 animate-slideIn">
    <div>
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Nouveau bon de commande</h2>
        <p class="text-gray-500 mt-1 flex items-center gap-2">
            <i class="fas fa-file-signature text-indigo-500 text-sm"></i>
            Créer un bon de commande pour un fournisseur
        </p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
    <form method="POST" action="{{ route('bon-commandes.store') }}" id="bcForm">
        @csrf
        
        <!-- En-tête du BC -->
        <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-indigo-500"></i> Informations générales
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Chantier *</label>
                    <select name="chantier_id" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Sélectionner un chantier</option>
                        @foreach($chantiers as $c)
                        <option value="{{ $c->id }}" {{ old('chantier_id') == $c->id ? 'selected' : '' }}>{{ $c->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fournisseur *</label>
                    <select name="fournisseur_id" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">Sélectionner un fournisseur</option>
                        @foreach($fournisseurs as $f)
                        <option value="{{ $f->id }}" {{ old('fournisseur_id') == $f->id ? 'selected' : '' }}>{{ $f->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date commande *</label>
                    <input type="date" name="date_commande" value="{{ old('date_commande', date('Y-m-d')) }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Date livraison prévue</label>
                    <input type="date" name="date_livraison_prevue" value="{{ old('date_livraison_prevue') }}" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>
        
        <!-- Articles du BC -->
        <div class="p-6 border-b border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-boxes text-indigo-500"></i> Articles
                </h3>
                <button type="button" id="addArticle" class="bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-lg text-sm hover:bg-indigo-100 transition flex items-center gap-2">
                    <i class="fas fa-plus"></i> Ajouter un article
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm" id="articlesTable">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Code</th>
                            <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600">Désignation *</th>
                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600">Unité</th>
                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600">Qté</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600">Prix unitaire HT</th>
                            <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600">Montant HT</th>
                            <th class="px-3 py-2 text-center text-xs font-semibold text-gray-600">Action</th>
                        </tr>
                    </thead>
                    <tbody id="articlesBody">
                        <tr class="article-row border-b border-gray-100">
                            <td class="px-3 py-2"><input type="text" name="articles[0][code_article]" class="w-24 border border-gray-200 rounded-lg px-2 py-1.5 text-sm"></td>
                            <td class="px-3 py-2"><input type="text" name="articles[0][designation]" required class="w-48 border border-gray-200 rounded-lg px-2 py-1.5 text-sm"></td>
                            <td class="px-3 py-2"><input type="text" name="articles[0][unite]" value="pièce" class="w-20 border border-gray-200 rounded-lg px-2 py-1.5 text-sm"></td>
                            <td class="px-3 py-2"><input type="number" name="articles[0][quantite]" value="1" step="1" min="1" required class="w-20 border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-center qte"></td>
                            <td class="px-3 py-2"><input type="number" name="articles[0][prix_unitaire_ht]" step="0.01" min="0" required class="w-28 border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-right prix"></td>
                            <td class="px-3 py-2"><input type="text" name="articles[0][montant_ht]" readonly class="w-28 border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-right bg-gray-50 montant"></td>
                            <td class="px-3 py-2 text-center"><button type="button" class="removeArticle text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="5" class="px-3 py-3 text-right font-semibold">Total HT :</td>
                            <td class="px-3 py-3 text-right"><input type="text" id="total_ht" name="total_ht" readonly class="w-28 border-0 bg-transparent font-bold text-indigo-600 text-right"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="px-3 py-2 text-right">TVA (%) :</td>
                            <td class="px-3 py-2"><input type="number" name="tva" id="tva" value="20" step="1" class="w-28 border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-right"></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td colspan="5" class="px-3 py-2 text-right font-semibold">Total TVA :</td>
                            <td class="px-3 py-2"><input type="text" id="total_tva" name="total_tva" readonly class="w-28 border-0 bg-transparent font-semibold text-right"></td>
                            <td></td>
                        </tr>
                        <tr class="bg-indigo-50">
                            <td colspan="5" class="px-3 py-3 text-right font-bold text-lg">Total TTC :</td>
                            <td class="px-3 py-3"><input type="text" id="total_ttc" name="total_ttc" readonly class="w-28 border-0 bg-transparent font-bold text-indigo-700 text-right text-lg"></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        <!-- Pied du BC -->
        <div class="p-6 border-b border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mode de paiement</label>
                    <select name="mode_paiement" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
                        <option value="">Sélectionner</option>
                        <option value="virement">Virement bancaire</option>
                        <option value="cheque">Chèque</option>
                        <option value="especes">Espèces</option>
                        <option value="credit">Crédit</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                    <textarea name="commentaire" rows="2" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm">{{ old('commentaire') }}</textarea>
                </div>
            </div>
        </div>
        
        <!-- Boutons -->
        <div class="p-6 flex gap-3">
            <button type="submit" class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white px-6 py-2.5 rounded-xl font-medium hover:from-indigo-700 hover:to-indigo-800 transition shadow-md">
                <i class="fas fa-save mr-2"></i> Créer le bon de commande
            </button>
            <a href="{{ route('bon-commandes.index') }}" class="bg-gray-100 text-gray-700 px-6 py-2.5 rounded-xl font-medium hover:bg-gray-200 transition">
                Annuler
            </a>
        </div>
    </form>
</div>

<script>
    let articleIndex = 1;
    
    // Calcul automatique des montants
    function calculateRow(row) {
        const qte = parseFloat(row.querySelector('.qte')?.value) || 0;
        const prix = parseFloat(row.querySelector('.prix')?.value) || 0;
        const montant = qte * prix;
        const montantField = row.querySelector('.montant');
        if (montantField) montantField.value = montant.toFixed(2);
        calculateTotals();
    }
    
    function calculateTotals() {
        let totalHT = 0;
        document.querySelectorAll('.montant').forEach(field => {
            totalHT += parseFloat(field.value) || 0;
        });
        document.getElementById('total_ht').value = totalHT.toFixed(2);
        
        const tva = parseFloat(document.getElementById('tva')?.value) || 0;
        const totalTVA = totalHT * tva / 100;
        document.getElementById('total_tva').value = totalTVA.toFixed(2);
        document.getElementById('total_ttc').value = (totalHT + totalTVA).toFixed(2);
    }
    
    // Ajouter une ligne d'article
    document.getElementById('addArticle')?.addEventListener('click', function() {
        const tbody = document.getElementById('articlesBody');
        const newRow = document.createElement('tr');
        newRow.className = 'article-row border-b border-gray-100';
        newRow.innerHTML = `
            <td class="px-3 py-2"><input type="text" name="articles[${articleIndex}][code_article]" class="w-24 border border-gray-200 rounded-lg px-2 py-1.5 text-sm"></td>
            <td class="px-3 py-2"><input type="text" name="articles[${articleIndex}][designation]" required class="w-48 border border-gray-200 rounded-lg px-2 py-1.5 text-sm"></td>
            <td class="px-3 py-2"><input type="text" name="articles[${articleIndex}][unite]" value="pièce" class="w-20 border border-gray-200 rounded-lg px-2 py-1.5 text-sm"></td>
            <td class="px-3 py-2"><input type="number" name="articles[${articleIndex}][quantite]" value="1" step="1" min="1" required class="w-20 border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-center qte"></td>
            <td class="px-3 py-2"><input type="number" name="articles[${articleIndex}][prix_unitaire_ht]" step="0.01" min="0" required class="w-28 border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-right prix"></td>
            <td class="px-3 py-2"><input type="text" name="articles[${articleIndex}][montant_ht]" readonly class="w-28 border border-gray-200 rounded-lg px-2 py-1.5 text-sm text-right bg-gray-50 montant"></td>
            <td class="px-3 py-2 text-center"><button type="button" class="removeArticle text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button></td>
        `;
        tbody.appendChild(newRow);
        
        // Ajouter les écouteurs d'événements
        const qteInput = newRow.querySelector('.qte');
        const prixInput = newRow.querySelector('.prix');
        if (qteInput) qteInput.addEventListener('input', () => calculateRow(newRow));
        if (prixInput) prixInput.addEventListener('input', () => calculateRow(newRow));
        
        articleIndex++;
    });
    
    // Supprimer une ligne
    document.addEventListener('click', function(e) {
        if (e.target.closest('.removeArticle')) {
            const row = e.target.closest('.article-row');
            if (document.querySelectorAll('.article-row').length > 1) {
                row.remove();
                calculateTotals();
            } else {
                alert('Vous devez avoir au moins un article');
            }
        }
    });
    
    // Écouteurs sur les lignes existantes
    document.querySelectorAll('.article-row').forEach(row => {
        const qteInput = row.querySelector('.qte');
        const prixInput = row.querySelector('.prix');
        if (qteInput) qteInput.addEventListener('input', () => calculateRow(row));
        if (prixInput) prixInput.addEventListener('input', () => calculateRow(row));
    });
    
    // TVA change
    document.getElementById('tva')?.addEventListener('input', calculateTotals);
    
    calculateTotals();
</script>
@endsection