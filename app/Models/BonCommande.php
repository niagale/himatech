<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonCommande extends Model
{
    protected $fillable = [
        'numero_bc', 'chantier_id', 'fournisseur_id', 'user_id',
        'date_commande', 'date_livraison_prevue', 'total_ht', 'tva',
        'total_tva', 'total_ttc', 'mode_paiement', 'commentaire',
        'statut', 'fichier_pdf'
    ];

    protected $casts = [
        'date_commande' => 'date',
        'date_livraison_prevue' => 'date',
        'total_ht' => 'decimal:2',
        'total_tva' => 'decimal:2',
        'total_ttc' => 'decimal:2',
        'tva' => 'decimal:2'
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(BonCommandeArticle::class);
    }

    public function chantier(): BelongsTo
    {
        return $this->belongsTo(Chantier::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function depenses(): HasMany
    {
        return $this->hasMany(Depense::class);
    }

    public function recalculerTotaux(): void
    {
        $this->total_ht = $this->articles->sum('montant_ht');
        $this->total_tva = $this->total_ht * ($this->tva / 100);
        $this->total_ttc = $this->total_ht + $this->total_tva;
        $this->saveQuietly();
    }
}