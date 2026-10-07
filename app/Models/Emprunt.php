<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emprunt extends Model
{
    protected $fillable = [
        'livre_id', 'adherent_id', 'date_emprunt',
        'date_retour_prevue', 'date_retour_effective',
    ];

    protected $casts = [
        'date_emprunt' => 'date',
        'date_retour_prevue' => 'date',
        'date_retour_effective' => 'date',
    ];

    public function livre(): BelongsTo
    {
        return $this->belongsTo(Livre::class);
    }

    public function adherent(): BelongsTo
    {
        return $this->belongsTo(Adherent::class);
    }

    /** Emprunts non encore rendus. */
    public function scopeEnCours(Builder $query): Builder
    {
        return $query->whereNull('date_retour_effective');
    }

    /** Emprunts non rendus dont la date prévue est dépassée. */
    public function scopeEnRetard(Builder $query): Builder
    {
        return $query->whereNull('date_retour_effective')
            ->whereDate('date_retour_prevue', '<', today());
    }

    public function estEnCours(): bool
    {
        return is_null($this->date_retour_effective);
    }

    public function estEnRetard(): bool
    {
        return $this->estEnCours() && $this->date_retour_prevue->lt(today());
    }

    public function joursDeRetard(): int
    {
        return $this->estEnRetard() ? (int) $this->date_retour_prevue->diffInDays(today()) : 0;
    }
}
