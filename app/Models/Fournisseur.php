<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
// use App\Traits\LogsActivity;

class Fournisseur extends Model
{
    // use LogsActivity;

    protected $fillable = ['nom', 'telephone', 'email', 'adresse', 'ice'];

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }

    public function totalDepenses(): float
    {
        return $this->depenses()->sum('montant');
    }
}