<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Depense;
use App\Models\Chantier;
use App\Models\Fournisseur;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DepensesExcelSeeder extends Seeder
{
    /**
     * Mapping des valeurs "Paiement" du fichier Excel vers l'enum Laravel.
     */
    private array $paiementMap = [
        'virement'  => 'virement',
        'chèque'    => 'cheque',
        'cheque'    => 'cheque',
        'espèces'   => 'especes',
        'especes'   => 'especes',
        'cash'      => 'especes',
        'credit'    => 'credit',
        'carte'     => 'carte',
        'p'         => 'p',
        'np'        => 'NP',
    ];

    public function run(): void
    {
        // Placez le fichier Excel ici : storage/app/formatisation_initiale.xlsx
        $filePath = storage_path('app/formatisation_initiale.xlsx');

        if (!file_exists($filePath)) {
            $this->command->error("❌ Fichier Excel introuvable : $filePath");
            $this->command->info("   → Copiez le fichier dans storage/app/");
            return;
        }

        $spreadsheet = IOFactory::load($filePath);
        $sheet       = $spreadsheet->getActiveSheet();

        // toArray(nullValue, calculateFormulas, formatData, returnCellRef)
        $rows = $sheet->toArray(null, true, true, false);

        // user_id par défaut = 2 (Service Achat)
        $userId   = 2;
        $imported = 0;
        $skipped  = 0;

        // Variables pour le forward-fill (les colonnes ne sont remplies
        // que sur la première ligne de chaque bon de commande)
        $currentFournisseur = null;
        $currentDate        = null;
        $currentNumeroBc    = null;
        $currentChantier    = null;

        foreach ($rows as $index => $row) {
            // Sauter les lignes d'en-tête (rows 1-6 → index 0-5)
            if ($index < 6) {
                continue;
            }

            // Colonnes (0-based après toArray) :
            // 0=Fournisseur | 1=Date | 2=NumeroBc | 3=Designation
            // 4=Unite | 5=Qte | 6=P.U/HT | 7=P.T/HT
            // 8=Facture | 9=NumeroBL | 10=Paiement | 11=Chantier

            $designation = $this->clean($row[3]);

            // Ignorer les lignes sans désignation
            if (empty($designation)) {
                continue;
            }

            // ── Forward-fill ─────────────────────────────────────────────
            if (!empty($this->clean($row[0])))  $currentFournisseur = $this->clean($row[0]);
            if (!empty($row[1]))                 $currentDate        = $this->parseDate($row[1]);
            if (!empty($this->clean($row[2])))   $currentNumeroBc    = $this->clean($row[2]);
            if (!empty($this->clean($row[11])))  $currentChantier    = $this->clean($row[11]);

            // Si aucun chantier connu, on ne peut pas insérer (FK obligatoire)
            if (empty($currentChantier)) {
                $skipped++;
                continue;
            }

            // ── Calcul du montant (P.T/HT) ───────────────────────────────
            $qte    = is_numeric($row[5]) ? (float) $row[5] : null;
            $puHt   = is_numeric($row[6]) ? (float) $row[6] : null;

            if (is_numeric($row[7])) {
                $montant = (float) $row[7];
            } elseif ($qte !== null && $puHt !== null) {
                $montant = round($qte * $puHt, 2);
            } else {
                $montant = 0;
            }

            // ── Résolution Fournisseur (firstOrCreate) ────────────────────
            $fournisseurId = null;
            if ($currentFournisseur) {
                $fournisseur = Fournisseur::firstOrCreate(
                    ['nom' => $currentFournisseur],
                    [
                        'telephone' => null,
                        'email'     => null,
                        'adresse'   => null,
                    ]
                );
                $fournisseurId = $fournisseur->id;
            }

            // ── Résolution Chantier (firstOrCreate) ───────────────────────
            // Génère un code unique à partir du nom si le chantier n'existe pas
            $chantier = Chantier::firstOrCreate(
                ['nom' => $currentChantier],
                [
                    'code'        => $this->generateCode($currentChantier),
                    'responsable' => 'À définir',
                    'budget'      => 0,
                    'date_debut'  => now()->toDateString(),
                    'date_fin'    => null,
                    'statut'      => 'en_cours',
                    'localisation'=> '',
                    'description' => 'Importé depuis Excel',
                ]
            );

            // ── Mode paiement ─────────────────────────────────────────────
            $paiementRaw  = strtolower(trim($this->clean($row[10]) ?? ''));
            $modePaiement = $this->paiementMap[$paiementRaw] ?? null;

            // ── Insertion ─────────────────────────────────────────────────
            Depense::create([
                'chantier_id'     => $chantier->id,
                'fournisseur_id'  => $fournisseurId,
                'user_id'         => $userId,
                'designation'     => $designation,
                'montant'         => $montant,
                'date'            => $currentDate ?? now()->toDateString(),
                'numero_bc'       => $currentNumeroBc,
                'numero_bl'       => $this->clean($row[9]),
                'numero_facture'  => $this->clean($row[8]),
                'mode_paiement'   => $modePaiement,
                'statut_paiement' => 'en_attente',
                'statut_facture'  => 'non_payee',
                'type'            => 'bc',
            ]);

            $imported++;
        }

        $total = Depense::sum('montant');
        $this->command->info("✅ $imported dépenses importées depuis Excel.");
        if ($skipped > 0) {
            $this->command->warn("⚠️  $skipped lignes ignorées (chantier manquant).");
        }
        $this->command->info("💰 Total : " . number_format($total, 2, ',', ' ') . " MAD");
    }

    /**
     * Nettoie une valeur : retire espaces, sauts de ligne, etc.
     */
    private function clean($value): ?string
    {
        if ($value === null) {
            return null;
        }
        $v = trim(str_replace(["\n", "\r", "\t"], ' ', (string) $value));
        $v = preg_replace('/\s+/', ' ', $v);
        return $v === '' ? null : $v;
    }

    /**
     * Convertit une date Excel en string Y-m-d.
     */
    private function parseDate($value): ?string
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        }
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                ->format('Y-m-d');
        }
        if (is_string($value)) {
            try {
                return (new \DateTime($value))->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * Génère un code chantier court à partir du nom (ex: "SOFAC WALILI" → "CHT-SOFACW").
     * Évite les doublons en ajoutant un suffixe si nécessaire.
     */
    private function generateCode(string $nom): string
    {
        $base  = 'CHT-' . strtoupper(substr(preg_replace('/[^A-Z0-9]/i', '', $nom), 0, 7));
        $code  = $base;
        $count = 1;
        while (Chantier::where('code', $code)->exists()) {
            $code = $base . $count++;
        }
        return $code;
    }
}
