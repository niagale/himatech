<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des bons de commande</title>
    <style>
        :root{
            --blue:#0d0db2;
            --red:#e30613;
        }
        *{ box-sizing:border-box; margin:0; padding:0; }
        body{
            font-family: 'DejaVu Sans', 'Bookman Old Style', 'URW Bookman', Arial, sans-serif;
            background:#fff;
            padding:20px;
            font-size:12px;
        }
        .page{
            max-width:800px;
            margin:0 auto;
            background:#fff;
            padding:20px;
        }
        .head{
            text-align:center;
            margin-bottom:0;
        }
        .head .brand{
            font-family:'Bookman Old Style','URW Bookman',Georgia,serif;
            font-weight:bold;
            font-size:24px;
            color:var(--blue);
            letter-spacing:.5px;
        }
        .head .brand .form{
            font-size:14px;
            margin-left:8px;
        }
        .banner{
            background:#000;
            color:#fff;
            text-align:center;
            padding:4px 0;
            margin-top:4px;
        }
        .banner span{
            font-family:'Bookman Old Style','URW Bookman',Georgia,serif;
            font-style:italic;
            font-weight:bold;
            font-size:13px;
        }
        .rule{ border-top:2px solid #000; margin-top:3px; }

        .report-title{
            text-align:center;
            margin:18px 0 10px 0;
        }
        .report-title h2{
            font-family:'Bookman Old Style','URW Bookman',Georgia,serif;
            font-size:18px;
            color:var(--blue);
        }
        .report-title p{
            font-size:12px;
            color:#666;
        }

        .summary { margin-bottom: 20px; }
        .summary table { width: 100%; border-collapse: collapse; }
        .summary td { padding: 8px; border: 1px solid #ddd; }
        .summary .label { background: #f3f4f6; font-weight: bold; width: 30%; }

        table.data { width: 100%; border-collapse: collapse; margin-top: 20px; font-size:11px; }
        table.data th { background: #1e3a8a; color: white; padding: 6px 8px; text-align: left; }
        table.data td { padding: 5px 8px; border-bottom: 1px solid #ddd; }
        .total { font-weight: bold; background: #f3f4f6; }

        .footer{
            margin-top:30px;
            text-align:center;
            font-weight:bold;
            font-size:10px;
            color:var(--blue);
        }
        .footer .ice{ color:var(--red); margin-top:2px; }
    </style>
</head>
<body>

<div class="page">

    <div class="head">
        <span class="brand">HIMATECH<span class="form">s.a.r.l</span></span>
    </div>
    <div class="banner"><span>Travaux d'aménagement et Travaux Divers</span></div>
    <div class="rule"></div>

    <div class="report-title">
        <h2>Liste des bons de commande</h2>
        <p>Généré le {{ date('d/m/Y H:i') }}</p>
    </div>

    <div class="summary">
        <table>
            <tr><td class="label">📋 Total des bons de commande</td><td><strong>{{ $bonCommandes->count() }}</strong></td></tr>
            <tr><td class="label">💰 Total général TTC</td><td><strong>{{ number_format($totalTTC, 2, ',', ' ') }} MAD</strong></td></tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th>N° BC</th>
                <th>Chantier</th>
                <th>Fournisseur</th>
                <th style="text-align:center;">Date</th>
                <th style="text-align:right;">Total TTC</th>
                <th style="text-align:center;">Statut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bonCommandes as $bc)
            <tr>
                <td><span style="font-weight:bold; color:var(--blue);">{{ $bc->numero_bc }}</span></td>
                <td>{{ $bc->chantier->nom ?? '-' }}</td>
                <td>{{ $bc->fournisseur->nom ?? '-' }}</td>
                <td style="text-align:center;">{{ $bc->date_commande->format('d/m/Y') }}</td>
                <td style="text-align:right; font-weight:bold;">{{ number_format($bc->total_ttc, 2, ',', ' ') }} MAD</td>
                <td style="text-align:center;">
                    @php
                        $statusLabels = [
                            'en_attente' => 'En attente',
                            'accepte' => 'Accepté',
                            'partiel' => 'Partiel',
                            'recu' => 'Reçu'
                        ];
                    @endphp
                    {{ $statusLabels[$bc->statut] ?? $bc->statut }}
                </td>
            </tr>
            @empty
            <tr><td colspan="6" style="text-align:center; padding:20px;">Aucun bon de commande enregistré</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="4" style="text-align:right;"><strong>TOTAL</strong></td>
                <td style="text-align:right;"><strong>{{ number_format($totalTTC, 2, ',', ' ') }} MAD</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div>10 allée des Mandariniers, Villa N°1 Ain Sebaa - Casablanca / Tél : 05 22 35 30 24 - Fax : 05 22 35 60 73</div>
        <div>Email: himatechsarl@gmail.com / R.C :225969 - TP: 37961614 - IF: 40233132 - CNSS : 8578702 - GSM : 06 61 95 27 23</div>
        <div class="ice">ICE 000222504000087</div>
    </div>

</div>

</body>
</html>