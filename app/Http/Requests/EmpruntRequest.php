<?php

namespace App\Http\Requests;

class EmpruntRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'livre_id' => ['required', 'integer', 'exists:livres,id'],
            'adherent_id' => ['required', 'integer', 'exists:adherents,id'],
            'duree' => ['nullable', 'integer', 'min:1', 'max:60'],
        ];
    }

    public function messages(): array
    {
        return parent::messages() + [
            'livre_id.required' => 'Veuillez choisir un livre.',
            'adherent_id.required' => 'Veuillez choisir un adhérent.',
        ];
    }

    public function attributes(): array
    {
        return [
            'livre_id' => 'Livre',
            'adherent_id' => 'Adhérent',
            'duree' => 'Durée (jours)',
        ];
    }
}
