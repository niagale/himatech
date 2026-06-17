<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport chantier - {{ $chantier->nom }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #1e3a8a; margin: 0; }
        .header p { color: #666; margin: 5px 0; }
        .chantier-info { background: #f3f4f6; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .chantier-info table { width: 100%; }
        .chantier-info td { padding: 5px; }
        .chantier-info .label { font-weight: bold; width: 30%; }
        .summary { margin-bottom: 20px; }
        .summary table { width: 100%; border-collapse: collapse; }
        .summary td { padding: 8px; border: 1px solid #ddd; }
        .summary .label { background: #1e3a8a; color: white; font-weight: bold; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table.data th { background: #1e3a8a; color: white; padding: 8px; text-align: left; }
        table.data td { padding: 6px 8px; border-bottom: 1px solid #ddd; }
        .footer { text-align: center; margin-top: 30px; font-size: 10px; color: #999; }
        .total { font-weight: bold; background: #f3f4f6; }
        .budget-bar { background: #e5e7eb; border-radius: 10px; height: 20px; width: 100%; margin: 10px 0; }
        .budget-fill { background: #3b82f6; border-radius: 10px; height: 20px; width: {{ min($pourcentageConsomme, 100) }}%; }
        .budget-warning { background: #ef4444; }
    </style>
</head>
<body>
    <div class="header">
        <h1>⚙️ Himatech</h1>
        <p>Plateforme de suivi des dépenses par chantier</p>
        <p><strong>Rapport détaillé du chantier</strong> – Généré le {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="chantier-info">
        <h3 style="margin: 0 0 10px 0; color: #1e3a8a;">📋 Informations chantier</h3>
        <table>
            <tr><td class="label">Nom :</td><td>{{ $chantier->nom }}</td></tr>
            <tr><td class="label">Code :</td><td>{{ $chantier->code }}</td></tr>
            <tr><td class="label">Responsable :</td><td>{{ $chantier->responsable }}</td></tr>
            <tr><td class="label">Localisation :</td><td>{{ $chantier->localisation ?? 'Non spécifiée' }}</td></tr>
            <tr><td class="label">Date début :</td><td>{{ \Carbon\Carbon::parse($chantier->date_debut)->format('d/m/Y') }}</td></tr>
            <tr><td class="label">Statut :</td><td>
                @if($chantier->statut == 'en_cours') En cours
                @elseif($chantier->statut == 'termine') Terminé
                @elseif($chantier->statut == 'suspendu') Suspendu
                @else Planifié
                @endif
            </td></tr>
        </table>
    </div>

    <div class="summary">
        <h3>💰 Synthèse financière</h3>
        <table>
            <tr><td class="label">Budget total</td><td>{{ number_format($chantier->budget, 2, ',', ' ') }} MAD</td></tr>
            <tr><td class="label">Dépenses engagées</td><td>{{ number_format($totalDepenses, 2, ',', ' ') }} MAD</td></tr>
            <tr><td class="label">Budget restant</td><td>{{ number_format($budgetRestant, 2, ',', ' ') }} MAD</td></tr>
            <tr><td class="label">Taux de consommation</td><td>{{ $pourcentageConsomme }}%</td></tr>
        </table>
        <div class="budget-bar">
            <div class="budget-fill {{ $pourcentageConsomme > 80 ? 'budget-warning' : '' }}" style="width: {{ min($pourcentageConsomme, 100) }}%"></div>
        </div>
    </div>

    <h3>📋 Détail des dépenses ({{ $depenses->count() }})</h3>
    <table class="data">
        <thead>
            <tr>
                <th>Date</th>
                <th>Désignation</th>
                <th>Fournisseur</th>
                <th>N° Facture</th>
                <th>Montant</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($depenses as $d)
            <tr>
                <td>{{ \Carbon\Carbon::parse($d->date)->format('d/m/Y') }}</td>
                <td>{{ $d->designation }}</td>
                <td>{{ $d->fournisseur->nom ?? '-' }}</td>
                <td>{{ $d->numero_facture ?? '-' }}</td>
                <td style="text-align: right">{{ number_format($d->montant, 2, ',', ' ') }} MAD</td>
                <td>
                    @if($d->statut_paiement == 'paye') Payé
                    @elseif($d->statut_paiement == 'en_attente') En attente
                    @else Partiellement payé
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align: center">Aucune dépense enregistrée pour ce chantier</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="4" style="text-align: right"><strong>TOTAL :</strong></td>
                <td style="text-align: right"><strong>{{ number_format($totalDepenses, 2, ',', ' ') }} MAD</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Himatech – Système centralisé de suivi des dépenses par chantier</p>
        <p>Document généré automatiquement - {{ date('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html>