<?php

namespace App\Console\Commands;

use App\Models\Chantier;
use App\Models\Fournisseur;
use App\Models\BonCommande;
use App\Models\BonCommandeArticle;
use App\Models\Depense;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportExcelData extends Command
{
    protected $signature = 'import:excel {file}';
    protected $description = 'Importe les données depuis un fichier Excel (Feuil1 et Feuil2)';

    public function handle()
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("Fichier introuvable : $filePath");
            return 1;
        }

        $this->info("📂 Lecture du fichier : " . basename($filePath));

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheetCount = $spreadsheet->getSheetCount();
            $allData = [];

            // ============================================
            // 1. LECTURE DE TOUTES LES FEUILLES
            // ============================================
            for ($sheetIndex = 0; $sheetIndex < $sheetCount; $sheetIndex++) {
                $worksheet = $spreadsheet->getSheet($sheetIndex);
                $sheetName = $worksheet->getTitle();
                $rows = $worksheet->toArray();

                $this->info("📄 Feuille " . ($sheetIndex + 1) . " : $sheetName");

                $fournisseurActuel = null;
                $isFirstDataRow = true;

                foreach ($rows as $index => $row) {
                    // --- Ignorer les lignes d'en-tête ---
                    if ($index == 0 && isset($row[0]) && trim($row[0]) == 'Fournisseur') {
                        continue;
                    }
                    if ($index <= 4 && (empty(trim($row[0] ?? '')) || trim($row[0] ?? '') == 'BC')) {
                        continue;
                    }

                    // --- Ignorer la ligne "Numero BC" ---
                    if (trim($row[2] ?? '') == 'Numero BC' || trim($row[2] ?? '') == 'BC') {
                        continue;
                    }

                    // --- Ignorer les lignes vides ---
                    if (empty(trim($row[2] ?? '')) && empty(trim($row[3] ?? ''))) {
                        continue;
                    }

                    // --- Gestion du fournisseur ---
                    $col0 = trim($row[0] ?? '');
                    if (!empty($col0) && $col0 != 'HM' && $col0 != 'HM Casablanca') {
                        $fournisseurActuel = $col0;
                    }

                    $fournisseur = $fournisseurActuel ?: trim($row[0] ?? '');

                    // --- Nettoyer le numéro de BC ---
                    $numeroBC = trim($row[2] ?? '');
                    if (empty($numeroBC)) {
                        continue;
                    }

                    // --- Nettoyer la date ---
                    $date = $this->parseDate($row[1] ?? '');

                    // --- Nettoyer les valeurs numériques ---
                    $designation = trim($row[3] ?? '');
                    $unite = trim($row[4] ?? '') ?: 'pièce';
                    $quantite = $this->parseFloat($row[5] ?? 0);
                    $prixUnitaire = $this->parseFloat($row[6] ?? 0);

                    // --- Montant HT ---
                    $montantHTRaw = $row[7] ?? null;
                    if (is_string($montantHTRaw) && str_starts_with(trim($montantHTRaw), '=')) {
                        $montantHTRaw = null;
                    }
                    $montantHT = $this->parseFloat($montantHTRaw);
                    if ($montantHT == 0 && $quantite > 0 && $prixUnitaire > 0) {
                        $montantHT = $quantite * $prixUnitaire;
                    }

                    // --- Infos facturation (pour dépense) ---
                    $facture = trim($row[8] ?? '');
                    $numeroBL = trim($row[9] ?? '');
                    $paiement = trim($row[10] ?? '');
                    $chantierNom = trim($row[11] ?? '');

                    if (empty($chantierNom)) {
                        continue;
                    }

                    $allData[] = [
                        'fournisseur' => $fournisseur ?: 'Fournisseur inconnu',
                        'date' => $date,
                        'numero_bc' => $numeroBC,
                        'designation' => $designation,
                        'unite' => $unite,
                        'quantite' => $quantite ?: 1,
                        'prix_unitaire_ht' => $prixUnitaire ?: 0,
                        'montant_ht' => $montantHT,
                        'numero_facture' => $facture,
                        'numero_bl' => $numeroBL,
                        'paiement' => $paiement,
                        'chantier' => $chantierNom,
                        'sheet' => $sheetName,
                    ];
                }
            }

            $this->info("✅ " . count($allData) . " lignes valides récupérées");

            // ============================================
            // 2. IMPORT DES CHANTIERS
            // ============================================
            $this->line("\n🏗️  Import des chantiers...");
            $chantiers = collect($allData)->pluck('chantier')->filter()->unique();

            foreach ($chantiers as $nom) {
                if (empty($nom)) continue;
                $nom = trim($nom);
                if (strlen($nom) < 2) continue;

                $chantier = Chantier::where('nom', $nom)->first();
                if (!$chantier) {
                    $code = 'CHT-' . str_pad(Chantier::count() + 1, 3, '0', STR_PAD_LEFT);
                    Chantier::create([
                        'nom' => $nom,
                        'code' => $code,
                        'responsable' => 'À définir',
                        'budget' => 0,
                        'date_debut' => now(),
                        'statut' => 'en_cours',
                        'localisation' => 'Non renseignée',
                    ]);
                    $this->info("  ✅ Chantier créé : $nom ($code)");
                }
            }
            $this->info("✅ " . Chantier::count() . " chantiers importés");

            // ============================================
            // 3. IMPORT DES FOURNISSEURS
            // ============================================
            $this->line("\n🏭 Import des fournisseurs...");
            $fournisseurs = collect($allData)->pluck('fournisseur')->filter()->unique();

            foreach ($fournisseurs as $nom) {
                if (empty($nom)) continue;
                $nom = trim($nom);
                if (strlen($nom) < 2) continue;

                $fournisseur = Fournisseur::where('nom', $nom)->first();
                if (!$fournisseur) {
                    Fournisseur::create([
                        'nom' => $nom,
                        'telephone' => 'À renseigner',
                        'email' => 'À renseigner',
                        'adresse' => 'À renseigner',
                        'ice' => 'À renseigner',
                    ]);
                    $this->info("  ✅ Fournisseur créé : $nom");
                }
            }
            $this->info("✅ " . Fournisseur::count() . " fournisseurs importés");

            // ============================================
            // 4. IMPORT DES BONS DE COMMANDE
            // ============================================
            $this->line("\n📄 Import des bons de commande...");

            $groupedByBC = collect($allData)->groupBy('numero_bc');
            $bcCount = 0;
            $articleCount = 0;

            foreach ($groupedByBC as $numeroBC => $items) {
                // Nettoyer le numéro de BC pour éviter les erreurs
                if (strlen(trim($numeroBC)) < 4) {
                    continue;
                }

                $items = $items->filter(function($item) {
                    return !empty($item['designation']) && $item['quantite'] > 0;
                });

                if ($items->count() == 0) continue;

                $first = $items->first();

                // Récupérer ou créer le fournisseur
                $fournisseur = Fournisseur::where('nom', $first['fournisseur'])->first();
                if (!$fournisseur) {
                    $fournisseur = Fournisseur::create([
                        'nom' => $first['fournisseur'] ?: 'Fournisseur inconnu',
                        'telephone' => 'À renseigner',
                        'email' => 'À renseigner',
                        'adresse' => 'À renseigner',
                        'ice' => 'À renseigner',
                    ]);
                }

                // Récupérer ou créer le chantier
                $chantier = Chantier::where('nom', $first['chantier'])->first();
                if (!$chantier) {
                    $chantier = Chantier::create([
                        'nom' => $first['chantier'] ?: 'Chantier inconnu',
                        'code' => 'CHT-' . str_pad(Chantier::count() + 1, 3, '0', STR_PAD_LEFT),
                        'responsable' => 'À définir',
                        'budget' => 0,
                        'date_debut' => now(),
                        'statut' => 'en_cours',
                        'localisation' => 'Non renseignée',
                    ]);
                }

                // Vérifier si le BC existe déjà
                $bonCommande = BonCommande::where('numero_bc', $numeroBC)->first();

                if (!$bonCommande) {
                    $bonCommande = BonCommande::create([
                        'numero_bc' => $numeroBC,
                        'chantier_id' => $chantier->id,
                        'fournisseur_id' => $fournisseur->id,
                        'user_id' => 1,
                        'date_commande' => $first['date'] ?: now(),
                        'date_livraison_prevue' => null,
                        'tva' => 20,
                        'mode_paiement' => null,
                        'commentaire' => 'Importé depuis Excel',
                        'statut' => 'en_attente',
                        'total_ht' => 0,
                        'total_tva' => 0,
                        'total_ttc' => 0,
                    ]);

                    $bcCount++;
                    $this->info("  ✅ BC créé : $numeroBC");

                    foreach ($items as $item) {
                        $montantHT = $item['montant_ht'] ?: ($item['quantite'] * $item['prix_unitaire_ht']);

                        BonCommandeArticle::create([
                            'bon_commande_id' => $bonCommande->id,
                            'code_article' => null,
                            'designation' => $item['designation'],
                            'unite' => $item['unite'] ?: 'pièce',
                            'quantite' => $item['quantite'] ?: 1,
                            'prix_unitaire_ht' => $item['prix_unitaire_ht'] ?: 0,
                            'montant_ht' => $montantHT,
                        ]);
                        $articleCount++;
                    }

                    // Mettre à jour les totaux
                    $totalHT = $bonCommande->articles()->sum('montant_ht');
                    $bonCommande->total_ht = $totalHT;
                    $bonCommande->total_tva = $totalHT * ($bonCommande->tva / 100);
                    $bonCommande->total_ttc = $totalHT + $bonCommande->total_tva;
                    $bonCommande->save();

                    // ============================================
                    // 5. GÉNÉRER LA DÉPENSE AVEC STATUT PAIEMENT
                    // ============================================
                    $facture = $first['numero_facture'] ?? null;
                    $bl = $first['numero_bl'] ?? null;
                    $paiementRaw = $first['paiement'] ?? null;

                    // Déterminer le statut de paiement
                    $statutPaiement = 'en_attente';
                    $statutFacture = 'non_payee';
                    
                    if (!empty($paiementRaw)) {
                        $paiementClean = strtoupper(trim($paiementRaw));
                        if ($paiementClean == 'P' || $paiementClean == 'PAYE' || $paiementClean == 'PAYÉ') {
                            $statutPaiement = 'paye';
                            $statutFacture = 'payee';
                        }
                    }

                    Depense::create([
                        'chantier_id' => $chantier->id,
                        'fournisseur_id' => $fournisseur->id,
                        'user_id' => 1,
                        'bon_commande_id' => $bonCommande->id,
                        'designation' => 'Commande ' . $bonCommande->numero_bc,
                        'montant' => $bonCommande->total_ttc,
                        'date' => $bonCommande->date_commande,
                        'numero_bc' => $bonCommande->numero_bc,
                        'numero_bl' => $bl,
                        'numero_facture' => $facture,
                        'mode_paiement' => $this->cleanPaiement($paiementRaw),
                        'statut_paiement' => $statutPaiement,
                        'statut_facture' => $statutFacture,
                        'type' => 'bc',
                        'commentaire' => 'Importé depuis Excel',
                    ]);

                } else {
                    $this->warn("  ⚠️ BC déjà existant : $numeroBC");
                }
            }

            $this->info("✅ $bcCount bons de commande importés");
            $this->info("✅ $articleCount articles importés");

            // ============================================
            // RÉCAPITULATIF
            // ============================================
            $this->line("\n==========================================");
            $this->line("📊 RÉCAPITULATIF DE L'IMPORT");
            $this->line("==========================================");
            $this->line("Chantiers : " . Chantier::count());
            $this->line("Fournisseurs : " . Fournisseur::count());
            $this->line("Bons de commande : " . BonCommande::count());
            $this->line("Articles : " . BonCommandeArticle::count());
            $this->line("Dépenses : " . Depense::count());
            $this->line("==========================================");

            $this->info("\n🎉 Import terminé avec succès !");

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Erreur : " . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
    }

    private function parseFloat($value): float
    {
        if ($value === null || $value === '') return 0;

        $clean = (string)$value;
        $clean = str_replace([' ', "\u{00A0}", ','], ['', '', '.'], $clean);

        if (substr_count($clean, '.') > 1) {
            $parts = explode('.', $clean);
            $last = array_pop($parts);
            $clean = implode('', $parts) . '.' . $last;
        }

        return floatval(preg_replace('/[^0-9.\-]/', '', $clean)) ?: 0;
    }

    private function cleanPaiement($value)
    {
        if (empty($value)) return null;

        $value = trim($value);

        if (in_array($value, ['p', 'NP'])) {
            return $value;
        }

        $map = [
            'virement' => 'virement',
            'cheque' => 'cheque',
            'chèque' => 'cheque',
            'especes' => 'especes',
            'espèces' => 'especes',
            'credit' => 'credit',
            'carte' => 'carte',
            'autre' => 'autre',
        ];

        $lower = strtolower($value);
        foreach ($map as $key => $val) {
            if (strtolower($key) === $lower || strpos($lower, $key) !== false) {
                return $val;
            }
        }

        return $value;
    }

    private function parseDate($value)
    {
        if (empty($value)) return now();

        if (is_numeric($value) && $value > 1000) {
            $unix = ($value - 25569) * 86400;
            return date('Y-m-d', $unix);
        }

        try {
            return date('Y-m-d', strtotime($value));
        } catch (\Exception $e) {
            return now();
        }
    }
}