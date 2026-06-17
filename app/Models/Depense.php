<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\LogsActivity;

class Depense extends Model
{
    // use LogsActivity;

    protected $fillable = [
        'chantier_id', 'fournisseur_id', 'user_id', 'bon_commande_id',
        'designation', 'montant', 'date',
        'numero_facture', 'numero_bc', 'numero_bl',
        'mode_paiement', 'statut_paiement', 'statut_facture', 'type',
        'commentaire', 'piece_jointe'
    ];

    protected $casts = [
        'date'    => 'date',
        'montant' => 'decimal:2',
    ];

    public function chantier()   { return $this->belongsTo(Chantier::class); }
    public function fournisseur(){ return $this->belongsTo(Fournisseur::class); }
    public function user()       { return $this->belongsTo(User::class); }
    public function bonCommande(){ return $this->belongsTo(BonCommande::class); }

    public function statutLabel(): string
    {
        return match($this->statut_facture ?? $this->statut_paiement) {
            'payee', 'paye'             => 'Payée',
            'non_payee'                 => 'Non payée',
            'en_attente'                => 'En attente',
            'partiellement_paye'        => 'Partiellement payé',
            default                     => $this->statut_paiement ?? 'Non payée',
        };
    }

    public function statutClass(): string
    {
        $statut = $this->statut_facture ?? $this->statut_paiement;
        return match($statut) {
            'payee', 'paye'             => 'bg-green-100 text-green-800',
            'non_payee'                 => 'bg-yellow-100 text-yellow-800',
            'en_attente'                => 'bg-yellow-100 text-yellow-800',
            'partiellement_paye'        => 'bg-blue-100 text-blue-800',
            default                     => 'bg-gray-100 text-gray-800',
        };
    }
}