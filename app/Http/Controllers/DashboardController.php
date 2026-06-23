<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use App\Models\Depense;
use App\Models\Fournisseur;
use App\Models\BonCommande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Récupération des filtres
        $chantierId = $request->chantier_id;
        $dateDebut = $request->date_debut;
        $dateFin = $request->date_fin;

        // Construction de la requête de base avec filtres
        $depensesQuery = Depense::query();
        
        if ($chantierId) {
            $depensesQuery->where('chantier_id', $chantierId);
        }
        if ($dateDebut) {
            $depensesQuery->whereDate('date', '>=', $dateDebut);
        }
        if ($dateFin) {
            $depensesQuery->whereDate('date', '<=', $dateFin);
        }

        // Indicateurs clés (KPI)
        $totalDepenses = $depensesQuery->sum('montant');
        $totalBudget = $chantierId ? (Chantier::find($chantierId)?->budget ?? 0) : Chantier::sum('budget');
        $nombreChantiers = $chantierId ? 1 : Chantier::count();
        $nombreDepenses = $depensesQuery->count();
        $budgetRestant = $totalBudget - $totalDepenses;
        $pourcentageConsomme = $totalBudget > 0 ? round(($totalDepenses / $totalBudget) * 100, 1) : 0;

        // Nombre de bons de commande
        $bonCommandesCount = BonCommande::count();

        // Dépenses par chantier (avec filtres de date)
        $depensesParChantier = Chantier::withSum(['depenses' => function($q) use ($dateDebut, $dateFin) {
            if ($dateDebut) $q->whereDate('date', '>=', $dateDebut);
            if ($dateFin) $q->whereDate('date', '<=', $dateFin);
        }], 'montant')->get()
            ->map(fn($c) => [
                'nom'    => $c->nom,
                'total'  => $c->depenses_sum_montant ?? 0,
                'budget' => $c->budget,
                'pourcentage' => $c->budget > 0 ? round(($c->depenses_sum_montant ?? 0) / $c->budget * 100, 1) : 0
            ]);

        // Dépenses mensuelles (avec filtres)
        $depensesMensuelles = Depense::select(
            DB::raw('MONTH(date) as mois'),
            DB::raw('YEAR(date) as annee'),
            DB::raw('SUM(montant) as total')
        )
        ->when($chantierId, fn($q) => $q->where('chantier_id', $chantierId))
        ->when($dateDebut, fn($q) => $q->whereDate('date', '>=', $dateDebut))
        ->when($dateFin, fn($q) => $q->whereDate('date', '<=', $dateFin))
        ->whereYear('date', date('Y'))
        ->groupBy('annee', 'mois')
        ->orderBy('mois')
        ->get();

        // Top 5 fournisseurs (avec filtres)
        $topFournisseurs = Fournisseur::withSum(['depenses' => function($q) use ($chantierId, $dateDebut, $dateFin) {
            if ($chantierId) $q->where('chantier_id', $chantierId);
            if ($dateDebut) $q->whereDate('date', '>=', $dateDebut);
            if ($dateFin) $q->whereDate('date', '<=', $dateFin);
        }], 'montant')
            ->orderByDesc('depenses_sum_montant')
            ->limit(5)
            ->get();

        // Chantiers critiques (dépassement > 80%)
        $chantiersCritiques = Chantier::withSum(['depenses' => function($q) use ($dateDebut, $dateFin) {
            if ($dateDebut) $q->whereDate('date', '>=', $dateDebut);
            if ($dateFin) $q->whereDate('date', '<=', $dateFin);
        }], 'montant')
            ->get()
            ->filter(fn($c) => ($c->depenses_sum_montant ?? 0) > $c->budget * 0.8)
            ->map(fn($c) => [
                'nom' => $c->nom,
                'pourcentage' => $c->budget > 0 ? round(($c->depenses_sum_montant ?? 0) / $c->budget * 100, 1) : 0,
                'depenses' => $c->depenses_sum_montant ?? 0,
                'budget' => $c->budget
            ]);

        // Liste des chantiers pour le filtre
        $chantiers = Chantier::orderBy('nom')->get();

        // Évolution des dépenses (6 derniers mois)
        $evolution = Depense::select(
            DB::raw('DATE_FORMAT(date, "%Y-%m") as mois'),
            DB::raw('SUM(montant) as total')
        )
        ->when($chantierId, fn($q) => $q->where('chantier_id', $chantierId))
        ->when($dateDebut, fn($q) => $q->whereDate('date', '>=', $dateDebut))
        ->when($dateFin, fn($q) => $q->whereDate('date', '<=', $dateFin))
        ->whereDate('date', '>=', now()->subMonths(6))
        ->groupBy('mois')
        ->orderBy('mois')
        ->get();

        return view('dashboard.index', compact(
            'totalDepenses', 'totalBudget', 'nombreChantiers',
            'nombreDepenses', 'budgetRestant', 'pourcentageConsomme',
            'depensesParChantier', 'depensesMensuelles',
            'topFournisseurs', 'chantiersCritiques', 'chantiers',
            'chantierId', 'dateDebut', 'dateFin', 'evolution',
            'bonCommandesCount'
        ));
    }
}