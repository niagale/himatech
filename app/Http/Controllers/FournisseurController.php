<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs = Fournisseur::withSum('depenses', 'montant')->latest()->paginate(10);
        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function create()
    {
        return view('fournisseurs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'       => 'required|string|max:255',
            'telephone' => 'nullable|string',
            'email'     => 'nullable|email',
            'adresse'   => 'nullable|string',
            'ice'       => 'nullable|string',
        ]);

        Fournisseur::create($request->all());
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur ajouté.');
    }

    public function show(Fournisseur $fournisseur)
    {
        $depenses = $fournisseur->depenses()->with('chantier')->latest()->paginate(10);
        return view('fournisseurs.show', compact('fournisseur', 'depenses'));
    }

    public function edit(Fournisseur $fournisseur)
    {
        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(Request $request, Fournisseur $fournisseur)
    {
        $request->validate([
            'nom'   => 'required|string|max:255',
            'email' => 'nullable|email',
        ]);

        $fournisseur->update($request->all());
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur mis à jour.');
    }

    public function destroy(Fournisseur $fournisseur)
    {
        $fournisseur->delete();
        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur supprimé.');
    }

    public function exportAllPdf()
    {
        $fournisseurs = Fournisseur::withSum('depenses', 'montant')->latest()->get();
        $totalDepenses = $fournisseurs->sum('depenses_sum_montant');
        
        $pdf = Pdf::loadView('fournisseurs.rapport-all-pdf', compact('fournisseurs', 'totalDepenses'));
        return $pdf->download('liste_fournisseurs_' . date('Y-m-d') . '.pdf');
    }
}