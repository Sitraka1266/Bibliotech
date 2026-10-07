<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class AdherentRequest extends BaseRequest
{
    public function rules(): array
    {
        $adherent = $this->route('adherent');

        return [
            'nom' => ['required', 'string', 'max:100'],
            'prenom' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', Rule::unique('adherents', 'email')->ignore($adherent?->id)],
            'telephone' => ['nullable', 'string', 'max:30'],
            'date_inscription' => ['required', 'date'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return parent::messages() + [
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre adhérent.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nom' => 'Nom',
            'prenom' => 'Prénom',
            'email' => 'Email',
            'telephone' => 'Téléphone',
            'date_inscription' => "Date d'inscription",
            'photo' => 'Photo de profil',
        ];
    }
}
