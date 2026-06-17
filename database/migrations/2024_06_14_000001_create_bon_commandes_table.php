<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bon_commandes', function (Blueprint $table) {
            $table->id();
            $table->string('numero_bc')->unique();
            $table->foreignId('chantier_id')->constrained();
            $table->foreignId('fournisseur_id')->constrained();
            $table->foreignId('user_id')->constrained(); // créé par
            $table->date('date_commande');
            $table->date('date_livraison_prevue')->nullable();
            $table->decimal('total_ht', 12, 2)->default(0);
            $table->decimal('tva', 5, 2)->default(20);
            $table->decimal('total_tva', 12, 2)->default(0);
            $table->decimal('total_ttc', 12, 2)->default(0);
            $table->string('mode_paiement')->nullable();
            $table->text('commentaire')->nullable();
            $table->string('statut')->default('en_attente'); // en_attente, accepte, partiel, recu
            $table->string('fichier_pdf')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('bon_commandes'); }
};