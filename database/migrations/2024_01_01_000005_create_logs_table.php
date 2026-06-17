<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('action'); // create, update, delete, login, logout
            $table->string('table_name')->nullable(); // chantiers, depenses, fournisseurs, users
            $table->unsignedBigInteger('record_id')->nullable(); // ID de l'enregistrement concerné
            $table->text('old_values')->nullable(); // Anciennes valeurs (JSON)
            $table->text('new_values')->nullable(); // Nouvelles valeurs (JSON)
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('logs'); }
};