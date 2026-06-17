<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('chantiers', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code')->unique();
            $table->string('responsable');
            $table->decimal('budget', 15, 2)->default(0);
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->enum('statut', ['en_cours', 'termine', 'suspendu', 'planifie'])->default('planifie');
            $table->string('localisation')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('chantiers'); }
};
