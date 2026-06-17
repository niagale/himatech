<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Chantier;
use App\Models\Fournisseur;
use App\Models\Depense;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // 1. UTILISATEURS
        // ============================================
        User::create(['name' => 'Administrateur', 'email' => 'admin@himatech.ma', 'password' => Hash::make('password'), 'role' => 'admin', 'actif' => true]);
        User::create(['name' => 'Service Achat',  'email' => 'achat@himatech.ma', 'password' => Hash::make('password'), 'role' => 'achat', 'actif' => true]);
        User::create(['name' => 'Service Finance','email' => 'finance@himatech.ma','password' => Hash::make('password'), 'role' => 'finance', 'actif' => true]);
        User::create(['name' => 'Direction',      'email' => 'direction@himatech.ma','password' => Hash::make('password'), 'role' => 'direction', 'actif' => true]);

        $this->command->info('✅ 4 utilisateurs créés');

        // ============================================
        // 2. FOURNISSEURS
        // ============================================
        $f1 = Fournisseur::create(['nom' => 'SONASID Acier', 'telephone' => '0522123456', 'email' => 'contact@sonasid.ma', 'adresse' => 'Casablanca', 'ice' => '001234567890123']);
        $f2 = Fournisseur::create(['nom' => 'Ciments du Maroc', 'telephone' => '0522987654', 'email' => 'commercial@cimmaroc.ma', 'adresse' => 'Casablanca', 'ice' => '009876543210987']);
        $f3 = Fournisseur::create(['nom' => 'BRICO PRO Matériaux', 'telephone' => '0661234567', 'email' => 'contact@bricopro.ma', 'adresse' => 'Rabat', 'ice' => '007654321098765']);
        $f4 = Fournisseur::create(['nom' => 'Élec Maroc', 'telephone' => '0522334455', 'email' => 'sav@elecmaroc.ma', 'adresse' => 'Tanger', 'ice' => '008765432109876']);
        $f5 = Fournisseur::create(['nom' => 'Sanitaire Distribution', 'telephone' => '0522556677', 'email' => 'contact@sanitaire.ma', 'adresse' => 'Marrakech', 'ice' => '009876543210123']);

        $this->command->info('✅ 5 fournisseurs créés');

        // ============================================
        // 3. CHANTIERS
        // ============================================
        $c1 = Chantier::create([
            'nom' => 'Chantier Casablanca Centre',
            'code' => 'CHT-001',
            'responsable' => 'Mohamed Alami',
            'budget' => 500000,
            'date_debut' => '2024-01-15',
            'date_fin' => '2024-12-31',
            'statut' => 'en_cours',
            'localisation' => 'Casablanca',
            'description' => 'Construction immeuble R+5'
        ]);

        $c2 = Chantier::create([
            'nom' => 'Résidence Ain Diab',
            'code' => 'CHT-002',
            'responsable' => 'Fatima Benali',
            'budget' => 350000,
            'date_debut' => '2024-03-01',
            'date_fin' => '2024-11-30',
            'statut' => 'en_cours',
            'localisation' => 'Ain Diab, Casablanca',
            'description' => 'Résidence de luxe'
        ]);

        $c3 = Chantier::create([
            'nom' => 'Complexe Industriel Berrechid',
            'code' => 'CHT-003',
            'responsable' => 'Karim Nassiri',
            'budget' => 800000,
            'date_debut' => '2023-06-01',
            'date_fin' => '2024-02-28',
            'statut' => 'termine',
            'localisation' => 'Berrechid',
            'description' => 'Extension zone industrielle'
        ]);

        $c4 = Chantier::create([
            'nom' => 'Marrakech Palmeraie Resort',
            'code' => 'CHT-004',
            'responsable' => 'Sofia El Mansouri',
            'budget' => 600000,
            'date_debut' => '2024-02-10',
            'date_fin' => '2024-10-15',
            'statut' => 'en_cours',
            'localisation' => 'Marrakech',
            'description' => 'Complexe hôtelier'
        ]);

        $c5 = Chantier::create([
            'nom' => 'Tanger Ville Nouvelle',
            'code' => 'CHT-005',
            'responsable' => 'Youssef Tazi',
            'budget' => 450000,
            'date_debut' => '2024-04-01',
            'date_fin' => '2025-01-31',
            'statut' => 'planifie',
            'localisation' => 'Tanger',
            'description' => 'Aménagement urbain'
        ]);

        $this->command->info('✅ 5 chantiers créés');

        // ============================================
        // 4. DÉPENSES (réparties sur plusieurs mois)
        // ============================================
        
        // JANVIER 2024
        Depense::create(['chantier_id' => $c1->id, 'fournisseur_id' => $f1->id, 'user_id' => 2, 'designation' => 'Acier HA12 (10 tonnes)', 'montant' => 65000, 'date' => '2024-01-20', 'numero_facture' => 'FAC-JAN-001', 'numero_bc' => 'BC-001', 'statut_paiement' => 'paye', 'mode_paiement' => 'virement']);
        Depense::create(['chantier_id' => $c1->id, 'fournisseur_id' => $f2->id, 'user_id' => 2, 'designation' => 'Ciment CPJ45 (500 sacs)', 'montant' => 25000, 'date' => '2024-01-25', 'numero_facture' => 'FAC-JAN-002', 'statut_paiement' => 'paye', 'mode_paiement' => 'cheque']);
        Depense::create(['chantier_id' => $c3->id, 'fournisseur_id' => $f1->id, 'user_id' => 2, 'designation' => 'Poutrelles IPE 200', 'montant' => 45000, 'date' => '2024-01-15', 'numero_facture' => 'FAC-JAN-003', 'statut_paiement' => 'paye', 'mode_paiement' => 'virement']);

        // FÉVRIER 2024
        Depense::create(['chantier_id' => $c1->id, 'fournisseur_id' => $f3->id, 'user_id' => 2, 'designation' => 'Bois de coffrage', 'montant' => 18000, 'date' => '2024-02-10', 'numero_facture' => 'FAC-FEV-001', 'statut_paiement' => 'paye', 'mode_paiement' => 'virement']);
        Depense::create(['chantier_id' => $c1->id, 'fournisseur_id' => $f4->id, 'user_id' => 2, 'designation' => 'Câblage électrique', 'montant' => 22000, 'date' => '2024-02-20', 'numero_facture' => 'FAC-FEV-002', 'statut_paiement' => 'en_attente', 'mode_paiement' => 'cheque']);
        Depense::create(['chantier_id' => $c4->id, 'fournisseur_id' => $f2->id, 'user_id' => 2, 'designation' => 'Ciment (300 sacs)', 'montant' => 15000, 'date' => '2024-02-15', 'numero_facture' => 'FAC-FEV-003', 'statut_paiement' => 'paye', 'mode_paiement' => 'virement']);
        Depense::create(['chantier_id' => $c3->id, 'fournisseur_id' => $f5->id, 'user_id' => 2, 'designation' => 'Sanitaires (lots)', 'montant' => 32000, 'date' => '2024-02-28', 'numero_facture' => 'FAC-FEV-004', 'statut_paiement' => 'paye', 'mode_paiement' => 'virement']);

        // MARS 2024
        Depense::create(['chantier_id' => $c2->id, 'fournisseur_id' => $f1->id, 'user_id' => 2, 'designation' => 'Acier HA16 (8 tonnes)', 'montant' => 52000, 'date' => '2024-03-05', 'numero_facture' => 'FAC-MAR-001', 'statut_paiement' => 'paye', 'mode_paiement' => 'virement']);
        Depense::create(['chantier_id' => $c2->id, 'fournisseur_id' => $f3->id, 'user_id' => 2, 'designation' => 'Matériaux divers', 'montant' => 28000, 'date' => '2024-03-15', 'numero_facture' => 'FAC-MAR-002', 'statut_paiement' => 'partiellement_paye', 'mode_paiement' => 'cheque']);
        Depense::create(['chantier_id' => $c4->id, 'fournisseur_id' => $f4->id, 'user_id' => 2, 'designation' => 'Installation électrique', 'montant' => 35000, 'date' => '2024-03-20', 'numero_facture' => 'FAC-MAR-003', 'statut_paiement' => 'en_attente', 'mode_paiement' => 'virement']);
        Depense::create(['chantier_id' => $c1->id, 'fournisseur_id' => $f5->id, 'user_id' => 2, 'designation' => 'Équipements sanitaires', 'montant' => 42000, 'date' => '2024-03-25', 'numero_facture' => 'FAC-MAR-004', 'statut_paiement' => 'paye', 'mode_paiement' => 'virement']);

        // AVRIL 2024
        Depense::create(['chantier_id' => $c2->id, 'fournisseur_id' => $f2->id, 'user_id' => 2, 'designation' => 'Ciment (400 sacs)', 'montant' => 20000, 'date' => '2024-04-10', 'numero_facture' => 'FAC-AVR-001', 'statut_paiement' => 'paye', 'mode_paiement' => 'virement']);
        Depense::create(['chantier_id' => $c2->id, 'fournisseur_id' => $f5->id, 'user_id' => 2, 'designation' => 'Carrelage et faïence', 'montant' => 38000, 'date' => '2024-04-18', 'numero_facture' => 'FAC-AVR-002', 'statut_paiement' => 'en_attente', 'mode_paiement' => 'cheque']);
        Depense::create(['chantier_id' => $c4->id, 'fournisseur_id' => $f1->id, 'user_id' => 2, 'designation' => 'Acier HA12 (6 tonnes)', 'montant' => 39000, 'date' => '2024-04-22', 'numero_facture' => 'FAC-AVR-003', 'statut_paiement' => 'paye', 'mode_paiement' => 'virement']);
        Depense::create(['chantier_id' => $c5->id, 'fournisseur_id' => $f3->id, 'user_id' => 2, 'designation' => 'Matériel de chantier', 'montant' => 25000, 'date' => '2024-04-25', 'numero_facture' => 'FAC-AVR-004', 'statut_paiement' => 'paye', 'mode_paiement' => 'virement']);

        // MAI 2024
        Depense::create(['chantier_id' => $c4->id, 'fournisseur_id' => $f3->id, 'user_id' => 2, 'designation' => 'Menuiserie bois', 'montant' => 48000, 'date' => '2024-05-05', 'numero_facture' => 'FAC-MAI-001', 'statut_paiement' => 'partiellement_paye', 'mode_paiement' => 'virement']);
        Depense::create(['chantier_id' => $c4->id, 'fournisseur_id' => $f5->id, 'user_id' => 2, 'designation' => 'Robinetterie et sanitaires', 'montant' => 22000, 'date' => '2024-05-12', 'numero_facture' => 'FAC-MAI-002', 'statut_paiement' => 'en_attente', 'mode_paiement' => 'cheque']);
        Depense::create(['chantier_id' => $c2->id, 'fournisseur_id' => $f4->id, 'user_id' => 2, 'designation' => 'Éclairage extérieur', 'montant' => 18000, 'date' => '2024-05-20', 'numero_facture' => 'FAC-MAI-003', 'statut_paiement' => 'paye', 'mode_paiement' => 'virement']);

        // JUIN 2024
        Depense::create(['chantier_id' => $c1->id, 'fournisseur_id' => $f4->id, 'user_id' => 2, 'designation' => 'Tableaux électriques', 'montant' => 15000, 'date' => '2024-06-01', 'numero_facture' => 'FAC-JUN-001', 'statut_paiement' => 'en_attente', 'mode_paiement' => 'cheque']);
        Depense::create(['chantier_id' => $c5->id, 'fournisseur_id' => $f1->id, 'user_id' => 2, 'designation' => 'Acier (premier lot)', 'montant' => 35000, 'date' => '2024-06-10', 'numero_facture' => 'FAC-JUN-002', 'statut_paiement' => 'paye', 'mode_paiement' => 'virement']);

        $this->command->info('✅ 20 dépenses créées (réparties sur 6 mois)');

        // ============================================
        // 5. RÉCAPITULATIF
        // ============================================
        $this->command->info("\n==========================================");
        $this->command->info("📊 RÉCAPITULATIF DES DONNÉES");
        $this->command->info("==========================================");
        $this->command->info("Utilisateurs : " . User::count());
        $this->command->info("Chantiers : " . Chantier::count());
        $this->command->info("Fournisseurs : " . Fournisseur::count());
        $this->command->info("Dépenses : " . Depense::count());
        $this->command->info("Total dépenses : " . number_format(Depense::sum('montant'), 2, ',', ' ') . " MAD");
        $this->command->info("==========================================");
        $this->command->info("");
        $this->command->info("🔑 Comptes de test :");
        $this->command->info("   admin@himatech.ma / password");
        $this->command->info("   achat@himatech.ma / password");
        $this->command->info("   finance@himatech.ma / password");
        $this->command->info("   direction@himatech.ma / password");
        $this->command->info("==========================================");
    }
}