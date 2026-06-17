<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Détail de la dépense</title>
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
        .serif{ font-family:'Bookman Old Style','URW Bookman','Palatino Linotype',Georgia,serif; }

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

        .detail-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .detail-table td { padding: 8px 12px; border: 1px solid #ddd; }
        .detail-table .label { background: #f3f4f6; font-weight: bold; width: 30%; }

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
        <h2>Détail de la dépense</h2>
        <p>Généré le {{ date('d/m/Y H:i') }}</p>
    </div>

    <table class="detail-table">
        <tr><td class="label">Chantier</td><td>{{ $depense->chantier->nom ?? '-' }}</td></tr>
        <tr><td class="label">Fournisseur</td><td>{{ $depense->fournisseur->nom ?? '-' }}</td></tr>
        <tr><td class="label">Désignation</td><td>{{ $depense->designation }}</td></tr>
        <tr><td class="label">Montant</td><td><strong>{{ number_format($depense->montant, 2, ',', ' ') }} MAD</strong></td></tr>
        <tr><td class="label">Date</td><td>{{ $depense->date->format('d/m/Y') }}</td></tr>
        <tr><td class="label">N° BC</td><td>{{ $depense->numero_bc ?? '-' }}</td></tr>
        <tr><td class="label">N° BL</td><td>{{ $depense->numero_bl ?? '-' }}</td></tr>
        <tr><td class="label">N° Facture</td><td>{{ $depense->numero_facture ?? '-' }}</td></tr>
        <tr><td class="label">Mode de paiement</td><td>{{ $depense->mode_paiement ?? '-' }}</td></tr>
        <tr><td class="label">Statut facture</td><td>{{ $depense->statut_facture == 'payee' ? 'Payée' : 'Non payée' }}</td></tr>
        @if($depense->commentaire)
        <tr><td class="label">Commentaire</td><td>{{ $depense->commentaire }}</td></tr>
        @endif
    </table>

    <div class="footer">
        <div>10 allée des Mandariniers, Villa N°1 Ain Sebaa - Casablanca / Tél : 05 22 35 30 24 - Fax : 05 22 35 60 73</div>
        <div>Email: himatechsarl@gmail.com / R.C :225969 - TP: 37961614 - IF: 40233132 - CNSS : 8578702 - GSM : 06 61 95 27 23</div>
        <div class="ice">ICE 000222504000087</div>
    </div>

</div>

</body>
</html>