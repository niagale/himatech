<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bon_commande_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bon_commande_id')->constrained()->onDelete('cascade');
            $table->string('code_article')->nullable();
            $table->string('designation');
            $table->string('unite')->default('pièce');
            $table->integer('quantite')->default(1);
            $table->decimal('prix_unitaire_ht', 12, 2);
            $table->decimal('montant_ht', 12, 2);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('bon_commande_articles'); }
};