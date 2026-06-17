<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ChantierController;
use App\Http\Controllers\DepenseController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\BonCommandeController;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Pages protégées
Route::middleware('auth')->group(function () {

    Route::get('/', fn() => redirect()->route('dashboard'));
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Chantiers
    Route::resource('chantiers', ChantierController::class);
    Route::get('/chantiers/export-pdf', [ChantierController::class, 'exportAllPdf'])->name('chantiers.export.all.pdf');
    Route::get('/chantiers/{chantier}/export-pdf', [ChantierController::class, 'exportPdf'])->name('chantiers.export.pdf');

    // Bons de commande
    Route::resource('bon-commandes', BonCommandeController::class);
    Route::get('/bon-commandes/export-pdf', [BonCommandeController::class, 'exportAllPdf'])->name('bon-commandes.export.all.pdf');
    Route::get('/bon-commandes/{bonCommande}/export-pdf', [BonCommandeController::class, 'exportPdf'])->name('bon-commandes.export.pdf');

    // Dépenses
    Route::resource('depenses', DepenseController::class);
    Route::get('/depenses/export/csv', [DepenseController::class, 'export'])->name('depenses.export');
    Route::get('/depenses/export/pdf', [DepenseController::class, 'exportPdf'])->name('depenses.export.pdf');
    Route::get('/depenses/export/mensuel', [DepenseController::class, 'exportRapportMensuel'])->name('depenses.export.mensuel');
    Route::get('/depenses/{depense}/export-pdf', [DepenseController::class, 'exportSinglePdf'])->name('depenses.export.single.pdf');
    Route::get('/depenses/{depense}/export-csv', [DepenseController::class, 'exportSingleCsv'])->name('depenses.export.single.csv');

    // Fournisseurs
    Route::resource('fournisseurs', FournisseurController::class);
    Route::get('/fournisseurs/export-pdf', [FournisseurController::class, 'exportAllPdf'])->name('fournisseurs.export.all.pdf');

    // Utilisateurs
    Route::resource('users', UserController::class);
    
    // Logs
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
});