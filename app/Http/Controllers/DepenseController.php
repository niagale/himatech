<?php

namespace App\Http\Controllers;

use App\Models\Depense;
use App\Models\Chantier;
use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class DepenseController extends Controller
{
    public function __construct()
    {
        // Vérification des droits pour la modification des factures
        // Admin, Achat et Finance peuvent modifier les factures
        $this->middleware(function ($request, $next) {
            $action = $request->route()->getActionMethod();
            
            if (in_array($action, ['edit', 'update'])) {
                if (!in_array(auth()->user()->role, ['admin', 'achat', 'finance'])) {
                    abort(403, 'Accès non autorisé. Réservé aux services Achats et Finance.');
                }
            }
            return $next($request);
        })->only(['edit', 'update']);
    }

    public function index(Request $request)
    {
        $query = Depense::with(['chantier', 'fournisseur', 'user']);

        if ($request->chantier_id) $query->where('chantier_id', $request->chantier_id);
        if ($request->fournisseur_id) $query->where('fournisseur_id', $request->fournisseur_id);
        if ($request->statut_paiement) $query->where('statut_paiement', $request->statut_paiement);
        if ($request->statut_facture) $query->where('statut_facture', $request->statut_facture);
        if ($request->date_debut) $query->whereDate('date', '>=', $request->date_debut);
        if ($request->date_fin)   $query->whereDate('date', '<=', $request->date_fin);
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('designation', 'like', "%{$request->search}%")
                  ->orWhere('numero_facture', 'like', "%{$request->search}%");
            });
        }

        $depenses     = $query->latest()->paginate(15)->withQueryString();
        $chantiers    = Chantier::all();
        $fournisseurs = Fournisseur::all();

        return view('depenses.index', compact('depenses', 'chantiers', 'fournisseurs'));
    }

    public function create()
    {
        $chantiers    = Chantier::orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        return view('depenses.create', compact('chantiers', 'fournisseurs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'chantier_id'     => 'required|exists:chantiers,id',
            'designation'     => 'required|string',
            'montant'         => 'required|numeric|min:0',
            'date'            => 'required|date',
            'fournisseur_id'  => 'nullable|exists:fournisseurs,id',
            'statut_paiement' => 'required|in:en_attente,paye,partiellement_paye',
            'piece_jointe'    => 'nullable|file|mimes:pdf,jpg,jpeg,png,xlsx|max:5120',
        ]);

        $data = $request->except('piece_jointe');
        $data['user_id'] = auth()->id();

        if ($request->hasFile('piece_jointe')) {
            $data['piece_jointe'] = $request->file('piece_jointe')->store('depenses', 'public');
        }

        Depense::create($data);
        return redirect()->route('depenses.index')->with('success', 'Dépense enregistrée avec succès.');
    }

    public function show(Depense $depense)
    {
        return view('depenses.show', compact('depense'));
    }

    public function edit(Depense $depense)
    {
        $chantiers    = Chantier::orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        return view('depenses.edit', compact('depense', 'chantiers', 'fournisseurs'));
    }

    public function update(Request $request, Depense $depense)
    {
        $rules = [
            'mode_paiement' => 'nullable|string',
            'statut_facture' => 'nullable|in:non_payee,payee',
            'commentaire' => 'nullable|string',
            'numero_bl' => 'nullable|string',
            'numero_facture' => 'nullable|string',
        ];
        
        $request->validate($rules);

        $data = [
            'mode_paiement' => $request->mode_paiement,
            'statut_facture' => $request->statut_facture,
            'commentaire' => $request->commentaire,
            'numero_bl' => $request->numero_bl,
            'numero_facture' => $request->numero_facture,
        ];

        $depense->update($data);
        
        return redirect()->route('depenses.show', $depense)->with('success', 'Facture mise à jour avec succès.');
    }

    public function destroy(Depense $depense)
    {
        $depense->delete();
        return redirect()->route('depenses.index')->with('success', 'Dépense supprimée.');
    }

    public function export()
    {
        $depenses = Depense::with(['chantier', 'fournisseur'])->get();
        $filename = 'depenses_' . date('Y-m-d') . '.csv';

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename"];

        $callback = function() use ($depenses) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Chantier', 'Fournisseur', 'Désignation', 'Montant', 'Date', 'N° BC', 'N° BL', 'N° Facture', 'Statut Facture'], ';');
            foreach ($depenses as $d) {
                fputcsv($file, [
                    $d->id, $d->chantier->nom ?? '', $d->fournisseur->nom ?? '',
                    $d->designation, $d->montant, $d->date->format('d/m/Y'),
                    $d->numero_bc ?? '', $d->numero_bl ?? '', $d->numero_facture ?? '',
                    $d->statut_facture ?? 'non_payee'
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $depenses = Depense::with(['chantier', 'fournisseur'])->latest()->get();
        $totalDepenses = $depenses->sum('montant');
        $totalBudget = Chantier::sum('budget');
        $budgetRestant = $totalBudget - $totalDepenses;
        
        $pdf = Pdf::loadView('depenses.rapport-pdf', compact('depenses', 'totalDepenses', 'totalBudget', 'budgetRestant'));
        return $pdf->download('rapport_depenses_' . date('Y-m-d') . '.pdf');
    }

    public function exportRapportMensuel(Request $request)
    {
        $mois = $request->mois ?? date('m');
        $annee = $request->annee ?? date('Y');
        
        $depenses = Depense::with(['chantier', 'fournisseur'])
            ->whereMonth('date', $mois)
            ->whereYear('date', $annee)
            ->get();
        
        $totalDepenses = $depenses->sum('montant');
        
        $moisPrecedent = $mois == 1 ? 12 : $mois - 1;
        $anneePrecedente = $mois == 1 ? $annee - 1 : $annee;
        
        $totalDepensesMoisPrecedent = Depense::whereMonth('date', $moisPrecedent)
            ->whereYear('date', $anneePrecedente)
            ->sum('montant');
        
        $evolution = $totalDepensesMoisPrecedent > 0 
            ? round(($totalDepenses - $totalDepensesMoisPrecedent) / $totalDepensesMoisPrecedent * 100, 1) 
            : 0;
        
        $topFournisseurs = Fournisseur::withSum(['depenses' => function($q) use ($mois, $annee) {
            $q->whereMonth('date', $mois)->whereYear('date', $annee);
        }], 'montant')
            ->orderByDesc('depenses_sum_montant')
            ->limit(5)
            ->get();
        
        $nomMois = date('F Y', mktime(0, 0, 0, $mois, 1, $annee));
        
        $pdf = Pdf::loadView('depenses.rapport-mensuel', compact(
            'depenses', 'totalDepenses', 'evolution', 'mois', 'annee', 
            'nomMois', 'topFournisseurs', 'totalDepensesMoisPrecedent'
        ));
        
        return $pdf->download('rapport_mensuel_' . $annee . '_' . $mois . '.pdf');
    }

    public function exportSinglePdf(Depense $depense)
    {
        $depense->load(['chantier', 'fournisseur']);
        $pdf = Pdf::loadView('depenses.rapport-single-pdf', compact('depense'));
        return $pdf->download('depense_' . $depense->id . '_' . date('Y-m-d') . '.pdf');
    }

    public function exportSingleCsv(Depense $depense)
    {
        $depense->load(['chantier', 'fournisseur']);
        $filename = 'depense_' . $depense->id . '_' . date('Y-m-d') . '.csv';

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$filename"];

        $callback = function() use ($depense) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Chantier', 'Fournisseur', 'Désignation', 'Montant', 'Date', 'N° BC', 'N° BL', 'N° Facture', 'Statut Facture'], ';');
            fputcsv($file, [
                $depense->chantier->nom ?? '',
                $depense->fournisseur->nom ?? '',
                $depense->designation,
                $depense->montant,
                $depense->date->format('d/m/Y'),
                $depense->numero_bc ?? '',
                $depense->numero_bl ?? '',
                $depense->numero_facture ?? '',
                $depense->statut_facture == 'payee' ? 'Payée' : 'Non payée'
            ], ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}