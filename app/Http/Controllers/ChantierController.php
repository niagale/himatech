<?php

namespace App\Http\Controllers;

use App\Models\Chantier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ChantierController extends Controller
{
    public function index()
    {
        $chantiers = Chantier::withSum('depenses', 'montant')->latest()->paginate(10);
        return view('chantiers.index', compact('chantiers'));
    }

    public function create()
    {
        return view('chantiers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'          => 'required|string|max:255',
            'code'         => 'required|string|unique:chantiers',
            'responsable'  => 'required|string',
            'budget'       => 'required|numeric|min:0',
            'date_debut'   => 'required|date',
            'date_fin'     => 'nullable|date|after:date_debut',
            'statut'       => 'required|in:en_cours,termine,suspendu,planifie',
            'localisation' => 'nullable|string',
        ]);

        Chantier::create($request->all());
        return redirect()->route('chantiers.index')->with('success', 'Chantier créé avec succès.');
    }

    public function show(Chantier $chantier)
    {
        $depenses = $chantier->depenses()->with('fournisseur')->latest()->paginate(10);
        return view('chantiers.show', compact('chantier', 'depenses'));
    }

    public function edit(Chantier $chantier)
    {
        return view('chantiers.edit', compact('chantier'));
    }

    public function update(Request $request, Chantier $chantier)
    {
        $request->validate([
            'nom'         => 'required|string|max:255',
            'code'        => 'required|string|unique:chantiers,code,' . $chantier->id,
            'responsable' => 'required|string',
            'budget'      => 'required|numeric|min:0',
            'date_debut'  => 'required|date',
            'statut'      => 'required|in:en_cours,termine,suspendu,planifie',
        ]);

        $chantier->update($request->all());
        return redirect()->route('chantiers.index')->with('success', 'Chantier mis à jour.');
    }

    public function destroy(Chantier $chantier)
    {
        $chantier->delete();
        return redirect()->route('chantiers.index')->with('success', 'Chantier supprimé.');
    }

    public function exportPdf(Chantier $chantier)
    {
        $depenses = $chantier->depenses()->with('fournisseur')->latest()->get();
        $totalDepenses = $depenses->sum('montant');
        $budgetRestant = $chantier->budget - $totalDepenses;
        $pourcentageConsomme = $chantier->budget > 0 ? round(($totalDepenses / $chantier->budget) * 100, 1) : 0;
        
        $pdf = Pdf::loadView('chantiers.rapport-pdf', compact('chantier', 'depenses', 'totalDepenses', 'budgetRestant', 'pourcentageConsomme'));
        return $pdf->download('rapport_chantier_' . $chantier->code . '_' . date('Y-m-d') . '.pdf');
    }

    public function exportAllPdf()
    {
        $chantiers = Chantier::withSum('depenses', 'montant')->latest()->get();
        $totalBudget = $chantiers->sum('budget');
        $totalDepenses = $chantiers->sum('depenses_sum_montant');
        
        $pdf = Pdf::loadView('chantiers.rapport-all-pdf', compact('chantiers', 'totalBudget', 'totalDepenses'));
        return $pdf->download('liste_chantiers_' . date('Y-m-d') . '.pdf');
    }
}