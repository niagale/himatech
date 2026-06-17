<?php

namespace App\Http\Controllers;

use App\Models\BonCommande;
use App\Models\BonCommandeArticle;
use App\Models\Chantier;
use App\Models\Fournisseur;
use App\Models\Depense;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class BonCommandeController extends Controller
{
    public function __construct()
    {
        // Vérification des droits pour les actions sensibles
        $this->middleware(function ($request, $next) {
            $actions = ['create', 'store', 'edit', 'update', 'destroy'];
            if (in_array($request->route()->getActionMethod(), $actions)) {
                if (!in_array(auth()->user()->role, ['admin', 'achat'])) {
                    abort(403, 'Accès non autorisé. Réservé au service Achats.');
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        $bonCommandes = BonCommande::with(['chantier', 'fournisseur', 'user'])->latest()->paginate(10);
        return view('bon-commandes.index', compact('bonCommandes'));
    }

    public function create()
    {
        $chantiers = Chantier::orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        return view('bon-commandes.create', compact('chantiers', 'fournisseurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'chantier_id' => 'required|exists:chantiers,id',
            'fournisseur_id' => 'required|exists:fournisseurs,id',
            'date_commande' => 'required|date',
            'tva' => 'required|numeric|min:0|max:100',
            'mode_paiement' => 'nullable|string',
            'articles' => 'required|array|min:1',
            'articles.*.designation' => 'required|string',
            'articles.*.quantite' => 'required|integer|min:1',
            'articles.*.prix_unitaire_ht' => 'required|numeric|min:0',
        ]);

        $numeroBC = 'BC-' . date('Ymd') . '-' . str_pad(BonCommande::count() + 1, 4, '0', STR_PAD_LEFT);

        $bonCommande = BonCommande::create([
            'numero_bc' => $numeroBC,
            'chantier_id' => $request->chantier_id,
            'fournisseur_id' => $request->fournisseur_id,
            'user_id' => auth()->id(),
            'date_commande' => $request->date_commande,
            'date_livraison_prevue' => $request->date_livraison_prevue,
            'tva' => $request->tva,
            'mode_paiement' => $request->mode_paiement,
            'commentaire' => $request->commentaire,
            'statut' => 'en_attente',
            'total_ht' => 0,
            'total_tva' => 0,
            'total_ttc' => 0,
        ]);

        $totalHT = 0;

        foreach ($request->articles as $article) {
            $montantHT = $article['quantite'] * $article['prix_unitaire_ht'];
            $totalHT += $montantHT;

            BonCommandeArticle::create([
                'bon_commande_id' => $bonCommande->id,
                'code_article' => $article['code_article'] ?? null,
                'designation' => $article['designation'],
                'unite' => $article['unite'] ?? 'pièce',
                'quantite' => $article['quantite'],
                'prix_unitaire_ht' => $article['prix_unitaire_ht'],
                'montant_ht' => $montantHT,
            ]);
        }

        $bonCommande->total_ht = $totalHT;
        $bonCommande->total_tva = $totalHT * ($bonCommande->tva / 100);
        $bonCommande->total_ttc = $totalHT + $bonCommande->total_tva;
        $bonCommande->save();

        $this->genererDepense($bonCommande);

        return redirect()->route('bon-commandes.index')->with('success', "Bon de commande $numeroBC créé avec succès.");
    }

    private function genererDepense(BonCommande $bonCommande)
    {
        Depense::create([
            'chantier_id' => $bonCommande->chantier_id,
            'fournisseur_id' => $bonCommande->fournisseur_id,
            'user_id' => auth()->id(),
            'bon_commande_id' => $bonCommande->id,
            'designation' => 'Commande ' . $bonCommande->numero_bc,
            'montant' => $bonCommande->total_ttc,
            'date' => $bonCommande->date_commande,
            'numero_bc' => $bonCommande->numero_bc,
            'mode_paiement' => $bonCommande->mode_paiement,
            'statut_paiement' => 'en_attente',
            'statut_facture' => 'non_payee',
            'type' => 'bc',
        ]);
    }

    public function show(BonCommande $bonCommande)
    {
        $bonCommande->load(['articles', 'chantier', 'fournisseur', 'user']);
        return view('bon-commandes.show', compact('bonCommande'));
    }

    public function edit(BonCommande $bonCommande)
    {
        $chantiers = Chantier::orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        $bonCommande->load('articles');
        return view('bon-commandes.edit', compact('bonCommande', 'chantiers', 'fournisseurs'));
    }

    public function update(Request $request, BonCommande $bonCommande)
    {
        if (!in_array(auth()->user()->role, ['admin', 'achat'])) {
            abort(403, 'Accès non autorisé. Réservé au service Achats.');
        }

        $request->validate([
            'date_livraison_prevue' => 'nullable|date',
            'commentaire' => 'nullable|string',
        ]);

        $bonCommande->update($request->only(['date_livraison_prevue', 'commentaire']));

        return redirect()->route('bon-commandes.index')->with('success', 'Bon de commande mis à jour.');
    }

    public function exportPdf(BonCommande $bonCommande)
    {
        $bonCommande->load(['articles', 'chantier', 'fournisseur']);
        $pdf = Pdf::loadView('bon-commandes.pdf', compact('bonCommande'));
        return $pdf->download('BC_' . $bonCommande->numero_bc . '.pdf');
    }

    public function exportAllPdf()
    {
        $bonCommandes = BonCommande::with(['chantier', 'fournisseur', 'user'])->latest()->get();
        $totalTTC = $bonCommandes->sum('total_ttc');
        
        $pdf = Pdf::loadView('bon-commandes.rapport-all-pdf', compact('bonCommandes', 'totalTTC'));
        return $pdf->download('liste_bons_commande_' . date('Y-m-d') . '.pdf');
    }

    public function destroy(BonCommande $bonCommande)
    {
        $bonCommande->delete();
        return redirect()->route('bon-commandes.index')->with('success', 'Bon de commande supprimé.');
    }
}