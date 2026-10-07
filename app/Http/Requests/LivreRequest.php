<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class LivreRequest extends BaseRequest
{
    public function rules(): array
    {
        $livre = $this->route('livre');
        // Règle 6 : la quantité totale ne peut pas être inférieure au nombre d'exemplaires empruntés.
        $minimum = $livre ? $livre->nbEmpruntsEnCours() : 0;

        return [
            'titre' => ['required', 'string', 'max:255'],
            'auteur' => ['required', 'string', 'max:255'],
            'isbn' => ['required', 'string', 'max:20', Rule::unique('livres', 'isbn')->ignore($livre?->id)],
            'categorie' => ['required', 'string', 'max:100'],
            'annee' => ['required', 'integer', 'min:1000', 'max:'.date('Y')],
            'quantite_totale' => ['required', 'integer', 'min:'.$minimum],
        ];
    }

    public function messages(): array
    {
        $livre = $this->route('livre');
        $n = $livre ? $livre->nbEmpruntsEnCours() : 0;

        return parent::messages() + [
            'isbn.unique' => 'Cet ISBN est déjà utilisé par un autre livre.',
            'quantite_totale.min' => $n > 0
                ? "La quantité totale ne peut pas être inférieure au nombre d'exemplaires actuellement empruntés ($n)."
                : 'La quantité ne peut pas être négative.',
        ];
    }

    public function attributes(): array
    {
        return [
            'titre' => 'Titre',
            'auteur' => 'Auteur',
            'isbn' => 'ISBN',
            'categorie' => 'Catégorie',
            'annee' => 'Année',
            'quantite_totale' => 'Quantité totale',
        ];
    }
}
