<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('bon_commande_articles', function (Blueprint $table) {
            $table->text('designation')->change();
        });
    }
    public function down(): void {
        Schema::table('bon_commande_articles', function (Blueprint $table) {
            $table->string('designation', 255)->change();
        });
    }
};