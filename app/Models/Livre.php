<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Livre extends Model
{
    protected $fillable = [
        'titre', 'auteur', 'isbn', 'categorie', 'annee',
        'quantite_totale', 'quantite_disponible',
    ];

    public function emprunts(): HasMany
    {
        return $this->hasMany(Emprunt::class);
    }

    public function nbEmpruntsEnCours(): int
    {
        return $this->emprunts()->enCours()->count();
    }
}
