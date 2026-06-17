<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de Commande - {{ $bonCommande->numero_bc }}</title>
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

        /* ---------- EN-TETE ---------- */
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

        /* ---------- BLOC N°, DATE/PAGE, CLIENT ---------- */
        .top-row{
            display:flex;
            justify-content:space-between;
            align-items:flex-start;
            gap:20px;
            margin:18px 0 14px 0;
            flex-wrap:wrap;
        }
        .doc-box{
            display:inline-block;
            border:1.5px solid var(--blue);
            border-radius:9px;
            padding:5px 14px;
            font-family:'Bookman Old Style','URW Bookman',Georgia,serif;
            font-weight:bold;
            color:var(--blue);
            font-size:13px;
            box-shadow:3px 3px 3px rgba(0,0,0,.3);
            margin-bottom:10px;
        }
        .doc-box span{
            color:var(--blue);
        }

        table.meta{
            border-collapse:collapse;
            font-family:'Bookman Old Style','URW Bookman',Georgia,serif;
            font-size:12px;
        }
        table.meta th, table.meta td{
            border:1px solid #000;
            padding:3px 14px;
            text-align:center;
        }
        table.meta th{ font-weight:bold; background:#f5f5f5; }

        .client-box{
            border:1.5px solid #000;
            border-radius:14px;
            padding:8px 16px 10px 14px;
            min-width:180px;
            box-shadow:3px 3px 3px rgba(0,0,0,.3);
            font-family:'Bookman Old Style','URW Bookman',Georgia,serif;
        }
        .client-box .name{
            font-weight:bold;
            font-size:14px;
        }
        .client-box .city{
            font-size:12px;
            margin-top:3px;
        }
        .client-box .ice-info{
            font-size:11px;
            margin-top:3px;
            color:#666;
        }

        /* ---------- INFOS CHANTIER ---------- */
        .infos-bar{
            display:flex;
            justify-content:space-between;
            flex-wrap:wrap;
            gap:8px;
            margin:8px 0 16px 0;
            font-family:'Bookman Old Style','URW Bookman',Georgia,serif;
            font-size:12px;
            background:#f5f5f5;
            padding:8px 12px;
            border-radius:8px;
            border:1px solid #ddd;
        }
        .infos-bar div{ margin:2px 0; }

        /* ---------- TEXTES ---------- */
        .serif-italic{
            font-family:'Bookman Old Style','URW Bookman',Georgia,serif;
            font-style:italic;
            font-size:13px;
        }
        p.serif-italic{ margin:10px 0; }

        /* ---------- TABLEAU ARTICLES ---------- */
        table.items{
            width:100%;
            border-collapse:collapse;
            font-size:11px;
        }
        table.items thead th{
            background:#000;
            color:#fff;
            font-weight:bold;
            text-align:center;
            padding:5px 4px;
            border:1px solid #fff;
            font-size:11px;
        }
        table.items tbody td{
            border:1px solid #000;
            padding:5px 6px;
            vertical-align:middle;
        }
        table.items tbody td.c1cell{ white-space:normal; }
        td.center{ text-align:center; }
        td.right{ text-align:right; }

        /* ---------- TOTAUX ---------- */
        .totals-wrap{ display:flex; justify-content:flex-end; margin-top:10px; }
        table.totals{
            border-collapse:collapse;
            font-family:'Bookman Old Style','URW Bookman',Georgia,serif;
            font-weight:bold;
            font-size:12px;
        }
        table.totals td{
            border:1px solid #000;
            padding:4px 10px;
        }
        table.totals td.label{ text-align:center; width:150px; }
        table.totals td.value{ text-align:right; width:80px; }
        table.totals td.total-ttc{
            font-size:15px;
            color:var(--blue);
        }

        /* ---------- SIGNATURE ---------- */
        .sign-box{
            border:1px solid #000;
            margin-top:40px;
        }
        .sign-box .lbl{
            font-family:'Bookman Old Style','URW Bookman',Georgia,serif;
            font-weight:bold;
            padding:4px 10px 0 10px;
            font-size:12px;
        }
        .sign-area{ height:70px; }
        .pay-row{
            border-top:1px solid #000;
            padding:4px 10px;
            font-family:'Bookman Old Style','URW Bookman',Georgia,serif;
            font-size:12px;
        }
        .pay-row div{ margin:2px 0; }
        .pay-row b{ font-weight:bold; }

        /* ---------- PIED DE PAGE ---------- */
        .footer{
            margin-top:30px;
            text-align:center;
            font-weight:bold;
            font-size:10px;
            color:var(--blue);
        }
        .footer .ice{ color:var(--red); margin-top:2px; }

        @media print{
            body{ padding:0; }
            .page{ padding:10mm; }
        }
    </style>
</head>
<body>

<div class="page">

    <!-- EN-TETE -->
    <div class="head">
        <span class="brand">HIMATECH<span class="form">s.a.r.l</span></span>
    </div>
    <div class="banner"><span>Travaux d'aménagement et Travaux Divers</span></div>
    <div class="rule"></div>

    <!-- N° DOC / DATE-PAGE / CLIENT -->
    <div class="top-row">
        <div>
            <div class="doc-box">Bon de Commande N° <span>{{ $bonCommande->numero_bc }}</span></div>
            <table class="meta">
                <tr><th>Date</th><th>Page</th></tr>
                <tr>
                    <td>{{ $bonCommande->date_commande->format('d/m/Y') }}</td>
                    <td>01</td>
                </tr>
            </table>
        </div>

        <div class="client-box">
            <div class="name">{{ $bonCommande->fournisseur->nom ?? 'Non renseigné' }}</div>
            <div class="city">{{ $bonCommande->fournisseur->adresse ?? 'Adresse non renseignée' }}</div>
            <div class="ice-info">ICE: {{ $bonCommande->fournisseur->ice ?? 'Non renseigné' }}</div>
        </div>
    </div>

    <!-- Chantier, Fournisseur, Statut, Paiement -->
    <div class="infos-bar">
        <div><strong>Chantier :</strong> {{ $bonCommande->chantier->nom ?? '-' }}</div>
        <div><strong>Fournisseur :</strong> {{ $bonCommande->fournisseur->nom ?? '-' }}</div>
        <div><strong>Statut :</strong> {{ ucfirst($bonCommande->statut) }}</div>
        <div><strong>Paiement :</strong> {{ $bonCommande->mode_paiement ?? 'Non spécifié' }}</div>
    </div>

    <p class="serif-italic">Messieurs,</p>
    <p class="serif-italic">Nous vous prions de bien vouloir nous faire livrer les articles objet du Bon de Commande suivant&nbsp;:</p>

    <!-- TABLEAU ARTICLES -->
    <table class="items">
        <thead>
            <tr>
                <th style="width:8%;">N°</th>
                <th style="width:42%;">Référence</th>
                <th style="width:8%;">U</th>
                <th style="width:12%;">Qté</th>
                <th style="width:15%;">P.U/H.T</th>
                <th style="width:15%;">P.T/H.T</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bonCommande->articles as $index => $article)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td class="c1cell">{{ $article->designation }}</td>
                <td class="center">{{ $article->unite }}</td>
                <td class="center">{{ number_format($article->quantite, 2, ',', ' ') }}</td>
                <td class="center">{{ number_format($article->prix_unitaire_ht, 2, ',', ' ') }}</td>
                <td class="right">{{ number_format($article->montant_ht, 2, ',', ' ') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-wrap">
        <table class="totals">
            <tr><td class="label">TOTAL HORS TAXE</td><td class="value">{{ number_format($bonCommande->total_ht, 2, ',', ' ') }}</td></tr>
            <tr><td class="label">T.V.A à {{ $bonCommande->tva }}%</td><td class="value">{{ number_format($bonCommande->total_tva, 2, ',', ' ') }}</td></tr>
            <tr><td class="label">TOTAL T.T.C</td><td class="value total-ttc">{{ number_format($bonCommande->total_ttc, 2, ',', ' ') }}</td></tr>
        </table>
    </div>

    <!-- SIGNATURE -->
    <div class="sign-box">
        <div class="lbl">Signature</div>
        <div class="sign-area"></div>
        <div class="pay-row">
            <div><b>Mode de paiement</b>&nbsp;&nbsp;{{ $bonCommande->mode_paiement ?? 'Non spécifié' }}</div>
            <div><b>Délai de livraison</b>&nbsp;&nbsp;{{ $bonCommande->date_livraison_prevue ? $bonCommande->date_livraison_prevue->format('d/m/Y') : 'À définir' }}</div>
        </div>
    </div>

    <!-- PIED DE PAGE -->
    <div class="footer">
        <div>10 allée des Mandariniers, Villa N°1 Ain Sebaa - Casablanca / Tél : 05 22 35 30 24 - Fax : 05 22 35 60 73</div>
        <div>Email: himatechsarl@gmail.com / R.C :225969 - TP: 37961614 - IF: 40233132 - CNSS : 8578702 - GSM : 06 61 95 27 23</div>
        <div class="ice">ICE 000222504000087</div>
    </div>

</div>

</body>
</html>