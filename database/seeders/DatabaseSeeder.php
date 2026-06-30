<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Chantier;
use App\Models\Fournisseur;
use App\Models\BonCommande;
use App\Models\BonCommandeArticle;
use App\Models\Depense;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // 1. UTILISATEURS
        // ============================================
        User::create(['name' => 'Administrateur', 'email' => 'admin@himatech.ma',     'password' => Hash::make('password'), 'role' => 'admin',     'actif' => true]);
        User::create(['name' => 'Service Achat',  'email' => 'achat@himatech.ma',     'password' => Hash::make('password'), 'role' => 'achat',     'actif' => true]);
        User::create(['name' => 'Service Finance','email' => 'finance@himatech.ma',   'password' => Hash::make('password'), 'role' => 'finance',   'actif' => true]);
        User::create(['name' => 'Direction',      'email' => 'direction@himatech.ma', 'password' => Hash::make('password'), 'role' => 'direction', 'actif' => true]);

        $this->command->info('4 utilisateurs crees');

        // ============================================
        // 2. IMPORT BONS DE COMMANDE + ARTICLES + DEPENSES depuis Excel
        //    (les fournisseurs et chantiers sont crees automatiquement)
        // ============================================
        $this->call(BonCommandesExcelSeeder::class);

        // ============================================
        // 3. RECAPITULATIF
        // ============================================
        $this->command->info('==========================================');
        $this->command->info('RECAPITULATIF');
        $this->command->info('==========================================');
        $this->command->info('Utilisateurs  : ' . User::count());
        $this->command->info('Fournisseurs  : ' . Fournisseur::count());
        $this->command->info('Chantiers     : ' . Chantier::count());
        $this->command->info('BonCommandes  : ' . BonCommande::count());
        $this->command->info('Articles      : ' . BonCommandeArticle::count());
        $this->command->info('Depenses      : ' . Depense::count());
        $this->command->info('Total TTC     : ' . number_format(Depense::sum('montant'), 2, ',', ' ') . ' MAD');
        $this->command->info('==========================================');
        $this->command->info('Comptes :');
        $this->command->info('  admin@himatech.ma     / password');
        $this->command->info('  achat@himatech.ma     / password');
        $this->command->info('  finance@himatech.ma   / password');
        $this->command->info('  direction@himatech.ma / password');
        $this->command->info('==========================================');
    }
}
