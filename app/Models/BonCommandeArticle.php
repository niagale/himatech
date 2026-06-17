<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonCommandeArticle extends Model
{
    protected $fillable = [
        'bon_commande_id', 'code_article', 'designation', 'unite',
        'quantite', 'prix_unitaire_ht', 'montant_ht'
    ];

    protected $casts = [
        'quantite' => 'integer',
        'prix_unitaire_ht' => 'decimal:2',
        'montant_ht' => 'decimal:2'
    ];

    public function bonCommande(): BelongsTo
    {
        return $this->belongsTo(BonCommande::class);
    }
}