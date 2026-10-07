<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Adherent extends Model
{
    protected $fillable = ['nom', 'prenom', 'email', 'telephone', 'date_inscription', 'photo_path'];

    protected $casts = ['date_inscription' => 'date'];

    public function emprunts(): HasMany
    {
        return $this->hasMany(Emprunt::class);
    }

    public function nomComplet(): string
    {
        return $this->prenom.' '.mb_strtoupper($this->nom);
    }

    public function aUnRetard(): bool
    {
        return $this->emprunts()->enRetard()->exists();
    }
}
