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
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return parent::messages() + [
            'email.unique' => 'Cette adresse email est déjà utilisée par un autre adhérent.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.mimes' => 'Le fichier doit être au format JPG, JPEG, PNG ou WEBP.',
            'photo.max' => 'La taille de l\'image ne doit pas dépasser 10 Mo.',
            'photo.uploaded' => 'La photo n’a pas pu être envoyée. Vérifiez qu’elle ne dépasse pas 10 Mo, puis réessayez.',
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
