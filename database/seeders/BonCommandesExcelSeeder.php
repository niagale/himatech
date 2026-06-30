<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BonCommande;
use App\Models\BonCommandeArticle;
use App\Models\Chantier;
use App\Models\Depense;
use App\Models\Fournisseur;
use PhpOffice\PhpSpreadsheet\IOFactory;

class BonCommandesExcelSeeder extends Seeder
{
    private array $fournisseurMap = [
        'alminium du maroc'            => 'ALUMINIUM DU MAROC',
        'azz sani'                     => 'AAZ SANI',
        'arkglass'                     => 'ARKIGLASS',
        'sotelec'                      => 'SOTELEC',
        'ceramex'                      => 'CERAMEX',
        'home system solution'         => 'HOME SYSTEM SOLUTION',
        'continental  trading company' => 'CONTINENTAL TRADING COMPANY',
        'hm casablanca'                => 'HM',
        'alipalace'                    => 'ALUPALACE CASABLANCA',
    ];

    private array $chantierMap = [
        'cih hassan ii'             => 'CIH HASSAN II',
        'cih kacita'                => 'CIH KACITA',
        'sofac rabat'               => 'SOFAC RABAT',
        'sofac walili 5eme etg'     => 'SOFAC WALILI 5EME ETG',
        'ci chatila'                => 'CIH CHATILA',
        'sofac 8eme etege'          => 'SOFAC 8EME ETAGE SIEGE',
        'sofac 4eme et 5eme etage'  => 'SOFAC 4EME ETG',
        'sofac cc walili 5eme etg'  => 'SOFAC WALILI CC 5EME ETG',
        'sofac walili 4 eme etg'    => 'SOFAC WALILI 4EME ETG',
        'sofac walili 4eme rtg'     => 'SOFAC WALILI 4EME ETG',
        'sofac cc 5eme etage'       => 'SOFAC WALILI CC 5EME ETG',
        'sofac 5eme etage'          => 'SOFAC WALILI 5EME ETG',
        'sofac cc siege'            => 'SOFAC 8EME ETAGE SIEGE',
        'agence cih'                => 'PORTE DES AGENCE CIH',
        'r2am casa aero'            => 'RAM CASA AERO',
        'casa aero+ram'             => 'RAM CASA AERO',
        'iam cc'                    => 'IAM',
    ];

    private array $paiementMap = [
        'p'  => 'p',
        'P'  => 'p',
        'np' => 'NP',
        'NP' => 'NP',
    ];

    public function run(): void
    {
        $filePath = $this->findExcelFile();
        if (!$filePath) {
            $this->command->error('Fichier Excel introuvable.');
            $this->command->warn('Placez le fichier dans : storage/app/formatisation_initiale.xlsx');
            return;
        }

        $this->command->info('Fichier trouve : ' . $filePath);

        $spreadsheet = IOFactory::load($filePath);

        $feuil1 = $spreadsheet->getSheetByName('Feuil1');
        if (!$feuil1) {
            $this->command->error('Feuil1 introuvable dans le classeur.');
            return;
        }

        $bcImportes = $this->importerFeuille($feuil1, 0, true);
        $this->command->info(count($bcImportes) . ' BC importes depuis Feuil1.');

        $feuil2 = $spreadsheet->getSheetByName('Feuil2');
        if ($feuil2) {
            $bcSuppl = $this->importerFeuille($feuil2, 5, false, $bcImportes);
            $this->command->info(count($bcSuppl) . ' BC supplementaires importes depuis Feuil2.');
        }

        $this->command->info('--- BILAN ---');
        $this->command->info('BonCommandes  : ' . BonCommande::count());
        $this->command->info('Articles      : ' . BonCommandeArticle::count());
        $this->command->info('Depenses      : ' . Depense::count());
        $total = Depense::sum('montant');
        $this->command->info('Total TTC     : ' . number_format($total, 2, ',', ' ') . ' MAD');
    }

    private function importerFeuille(
        $sheet,
        int $skipRows,
        bool $hasFactureBL,
        array $skipBC = []
    ): array {
        $rows = $sheet->toArray(null, true, true, false);

        $groupes = [];
        $currentFournisseur = null;
        $currentDate        = null;
        $currentBC          = null;
        $currentChantier    = null;

        foreach ($rows as $idx => $row) {
            if ($idx < $skipRows) {
                continue;
            }

            $fournisseur = $this->clean($row[0] ?? null);
            $dateRaw     = $row[1] ?? null;
            $bcNum       = $this->clean($row[2] ?? null);
            $designation = $this->clean($row[3] ?? null);
            $unite       = $this->clean($row[4] ?? null);
            $qte         = isset($row[5]) && is_numeric($row[5]) ? (float) $row[5] : null;
            $puHt        = isset($row[6]) && is_numeric($row[6]) ? (float) $row[6] : null;
            $ptHt        = isset($row[7]) && is_numeric($row[7]) ? (float) $row[7] : null;
            $facture     = $hasFactureBL ? $this->clean($row[8] ?? null) : null;
            $numBL       = $hasFactureBL ? $this->clean($row[9] ?? null) : null;
            $paiement    = $hasFactureBL ? $this->clean($row[10] ?? null) : null;
            $chantier    = $this->clean($row[11] ?? null);

            if ($fournisseur) $currentFournisseur = $fournisseur;
            if ($dateRaw)     $currentDate        = $this->parseDate($dateRaw);
            if ($bcNum)       $currentBC          = $bcNum;
            if ($chantier)    $currentChantier    = $chantier;

            if (!$designation || !$currentBC) {
                continue;
            }

            if (!isset($groupes[$currentBC])) {
                $groupes[$currentBC] = [
                    'fournisseur' => $currentFournisseur,
                    'chantier'    => $currentChantier,
                    'date'        => $currentDate,
                    'facture'     => null,
                    'bl'          => null,
                    'paiement'    => null,
                    'articles'    => [],
                ];
            }

            if ($hasFactureBL) {
                if (!$groupes[$currentBC]['facture'] && $facture) {
                    $groupes[$currentBC]['facture'] = $facture;
                }
                if (!$groupes[$currentBC]['bl'] && $numBL) {
                    $groupes[$currentBC]['bl'] = $numBL;
                }
                if (!$groupes[$currentBC]['paiement'] && $paiement) {
                    $groupes[$currentBC]['paiement'] = $paiement;
                }
            }

            $montantHt = $ptHt ?? ($qte !== null && $puHt !== null ? round($qte * $puHt, 2) : 0);

            $groupes[$currentBC]['articles'][] = [
                'designation'      => $designation,
                'unite'            => $unite ?? 'U',
                'quantite'         => $qte !== null ? (int) round($qte) : 1,
                'prix_unitaire_ht' => $puHt ?? 0,
                'montant_ht'       => $montantHt,
            ];
        }

        $importes = [];

        foreach ($groupes as $bcNum => $data) {
            if (in_array($bcNum, $skipBC)) {
                continue;
            }
            if (BonCommande::where('numero_bc', $bcNum)->exists()) {
                continue;
            }
            if (empty($data['chantier']) || empty($data['articles'])) {
                continue;
            }

            $nomFournisseur = $this->normaliseFournisseur($data['fournisseur'] ?? '');
            $nomChantier    = $this->normaliseChantier($data['chantier']);

            $fournisseur = null;
            if ($nomFournisseur) {
                $fournisseur = Fournisseur::firstOrCreate(
                    ['nom' => $nomFournisseur],
                    ['telephone' => null, 'email' => null, 'adresse' => null]
                );
            }

            $chantier = Chantier::firstOrCreate(
                ['nom' => $nomChantier],
                [
                    'code'         => $this->generateCode($nomChantier),
                    'responsable'  => 'A definir',
                    'budget'       => 0,
                    'date_debut'   => now()->toDateString(),
                    'date_fin'     => null,
                    'statut'       => 'en_cours',
                    'localisation' => '',
                    'description'  => 'Importe depuis Excel',
                ]
            );

            $totalHt  = round(array_sum(array_column($data['articles'], 'montant_ht')), 2);
            $tva      = 20.00;
            $totalTva = round($totalHt * $tva / 100, 2);
            $totalTtc = round($totalHt + $totalTva, 2);

            $modePaiement = $this->normalisePaiement($data['paiement']);

            $bc = BonCommande::create([
                'numero_bc'            => $bcNum,
                'chantier_id'          => $chantier->id,
                'fournisseur_id'       => $fournisseur?->id,
                'user_id'              => 1,
                'date_commande'        => $data['date'] ?? now()->toDateString(),
                'date_livraison_prevue'=> null,
                'total_ht'             => $totalHt,
                'tva'                  => $tva,
                'total_tva'            => $totalTva,
                'total_ttc'            => $totalTtc,
                'mode_paiement'        => $modePaiement,
                'statut'               => 'en_attente',
                'commentaire'          => null,
            ]);

            foreach ($data['articles'] as $article) {
                BonCommandeArticle::create([
                    'bon_commande_id'   => $bc->id,
                    'code_article'      => null,
                    'designation'       => $article['designation'],
                    'unite'             => $article['unite'],
                    'quantite'          => $article['quantite'],
                    'prix_unitaire_ht'  => $article['prix_unitaire_ht'],
                    'montant_ht'        => $article['montant_ht'],
                ]);
            }

            $statutPaiement = $data['paiement'] ? 'paye' : 'en_attente';
            $statutFacture  = $data['facture']  ? 'payee' : 'non_payee';

            Depense::create([
                'chantier_id'     => $chantier->id,
                'fournisseur_id'  => $fournisseur?->id,
                'user_id'         => 1,
                'bon_commande_id' => $bc->id,
                'designation'     => 'Commande ' . $bcNum,
                'montant'         => $totalTtc,
                'date'            => $data['date'] ?? now()->toDateString(),
                'numero_bc'       => $bcNum,
                'numero_facture'  => $data['facture'],
                'numero_bl'       => $data['bl'],
                'mode_paiement'   => $modePaiement,
                'statut_paiement' => $statutPaiement,
                'statut_facture'  => $statutFacture,
                'type'            => 'bc',
                'commentaire'     => null,
            ]);

            $importes[] = $bcNum;
        }

        return $importes;
    }

    private function clean($value): ?string
    {
        if ($value === null) {
            return null;
        }
        $v = trim(str_replace(["\n", "\r", "\t"], ' ', (string) $value));
        $v = preg_replace('/\s+/', ' ', $v);
        return $v === '' ? null : $v;
    }

    private function parseDate($value): ?string
    {
        if ($value instanceof \DateTime) {
            return $value->format('Y-m-d');
        }
        if (is_numeric($value)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)
                ->format('Y-m-d');
        }
        if (is_string($value) && $value !== '') {
            $ts = strtotime($value);
            if ($ts !== false) {
                return date('Y-m-d', $ts);
            }
        }
        return null;
    }

    private function normaliseFournisseur(?string $nom): string
    {
        if (!$nom) {
            return '';
        }
        $nomClean = strtoupper(preg_replace('/\s+/', ' ', trim($nom)));
        $nomLower = strtolower($nomClean);
        return $this->fournisseurMap[$nomLower] ?? $nomClean;
    }

    private function normaliseChantier(?string $nom): string
    {
        if (!$nom) {
            return '';
        }
        $nomClean = strtoupper(preg_replace('/\s+/', ' ', trim($nom)));
        $nomLower = strtolower($nomClean);
        return $this->chantierMap[$nomLower] ?? $nomClean;
    }

    private function normalisePaiement(?string $paiement): ?string
    {
        if (!$paiement) {
            return null;
        }
        return $this->paiementMap[trim($paiement)] ?? null;
    }

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

    private function findExcelFile(): ?string
    {
        $paths = [
            storage_path('app/formatisation_initiale.xlsx'),
            storage_path('app/formatisation initiale.xlsx'),
            storage_path('app/imports/formatisation_initiale.xlsx'),
            storage_path('app/imports/formatisation initiale.xlsx'),
        ];
        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }
        return null;
    }
}
