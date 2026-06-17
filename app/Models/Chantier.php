<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\LogsActivity;

class Chantier extends Model
{
    // use LogsActivity;

    protected $fillable = [
        'nom', 'code', 'responsable', 'budget',
        'date_debut', 'date_fin', 'statut', 'localisation', 'description'
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin'   => 'date',
        'budget'     => 'decimal:2',
    ];

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }

    public function totalDepenses(): float
    {
        return $this->depenses()->sum('montant');
    }

    public function budgetRestant(): float
    {
        return $this->budget - $this->totalDepenses();
    }

    public function pourcentageConsomme(): float
    {
        if ($this->budget == 0) return 0;
        return round(($this->totalDepenses() / $this->budget) * 100, 2);
    }

    public function statutLabel(): string
    {
        return match($this->statut) {
            'en_cours'  => 'En cours',
            'termine'   => 'Terminé',
            'suspendu'  => 'Suspendu',
            'planifie'  => 'Planifié',
            default     => $this->statut,
        };
    }
}