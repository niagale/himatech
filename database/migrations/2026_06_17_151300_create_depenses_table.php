<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chantier_id')->constrained('chantiers')->onDelete('cascade');
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('designation');
            $table->decimal('montant', 15, 2);
            $table->date('date');
            $table->string('numero_facture')->nullable();
            $table->string('numero_bc')->nullable();
            $table->string('numero_bl')->nullable();
            $table->enum('mode_paiement', ['virement', 'cheque', 'especes', 'credit', 'carte', 'autre', 'p', 'NP'])->nullable();
            $table->enum('statut_paiement', ['en_attente', 'paye', 'partiellement_paye'])->default('en_attente');
            $table->enum('statut_facture', ['non_payee', 'payee'])->default('non_payee');
            $table->string('type')->default('bc');
            $table->foreignId('bon_commande_id')->nullable()->constrained('bon_commandes')->onDelete('set null');
            $table->text('commentaire')->nullable();
            $table->string('piece_jointe')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('depenses'); }
};